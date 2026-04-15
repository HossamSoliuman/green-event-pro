<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_venues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('venue_type')->default('permanent_building');
            $table->boolean('has_env_certification')->default(false);
            $table->string('env_cert_type')->nullable();
            $table->date('env_cert_valid_until')->nullable();
            $table->boolean('klimaaktiv_silver')->default(false);
            $table->boolean('dgnb_platin')->default(false);
            $table->boolean('has_waste_management_concept')->default(false);
            $table->boolean('toilet_waste_bins_present')->default(false);
            $table->boolean('has_sustainability_concept')->default(false);
            $table->boolean('has_energy_bookkeeping')->default(false);
            $table->boolean('co2_calculation_done')->default(false);
            $table->boolean('has_accessibility_statement')->default(false);
            $table->boolean('meets_oenorm_b1600')->default(false);
            $table->boolean('website_accessible_wcag')->default(false);
            $table->boolean('has_energy_certificate')->default(false);
            $table->boolean('energy_optimization_program')->default(false);
            $table->decimal('led_percentage', 5, 2)->default(0);
            $table->boolean('uses_motion_sensors_timers')->default(false);
            $table->boolean('renewable_electricity_100pct')->default(false);
            $table->boolean('umweltzeichen_green_electricity')->default(false);
            $table->boolean('own_renewable_energy_system')->default(false);
            $table->boolean('renewable_heating_cooling_50pct')->default(false);
            $table->boolean('renewable_heating_cooling_100pct')->default(false);
            $table->boolean('toilet_flush_stop_button')->default(false);
            $table->boolean('waterless_urinals')->default(false);
            $table->boolean('tap_flow_under_9l')->default(false);
            $table->boolean('tap_auto_control')->default(false);
            $table->boolean('rainwater_reuse')->default(false);
            $table->boolean('uses_eco_cleaning_products')->default(false);
            $table->boolean('has_mobility_concept')->default(false);
            $table->boolean('ev_charging_stations_renewable')->default(false);
            $table->boolean('bike_parking_available')->default(false);
            // Outdoor
            $table->boolean('not_in_protected_area')->default(true);
            $table->boolean('has_nature_protection_concept')->default(false);
            $table->boolean('temporary_buildings_fully_dismantled')->default(false);
            $table->boolean('power_from_public_grid')->default(false);
            $table->boolean('no_gas_patio_heaters')->default(false);
            $table->boolean('wastewater_properly_disposed')->default(false);
            $table->boolean('mobile_toilets_adequate')->default(false);
            $table->boolean('eco_mobile_toilets')->default(false);
            $table->boolean('green_electricity_100pct')->default(false);
            $table->boolean('alternative_power_generation')->default(false);
            $table->string('alt_power_source_description')->nullable();
            $table->boolean('env_officer_on_site')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_venues'); }
};
