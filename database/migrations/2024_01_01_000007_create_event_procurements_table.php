<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_procurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->boolean('has_waste_concept')->default(false);
            $table->boolean('reusable_name_badges')->default(false);
            $table->boolean('staff_informed_about_waste')->default(false);
            $table->boolean('participants_informed_about_waste')->default(false);
            $table->boolean('new_office_devices_energy_star')->default(false);
            $table->boolean('minimal_print_approach')->default(false);
            $table->boolean('paper_has_env_label')->default(false);
            $table->boolean('giveaways_restricted_no_batteries')->default(false);
            $table->boolean('waste_separated_min_4')->default(false);
            $table->boolean('waste_data_collected')->default(false);
            $table->boolean('print_reduction_vs_last')->default(false);
            $table->boolean('paperless_event')->default(false);
            $table->boolean('bags_not_provided')->default(false);
            $table->boolean('bags_from_recycled_material')->default(false);
            $table->boolean('bags_from_social_workshop')->default(false);
            $table->boolean('giveaways_not_provided')->default(false);
            $table->boolean('giveaways_eco_certified')->default(false);
            $table->boolean('giveaways_50pct_eco')->default(false);
            $table->boolean('sponsor_giveaways_not_provided')->default(false);
            $table->boolean('sponsor_giveaways_eco_certified')->default(false);
            $table->boolean('no_decoration')->default(false);
            $table->boolean('decoration_natural_materials')->default(false);
            $table->boolean('decoration_reusable')->default(false);
            $table->boolean('flowers_from_regional_nursery')->default(false);
            $table->boolean('uses_rental_plants')->default(false);
            $table->boolean('banners_reusable')->default(false);
            $table->boolean('banners_recycled')->default(false);
            $table->integer('partner_companies_env_certified')->default(0);
            $table->boolean('electricity_consumption_measured')->default(false);
            $table->boolean('hybrid_energy_optimized')->default(false);
            $table->text('hybrid_energy_measures')->nullable();
            $table->boolean('self_service_reusable_cups')->default(false);
            $table->boolean('self_service_reusable_dishes')->default(false);
            $table->boolean('self_service_tap_water_free')->default(false);
            $table->boolean('sustainable_trophies')->default(false);
            $table->boolean('finisher_medals_sustainable')->default(false);
            $table->boolean('fair_sportswear')->default(false);
            $table->boolean('reusable_race_numbers')->default(false);
            $table->boolean('reusable_timing_chips')->default(false);
            $table->boolean('stage_reuse_materials')->default(false);
            $table->boolean('makeup_eco_certified')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_procurements'); }
};
