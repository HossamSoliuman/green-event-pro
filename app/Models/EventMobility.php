<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventMobility extends Model
{
    protected $fillable = [
        'event_id', 'organization_id',
        'venue_accessible_by_foot', 'venue_accessible_by_bike', 'venue_accessible_by_public_transport',
        'venue_distance_from_int_airport_km', 'venue_distance_from_int_station_km',
        'public_transport_max_travel_time_min', 'shuttle_service_provided', 'shuttle_service_description',
        'bike_parking_spaces', 'bike_parking_secured', 'ebike_charging_stations',
        'bike_rental_available', 'bike_repair_station', 'bike_route_communicated',
        'incentive_type', 'incentive_description', 'discount_percentage',
        'ticket_cooperation_with_transport', 'transport_ticket_booked_for_participants',
        'carpooling_organized', 'group_travel_organized', 'team_group_travel_organized',
        'public_transport_frequency_increased',
        'onsite_transport_ticket_included', 'onsite_transport_ticket_purchasable',
        'onsite_bikes_available_free', 'onsite_bikes_staff_free', 'onsite_bike_rental_paid',
        'onsite_eco_taxi_available', 'onsite_multiple_venues_walk_distance', 'onsite_shuttle_between_venues',
        'material_transport_climate_friendly',
        'modal_split_surveyed', 'modal_split_car_percent', 'modal_split_public_transport_percent',
        'modal_split_bike_percent', 'modal_split_flight_percent', 'modal_split_other_percent',
        'ghg_compensation_communicated', 'ghg_calculation_done', 'ghg_total_kg_co2',
        'ghg_mobility_kg_co2', 'ghg_compensation_done', 'ghg_compensation_scope',
        'ghg_compensation_provider', 'ghg_compensation_amount_kg',
        'transport_company_name', 'transport_company_euro6_compliant', 'transport_company_has_env_policy',
        'transport_company_is_regional', 'transport_company_alternative_drive',
        'event_times_aligned_to_transport', 'event_start_time', 'event_end_time',
        'hybrid_replaces_flights', 'hybrid_speakers_remote', 'hybrid_participants_motivated_online',
        'notes',
    ];

    protected $casts = [
        'venue_accessible_by_foot' => 'boolean', 'venue_accessible_by_bike' => 'boolean',
        'venue_accessible_by_public_transport' => 'boolean', 'shuttle_service_provided' => 'boolean',
        'bike_parking_secured' => 'boolean', 'bike_rental_available' => 'boolean',
        'bike_repair_station' => 'boolean', 'bike_route_communicated' => 'boolean',
        'ticket_cooperation_with_transport' => 'boolean', 'transport_ticket_booked_for_participants' => 'boolean',
        'carpooling_organized' => 'boolean', 'group_travel_organized' => 'boolean',
        'modal_split_surveyed' => 'boolean', 'ghg_compensation_communicated' => 'boolean',
        'ghg_calculation_done' => 'boolean', 'ghg_compensation_done' => 'boolean',
        'transport_company_euro6_compliant' => 'boolean', 'transport_company_has_env_policy' => 'boolean',
        'transport_company_is_regional' => 'boolean', 'event_times_aligned_to_transport' => 'boolean',
        'hybrid_replaces_flights' => 'boolean', 'hybrid_participants_motivated_online' => 'boolean',
        'material_transport_climate_friendly' => 'boolean',
    ];

    public function event(): BelongsTo { return $this->belongsTo(Event::class); }
}
