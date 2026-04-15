<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_caterings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('catering_type')->default('external_caterer');
            $table->string('catering_company_name')->nullable();
            $table->boolean('caterer_has_umweltzeichen')->default(false);
            $table->boolean('caterer_is_100pct_vegan_bio')->default(false);
            $table->boolean('caterer_has_bio_certification')->default(false);
            $table->boolean('reusable_cups_and_dishes')->default(false);
            $table->boolean('reusable_tablecloths')->default(false);
            $table->boolean('drinks_in_bulk_containers')->default(false);
            $table->boolean('no_pod_coffee_machines')->default(false);
            $table->boolean('food_waste_eco_disposal')->default(false);
            $table->boolean('no_open_front_coolers')->default(false);
            $table->boolean('no_gas_patio_heaters')->default(false);
            $table->boolean('free_tap_water')->default(false);
            $table->boolean('two_seasonal_regional_ingredients')->default(false);
            $table->boolean('two_regional_drinks')->default(false);
            $table->boolean('one_bio_drink_and_ingredient')->default(false);
            $table->boolean('one_fair_trade_product')->default(false);
            $table->boolean('sustainable_seafood')->default(false);
            $table->boolean('no_endangered_species')->default(false);
            $table->boolean('free_range_eggs')->default(false);
            $table->boolean('vegetarian_option')->default(false);
            $table->boolean('staff_informed')->default(false);
            $table->boolean('quality_communicated_to_guests')->default(false);
            $table->boolean('origin_labeled_on_menu')->default(false);
            $table->boolean('bio_100pct')->default(false);
            $table->boolean('bio_50pct_main_and_drinks')->default(false);
            $table->boolean('bio_30pct_main_and_drinks')->default(false);
            $table->boolean('bio_50pct_main_only')->default(false);
            $table->boolean('bio_30pct_main_only')->default(false);
            $table->boolean('eco_cleaning_dishwash')->default(false);
            $table->boolean('food_waste_calculated')->default(false);
            $table->boolean('leftover_food_solution')->default(false);
            $table->string('leftover_solution_description')->nullable();
            $table->boolean('special_diet_allergies')->default(false);
            $table->boolean('special_diet_religious')->default(false);
            $table->boolean('vegetarian_full_menu')->default(false);
            $table->boolean('vegan_full_menu')->default(false);
            $table->boolean('no_still_mineral_water')->default(false);
            $table->integer('local_specialties_count')->default(0);
            $table->boolean('regional_typical_dishes')->default(false);
            $table->boolean('fair_trade_coffee')->default(false);
            $table->boolean('fair_trade_tea')->default(false);
            $table->boolean('fair_trade_cocoa')->default(false);
            $table->boolean('fair_trade_chocolate')->default(false);
            $table->integer('food_stands_count')->default(0);
            $table->boolean('food_stands_briefed')->default(false);
            $table->boolean('food_stands_contracted')->default(false);
            $table->boolean('food_stands_veg_options_min2')->default(false);
            $table->boolean('food_stands_50pct_voluntary')->default(false);
            $table->boolean('food_stands_100pct_voluntary')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_caterings'); }
};
