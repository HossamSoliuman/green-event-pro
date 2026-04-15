<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventUz62Score;

class UZ62ScoringService
{
    const PASSING_THRESHOLD = 0.28; // 28% of achievable points

    public function calculateScore(Event $event): EventUz62Score
    {
        $result = $this->evaluate($event);

        $score = new EventUz62Score([
            'event_id' => $event->id,
            'organization_id' => $event->organization_id,
            'calculated_at' => now(),
            'event_type' => $event->type,
            'muss_passed' => $result['muss_passed'],
            'muss_failed_criteria' => $result['muss_failed'],
            'points_achieved' => $result['points_achieved'],
            'points_max' => $result['points_max'],
            'percentage' => $result['percentage'],
            'passed' => $result['passed'],
            'section_scores' => $result['sections'],
            'certification_eligible' => $result['passed'],
            'certification_phase' => $event->certification_phase ?? 'none',
            'report_generated_at' => now(),
        ]);

        $score->save();

        $event->update([
            'uz62_score' => $result['points_achieved'],
            'uz62_score_max' => $result['points_max'],
            'uz62_percentage' => $result['percentage'],
            'uz62_passed' => $result['passed'],
        ]);

        return $score;
    }

    public function evaluate(Event $event): array
    {
        $mussFailed = [];
        $sectionsResult = [];
        $totalAchieved = 0;
        $totalMax = 0;

        // Evaluate each section
        $sections = [
            'mobility' => $this->evaluateMobility($event),
            'accommodation' => $this->evaluateAccommodation($event),
            'venue' => $this->evaluateVenue($event),
            'procurement' => $this->evaluateProcurement($event),
            'exhibitors' => $this->evaluateExhibitors($event),
            'catering' => $this->evaluateCatering($event),
            'communication' => $this->evaluateCommunication($event),
            'social' => $this->evaluateSocial($event),
            'technology' => $this->evaluateTechnology($event),
        ];

        foreach ($sections as $key => $section) {
            $mussFailed = array_merge($mussFailed, $section['muss_failed'] ?? []);
            $totalAchieved += $section['points_achieved'];
            $totalMax += $section['points_max'];
            $sectionsResult[$key] = $section;
        }

        $mussPassed = empty($mussFailed);
        $percentage = $totalMax > 0 ? ($totalAchieved / $totalMax) * 100 : 0;
        $passed = $mussPassed && ($percentage >= (self::PASSING_THRESHOLD * 100));

        return [
            'muss_passed' => $mussPassed,
            'muss_failed' => $mussFailed,
            'points_achieved' => round($totalAchieved, 2),
            'points_max' => round($totalMax, 2),
            'percentage' => round($percentage, 2),
            'passed' => $passed,
            'sections' => $sectionsResult,
        ];
    }

    private function evaluateMobility(Event $event): array
    {
        $m = $event->mobility;
        $mussFailed = [];
        $points = 0;
        $criteria = [];

        if (!$m) {
            $mussFailed[] = 'M1';
            return ['muss_failed' => $mussFailed, 'points_achieved' => 0, 'points_max' => 27.5, 'criteria' => []];
        }

        // MUSS criteria
        $m1 = $m->venue_accessible_by_public_transport || $m->shuttle_service_provided;
        if (!$m1) $mussFailed[] = 'M1';
        $criteria[] = ['code' => 'M1', 'type' => 'muss', 'passed' => $m1, 'points' => 0];

        if ($event->is_hybrid && !$m->hybrid_replaces_flights) {
            $mussFailed[] = 'M17';
            $criteria[] = ['code' => 'M17', 'type' => 'muss', 'passed' => false, 'points' => 0];
        } elseif ($event->is_hybrid) {
            $criteria[] = ['code' => 'M17', 'type' => 'muss', 'passed' => true, 'points' => 0];
        }

        // SOLL criteria - points
        $sollCriteria = [
            ['code' => 'M4', 'field' => 'bike_parking_spaces', 'condition' => fn($v) => $v >= 1, 'points' => 0.5],
            ['code' => 'M4b', 'field' => 'bike_parking_secured', 'condition' => fn($v) => $v, 'points' => 0.5],
            ['code' => 'M4c', 'field' => 'ebike_charging_stations', 'condition' => fn($v) => $v >= 1, 'points' => 0.5],
            ['code' => 'M5', 'field' => 'bike_rental_available', 'condition' => fn($v) => $v, 'points' => 0.5],
            ['code' => 'M5b', 'field' => 'bike_repair_station', 'condition' => fn($v) => $v, 'points' => 0.5],
            ['code' => 'M6c', 'field' => 'ticket_cooperation_with_transport', 'condition' => fn($v) => $v, 'points' => 1.5],
            ['code' => 'M6d', 'field' => 'transport_ticket_booked_for_participants', 'condition' => fn($v) => $v, 'points' => 3.0],
            ['code' => 'M6e', 'field' => 'carpooling_organized', 'condition' => fn($v) => $v, 'points' => 1.0],
            ['code' => 'M6f', 'field' => 'group_travel_organized', 'condition' => fn($v) => $v, 'points' => 1.5],
            ['code' => 'M10a', 'field' => 'transport_company_euro6_compliant', 'condition' => fn($v) => $v, 'points' => 1.0],
            ['code' => 'M10b', 'field' => 'transport_company_has_env_policy', 'condition' => fn($v) => $v, 'points' => 1.0],
            ['code' => 'M10c', 'field' => 'transport_company_is_regional', 'condition' => fn($v) => $v, 'points' => 1.0],
            ['code' => 'M11', 'field' => 'modal_split_surveyed', 'condition' => fn($v) => $v, 'points' => 1.5],
            ['code' => 'M12', 'field' => 'ghg_compensation_communicated', 'condition' => fn($v) => $v, 'points' => 1.0],
            ['code' => 'M13', 'field' => 'ghg_calculation_done', 'condition' => fn($v) => $v, 'points' => 2.0],
            ['code' => 'M14', 'field' => 'ghg_compensation_done', 'condition' => fn($v) => $v, 'points' => 3.0],
            ['code' => 'M16', 'field' => 'event_times_aligned_to_transport', 'condition' => fn($v) => $v, 'points' => 1.0],
        ];

        foreach ($sollCriteria as $c) {
            $val = $m->{$c['field']} ?? null;
            $passed = $c['condition']($val);
            if ($passed) $points += $c['points'];
            $criteria[] = ['code' => $c['code'], 'type' => 'soll', 'passed' => $passed, 'points' => $c['points']];
        }

        return ['muss_failed' => $mussFailed, 'points_achieved' => $points, 'points_max' => 27.5, 'criteria' => $criteria];
    }

    private function evaluateAccommodation(Event $event): array
    {
        $accommodations = $event->accommodations;
        $mussFailed = [];
        $points = 0;

        $hasCertified = $accommodations->where('has_env_certification', true)->isNotEmpty();
        $allInformed = $accommodations->where('hotel_informed_of_green_event', false)->isEmpty() && $accommodations->isNotEmpty();

        if (!$hasCertified && $accommodations->isNotEmpty()) $mussFailed[] = 'U1';
        if (!$allInformed && $accommodations->isNotEmpty()) $mussFailed[] = 'U2';

        // U3 points (max 12)
        foreach ($accommodations as $hotel) {
            if ($hotel->certification_type === 'umweltzeichen' || $hotel->certification_type === 'eu_ecolabel' || $hotel->has_env_certification) {
                $points += 3;
            } elseif ($hotel->has_secondary_certification) {
                $points += 2;
            } elseif ($hotel->self_declaration_completed && $hotel->self_declaration_points >= 15) {
                $points += 1;
            }
        }

        return ['muss_failed' => $mussFailed, 'points_achieved' => min($points, 12), 'points_max' => 12, 'criteria' => []];
    }

    private function evaluateVenue(Event $event): array
    {
        $v = $event->venue;
        $mussFailed = [];
        $points = 0;

        if (!$v) return ['muss_failed' => [], 'points_achieved' => 0, 'points_max' => 33, 'criteria' => []];

        // MUSS
        if (!$v->has_waste_management_concept) $mussFailed[] = 'Va2';
        if (!$v->toilet_waste_bins_present) $mussFailed[] = 'Va3';
        if ($event->is_outdoor) {
            if (!$v->not_in_protected_area) $mussFailed[] = 'Vb1';
            if (!$v->has_nature_protection_concept) $mussFailed[] = 'Vb2';
            if (!$v->temporary_buildings_fully_dismantled) $mussFailed[] = 'Vb3';
            if (!$v->wastewater_properly_disposed) $mussFailed[] = 'Vb6';
        }

        // Auto-satisfy if UZ 200 certified
        if ($v->env_cert_type === 'umweltzeichen_uz200' && $v->has_env_certification) {
            $points += 15.5; // Auto-granted per UZ 62 §3a
        } else {
            // Energy points
            if ($v->led_percentage >= 80) $points += 1.0;
            if ($v->renewable_electricity_100pct) $points += 0.5;
            if ($v->umweltzeichen_green_electricity) $points += 3.0;
            if ($v->own_renewable_energy_system) $points += 2.0;
            if ($v->renewable_heating_cooling_50pct) $points += 1.5;
            if ($v->renewable_heating_cooling_100pct) $points += 3.0;
            if ($v->has_energy_certificate) $points += 1.0;
            if ($v->energy_optimization_program) $points += 1.0;

            // Water
            if ($v->toilet_flush_stop_button) $points += 0.5;
            if ($v->waterless_urinals) $points += 0.5;
            if ($v->tap_flow_under_9l) $points += 0.5;
            if ($v->rainwater_reuse) $points += 1.0;

            // Sustainability
            if ($v->has_sustainability_concept) $points += 1.0;
            if ($v->has_energy_bookkeeping) $points += 0.5;
            if ($v->co2_calculation_done) $points += 0.5;

            // Mobility
            if ($v->has_mobility_concept) $points += 1.0;
            if ($v->ev_charging_stations_renewable) $points += 1.0;
            if ($v->bike_parking_available) $points += 0.5;
        }

        // Outdoor extra points
        if ($event->is_outdoor) {
            if ($v->eco_mobile_toilets) $points += 2.0;
            if ($v->green_electricity_100pct) $points += 2.0;
            if ($v->alternative_power_generation) $points += 3.0;
            if ($v->env_officer_on_site) $points += 2.0;
        }

        return ['muss_failed' => $mussFailed, 'points_achieved' => min($points, 33), 'points_max' => 33, 'criteria' => []];
    }

    private function evaluateProcurement(Event $event): array
    {
        $p = $event->procurement;
        $mussFailed = [];
        $points = 0;
        $maxPoints = $event->isSportEvent() ? 35 : ($event->isMeeting() ? 32 : 29);

        if (!$p) return ['muss_failed' => [], 'points_achieved' => 0, 'points_max' => $maxPoints, 'criteria' => []];

        // MUSS
        if (!$p->has_waste_concept) $mussFailed[] = 'B1';
        if (!$p->reusable_name_badges) $mussFailed[] = 'B3';
        if (!$p->staff_informed_about_waste) $mussFailed[] = 'B4';
        if (!$p->participants_informed_about_waste) $mussFailed[] = 'B5';
        if (!$p->new_office_devices_energy_star) $mussFailed[] = 'B6';
        if (!$p->minimal_print_approach) $mussFailed[] = 'B7';
        if (!$p->paper_has_env_label) $mussFailed[] = 'B8';
        if (!$p->giveaways_restricted_no_batteries) $mussFailed[] = 'B9';

        // SOLL points
        if ($p->waste_separated_min_4) $points += 2.0;
        if ($p->waste_data_collected) $points += 1.0;
        if ($p->print_reduction_vs_last) $points += 1.5;
        if ($p->paperless_event) $points += 2.0;
        if ($p->bags_not_provided) $points += 2.5;
        if ($p->bags_from_recycled_material) $points += 1.0;
        if ($p->giveaways_not_provided) $points += 3.0;
        if ($p->giveaways_eco_certified) $points += 2.0;
        if ($p->no_decoration) $points += 1.5;
        if ($p->flowers_from_regional_nursery) $points += 1.0;
        if ($p->banners_reusable) $points += 1.5;
        if ($p->electricity_consumption_measured) $points += 1.0;
        if ($event->is_hybrid && $p->hybrid_energy_optimized) $points += 1.0;

        return ['muss_failed' => $mussFailed, 'points_achieved' => min($points, $maxPoints), 'points_max' => $maxPoints, 'criteria' => []];
    }

    private function evaluateExhibitors(Event $event): array
    {
        $e = $event->exhibitor;
        $mussFailed = [];
        $points = 0;

        if (!$e) return ['muss_failed' => [], 'points_achieved' => 0, 'points_max' => 11, 'criteria' => []];

        if (!$e->exhibitors_informed) $mussFailed[] = 'A1';
        if (!$e->stand_builders_informed) $mussFailed[] = 'A2';
        if (!$e->contracts_signed_with_exhibitors) $mussFailed[] = 'A3';

        if ($e->print_reduction_signed_50pct) $points += 1.5;
        if ($e->giveaway_reduction_signed_50pct) $points += 1.5;
        if ($e->stands_provided_by_organizer_reused) $points += 3.0;
        elseif ($e->exhibitor_stands_reused_100pct) $points += 3.0;
        elseif ($e->exhibitor_stands_reused_50pct) $points += 2.0;
        elseif ($e->exhibitor_stands_reused_25pct) $points += 1.5;
        elseif ($e->exhibitor_stands_reused_10pct) $points += 1.0;
        if ($e->stands_eco_materials_50pct) $points += 1.0;
        if ($e->stands_led_lighting_50pct) $points += 1.0;

        return ['muss_failed' => $mussFailed, 'points_achieved' => min($points, 11), 'points_max' => 11, 'criteria' => []];
    }

    private function evaluateCatering(Event $event): array
    {
        $c = $event->catering;
        $mussFailed = [];
        $points = 0;

        if (!$c) return ['muss_failed' => [], 'points_achieved' => 0, 'points_max' => 39.5, 'criteria' => []];

        // MUSS
        if (!$c->reusable_cups_and_dishes) $mussFailed[] = 'C2';
        if (!$c->drinks_in_bulk_containers) $mussFailed[] = 'C3';
        if (!$c->food_waste_eco_disposal) $mussFailed[] = 'C4';
        if (!$c->no_open_front_coolers) $mussFailed[] = 'C5';
        if (!$c->no_gas_patio_heaters) $mussFailed[] = 'C6';
        if (!$c->free_tap_water) $mussFailed[] = 'C7';
        if (!$c->two_seasonal_regional_ingredients) $mussFailed[] = 'C8';
        if (!$c->two_regional_drinks) $mussFailed[] = 'C9';
        if (!$c->one_bio_drink_and_ingredient) $mussFailed[] = 'C10';
        if (!$c->one_fair_trade_product) $mussFailed[] = 'C11';
        if (!$c->sustainable_seafood) $mussFailed[] = 'C12';
        if (!$c->no_endangered_species) $mussFailed[] = 'C13';
        if (!$c->free_range_eggs) $mussFailed[] = 'C14';
        if (!$c->vegetarian_option) $mussFailed[] = 'C15';
        if (!$c->staff_informed) $mussFailed[] = 'C16';
        if (!$c->quality_communicated_to_guests) $mussFailed[] = 'C17';

        // SOLL points
        if ($c->caterer_has_umweltzeichen) $points += 3.0;
        if ($c->bio_100pct) $points += 5.0;
        elseif ($c->bio_50pct_main_and_drinks) $points += 3.5;
        elseif ($c->bio_30pct_main_and_drinks) $points += 3.0;
        elseif ($c->bio_50pct_main_only) $points += 2.0;
        elseif ($c->bio_30pct_main_only) $points += 1.0;

        if ($c->vegan_full_menu) $points += 3.0;
        elseif ($c->vegetarian_full_menu) $points += 2.0;

        if ($c->fair_trade_coffee) $points += 1.0;
        if ($c->fair_trade_tea) $points += 0.5;
        if ($c->food_waste_calculated) $points += 1.0;
        if ($c->leftover_food_solution) $points += 1.0;
        if ($c->special_diet_allergies) $points += 0.5;
        if ($c->special_diet_religious) $points += 0.5;
        if ($c->no_still_mineral_water) $points += 1.0;
        if ($c->local_specialties_count) $points += min($c->local_specialties_count * 0.5, 2.0);

        // Food stands MUSS
        if ($c->food_stands_count > 0) {
            if (!$c->food_stands_briefed) $mussFailed[] = 'VK1';
            if (!$c->food_stands_contracted) $mussFailed[] = 'VK2';
            if (!$c->food_stands_veg_options_min2) $mussFailed[] = 'VK3';
            if ($c->food_stands_100pct_voluntary) $points += 6.0;
            elseif ($c->food_stands_50pct_voluntary) $points += 3.0;
        }

        return ['muss_failed' => $mussFailed, 'points_achieved' => min($points, 39.5), 'points_max' => 39.5, 'criteria' => []];
    }

    private function evaluateCommunication(Event $event): array
    {
        $c = $event->communication;
        $mussFailed = [];
        $points = 0;
        $maxPoints = $event->isMeeting() ? 3.5 : 4.5;

        if (!$c) return ['muss_failed' => ['K1', 'K2', 'K3', 'K4'], 'points_achieved' => 0, 'points_max' => $maxPoints, 'criteria' => []];

        if (!$c->internal_comm_done) $mussFailed[] = 'K1';
        if (!$c->external_comm_in_invitation && !$c->external_comm_on_website) $mussFailed[] = 'K2';
        if (!$c->green_contact_name) $mussFailed[] = 'K3';
        if (!$c->feedback_survey_done) $mussFailed[] = 'K4';

        if ($c->eco_accommodations_communicated) $points += 1.0;
        if ($c->kpis_collected && $c->kpis_used_for_improvement) $points += 2.5;
        if ($c->neighbours_informed) $points += 1.0;

        return ['muss_failed' => $mussFailed, 'points_achieved' => min($points, $maxPoints), 'points_max' => $maxPoints, 'criteria' => []];
    }

    private function evaluateSocial(Event $event): array
    {
        $s = $event->social;
        $mussFailed = [];
        $points = 0;
        $maxPoints = $event->isSportEvent() ? 15 : ($event->is_hybrid ? 16 : 13);

        if (!$s) return ['muss_failed' => ['S2'], 'points_achieved' => 0, 'points_max' => $maxPoints, 'criteria' => []];

        if (($event->expected_participants ?? 0) >= 1000 && !$s->security_concept_exists) $mussFailed[] = 'S1';
        if (!$s->accessibility_statement_created) $mussFailed[] = 'S2';

        if ($s->security_company_certified) $points += 1.0;
        if ($s->disability_contact_in_invitation) $points += 0.5;
        if ($s->disability_need_asked_at_registration) $points += 0.5;
        if ($s->accessible_accommodation_offered) $points += 0.5;
        if ($s->assistant_free_entry) $points += 0.5;
        if ($s->gender_neutral_language) $points += 0.5;
        if ($s->balanced_gender_speakers) $points += 0.5;
        if ($s->family_offers_available) $points += 1.5;
        if ($s->website_wcag_aaa) $points += 1.5;
        elseif ($s->website_wcag_aa) $points += 1.0;
        if ($s->regional_culture_nature_program) $points += 2.0;
        if ($s->social_initiatives_supported) $points += 2.0;
        if ($s->youth_protection_measures) $points += 1.0;
        if ($s->no_happy_hour && $s->non_alcoholic_cheaper) $points += 1.0;
        if ($event->isSportEvent() && $s->fair_play_communicated) $points += 1.0;

        return ['muss_failed' => $mussFailed, 'points_achieved' => min($points, $maxPoints), 'points_max' => $maxPoints, 'criteria' => []];
    }

    private function evaluateTechnology(Event $event): array
    {
        $t = $event->technology;
        $mussFailed = [];
        $points = 0;

        if (!$t) return ['muss_failed' => ['T1', 'T2', 'T3'], 'points_achieved' => 0, 'points_max' => 4, 'criteria' => []];

        if (!$t->hearing_protection_provided) $mussFailed[] = 'T1';
        if (!$t->no_outdoor_confetti_fireworks || !$t->no_sky_beamers) $mussFailed[] = 'T2';
        if (!$t->existing_venue_tech_used_first) $mussFailed[] = 'T3';

        if ($t->led_spotlights_100pct) $points += 1.0;
        elseif ($t->led_spotlights_50pct) $points += 0.5;
        if ($t->wired_internet_used) $points += 0.5;
        if ($t->tech_appropriately_sized) $points += 0.5;
        if ($t->battery_backup_instead_of_diesel) $points += 2.0;

        return ['muss_failed' => $mussFailed, 'points_achieved' => min($points, 4), 'points_max' => 4, 'criteria' => []];
    }
}
