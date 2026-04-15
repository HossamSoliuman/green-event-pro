<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_technologies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->decimal('expected_db_level', 5, 1)->nullable();
            $table->boolean('hearing_protection_provided')->default(false);
            $table->boolean('guests_warned_about_noise')->default(false);
            $table->boolean('no_outdoor_confetti_fireworks')->default(false);
            $table->boolean('no_sky_beamers')->default(false);
            $table->boolean('eco_flame_effects_only')->default(false);
            $table->boolean('existing_venue_tech_used_first')->default(false);
            $table->boolean('regional_tech_company_hired')->default(false);
            $table->string('tech_company_name')->nullable();
            $table->decimal('tech_company_distance_km', 8, 2)->nullable();
            $table->boolean('led_spotlights_50pct')->default(false);
            $table->boolean('led_spotlights_100pct')->default(false);
            $table->boolean('wired_internet_used')->default(false);
            $table->integer('energy_efficient_devices_count')->default(0);
            $table->boolean('tech_appropriately_sized')->default(false);
            $table->boolean('battery_backup_instead_of_diesel')->default(false);
            $table->decimal('power_consumption_kwh', 10, 2)->nullable();
            $table->string('power_source')->nullable();
            $table->boolean('generator_has_particle_filter')->default(false);
            $table->date('generator_maintenance_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_technologies'); }
};
