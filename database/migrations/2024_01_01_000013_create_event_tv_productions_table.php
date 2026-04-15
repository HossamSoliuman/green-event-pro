<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_tv_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->boolean('has_tv_production')->default(false);
            $table->string('production_company_name')->nullable();
            $table->string('production_company_country')->nullable();
            $table->integer('crew_total_persons')->default(0);
            $table->integer('crew_travel_car_persons')->default(0);
            $table->decimal('crew_travel_car_km_avg', 8, 2)->default(0);
            $table->integer('crew_travel_plane_persons')->default(0);
            $table->decimal('crew_travel_plane_km_avg', 8, 2)->default(0);
            $table->integer('crew_travel_train_persons')->default(0);
            $table->decimal('crew_travel_train_km_avg', 8, 2)->default(0);
            $table->integer('equipment_transport_trucks')->default(0);
            $table->decimal('equipment_transport_km', 8, 2)->default(0);
            $table->string('equipment_transport_fuel_type')->default('diesel');
            $table->decimal('equipment_total_weight_kg', 10, 2)->nullable();
            $table->decimal('power_consumed_kwh', 10, 2)->nullable();
            $table->string('power_source_type')->default('public_grid');
            $table->decimal('generator_fuel_liters', 10, 2)->nullable();
            $table->string('generator_fuel_type')->default('diesel');
            $table->integer('crew_hotel_nights')->default(0);
            $table->string('crew_hotel_name')->nullable();
            $table->boolean('crew_hotel_env_certified')->default(false);
            $table->boolean('equipment_reused_from_previous_event')->default(false);
            $table->decimal('equipment_reuse_percentage', 5, 2)->default(0);
            $table->decimal('led_studio_lights_percentage', 5, 2)->default(0);
            $table->boolean('uses_remote_production')->default(false);
            $table->text('remote_production_description')->nullable();
            $table->decimal('travel_co2_kg', 10, 2)->nullable();
            $table->decimal('equipment_transport_co2_kg', 10, 2)->nullable();
            $table->decimal('power_co2_kg', 10, 2)->nullable();
            $table->decimal('total_co2_kg', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_tv_productions'); }
};
