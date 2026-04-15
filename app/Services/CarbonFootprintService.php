<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventCarbonReport;

class CarbonFootprintService
{
    // CO2 emission factors (kg CO2 per person-km or unit)
    const FACTORS = [
        'car_avg' => 0.210,
        'car_electric' => 0.053,
        'flight_short' => 0.255,   // < 1000 km
        'flight_long' => 0.195,    // > 1000 km
        'train' => 0.032,
        'bus' => 0.089,
        'electricity_at_grid' => 0.158,
        'electricity_renewable' => 0.010,
        'diesel_generator' => 2.680,  // per liter
        'catering_standard' => 7.0,   // per person per day
        'catering_vegetarian' => 2.5,
        'catering_vegan' => 1.7,
        'hotel_avg' => 18.0,          // per night per person
        'hotel_eco' => 8.5,
        'truck_diesel' => 0.75,       // per km per full truck
    ];

    public function calculate(Event $event): EventCarbonReport
    {
        $report = new EventCarbonReport([
            'event_id' => $event->id,
            'organization_id' => $event->organization_id,
            'calculated_at' => now(),
            'participants_count' => $event->expected_participants ?? 0,
        ]);

        $report->co2_participant_travel = $this->calculateParticipantTravel($event);
        $report->co2_staff_travel = 0; // Can be extended with staff travel data
        $report->co2_tv_crew_travel = $this->calculateTvCrewTravel($event);
        $report->co2_equipment_transport = $this->calculateEquipmentTransport($event);
        $report->co2_venue_energy = $this->calculateVenueEnergy($event);
        $report->co2_catering = $this->calculateCatering($event);
        $report->co2_accommodation = $this->calculateAccommodation($event);
        $report->co2_tv_production_power = $this->calculateTvPower($event);
        $report->co2_other = 0;

        $grossTotal = array_sum([
            $report->co2_participant_travel,
            $report->co2_staff_travel,
            $report->co2_tv_crew_travel,
            $report->co2_equipment_transport,
            $report->co2_venue_energy,
            $report->co2_catering,
            $report->co2_accommodation,
            $report->co2_tv_production_power,
        ]);

        $compensation = 0;
        if ($mobility = $event->mobility) {
            $compensation = (float) ($mobility->ghg_compensation_amount_kg ?? 0);
        }

        $report->co2_total = $grossTotal;
        $report->co2_compensation = $compensation;
        $report->co2_net = max(0, $grossTotal - $compensation);
        $report->co2_per_person = $event->expected_participants > 0
            ? $report->co2_net / $event->expected_participants
            : 0;
        $report->is_carbon_neutral = $report->co2_net <= 0;
        $report->benchmark_avg_event_co2_per_person = 100.0;
        $report->benchmark_green_event_co2_per_person = 30.0;
        $report->report_generated_at = now();

        $report->save();

        // Update event
        $event->update([
            'carbon_footprint_kg_co2' => $report->co2_net,
            'carbon_footprint_per_person' => $report->co2_per_person,
        ]);

        return $report;
    }

    private function calculateParticipantTravel(Event $event): float
    {
        $mobility = $event->mobility;
        if (!$mobility) return 0;

        $participants = $event->expected_participants ?? 0;
        if ($participants === 0) return 0;

        // Estimate average travel distance (km one way × 2)
        $avgDistanceKm = 200; // Default assumption: 200 km one-way

        $co2 = 0;
        $co2 += ($mobility->modal_split_car_percent / 100) * $participants * $avgDistanceKm * 2 * self::FACTORS['car_avg'];
        $co2 += ($mobility->modal_split_public_transport_percent / 100) * $participants * $avgDistanceKm * 2 * self::FACTORS['train'];
        $co2 += ($mobility->modal_split_bike_percent / 100) * $participants * 0; // 0 emissions
        $co2 += ($mobility->modal_split_flight_percent / 100) * $participants * $avgDistanceKm * 2 * self::FACTORS['flight_short'];

        return round($co2, 2);
    }

    private function calculateTvCrewTravel(Event $event): float
    {
        $tv = $event->tvProduction;
        if (!$tv || !$tv->has_tv_production) return 0;

        $co2 = 0;
        $co2 += ($tv->crew_travel_car_persons ?? 0) * ($tv->crew_travel_car_km_avg ?? 0) * 2 * self::FACTORS['car_avg'];
        $co2 += ($tv->crew_travel_plane_persons ?? 0) * ($tv->crew_travel_plane_km_avg ?? 0) * 2 * self::FACTORS['flight_short'];
        $co2 += ($tv->crew_travel_train_persons ?? 0) * ($tv->crew_travel_train_km_avg ?? 0) * 2 * self::FACTORS['train'];

        return round($co2, 2);
    }

    private function calculateEquipmentTransport(Event $event): float
    {
        $tv = $event->tvProduction;
        if (!$tv) return 0;

        $trucks = $tv->equipment_transport_trucks ?? 0;
        $km = $tv->equipment_transport_km ?? 0;

        return round($trucks * $km * 2 * self::FACTORS['truck_diesel'], 2);
    }

    private function calculateVenueEnergy(Event $event): float
    {
        $tech = $event->technology;
        if (!$tech) return 0;

        $kwh = $tech->power_consumption_kwh ?? 0;
        $factor = ($tech->power_source === 'renewable') ? self::FACTORS['electricity_renewable'] : self::FACTORS['electricity_at_grid'];

        return round($kwh * $factor, 2);
    }

    private function calculateCatering(Event $event): float
    {
        $catering = $event->catering;
        $participants = $event->expected_participants ?? 0;
        $days = $event->getDurationDays();

        if (!$catering) {
            return round($participants * $days * self::FACTORS['catering_standard'], 2);
        }

        if ($catering->vegan_full_menu) {
            $factor = self::FACTORS['catering_vegan'];
        } elseif ($catering->vegetarian_full_menu) {
            $factor = self::FACTORS['catering_vegetarian'];
        } else {
            $factor = self::FACTORS['catering_standard'];
        }

        return round($participants * $days * $factor, 2);
    }

    private function calculateAccommodation(Event $event): float
    {
        $accommodations = $event->accommodations;
        $co2 = 0;

        foreach ($accommodations as $hotel) {
            $nights = $hotel->nights_reserved ?? 0;
            $persons = $hotel->contingent_reserved ?? 0;
            $factor = $hotel->has_env_certification ? self::FACTORS['hotel_eco'] : self::FACTORS['hotel_avg'];
            $co2 += $nights * $persons * $factor;
        }

        return round($co2, 2);
    }

    private function calculateTvPower(Event $event): float
    {
        $tv = $event->tvProduction;
        if (!$tv || !$tv->has_tv_production) return 0;

        $liters = $tv->generator_fuel_liters ?? 0;
        $kwh = $tv->power_consumed_kwh ?? 0;

        $co2 = $liters * self::FACTORS['diesel_generator'];
        $co2 += $kwh * self::FACTORS['electricity_at_grid'];

        return round($co2, 2);
    }
}
