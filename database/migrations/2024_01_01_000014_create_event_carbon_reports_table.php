<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_carbon_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->timestamp('calculated_at');
            $table->integer('participants_count')->default(0);
            $table->decimal('co2_participant_travel', 12, 2)->default(0);
            $table->decimal('co2_staff_travel', 12, 2)->default(0);
            $table->decimal('co2_tv_crew_travel', 12, 2)->default(0);
            $table->decimal('co2_equipment_transport', 12, 2)->default(0);
            $table->decimal('co2_venue_energy', 12, 2)->default(0);
            $table->decimal('co2_catering', 12, 2)->default(0);
            $table->decimal('co2_accommodation', 12, 2)->default(0);
            $table->decimal('co2_tv_production_power', 12, 2)->default(0);
            $table->decimal('co2_other', 12, 2)->default(0);
            $table->decimal('co2_total', 12, 2)->default(0);
            $table->decimal('co2_per_person', 10, 2)->default(0);
            $table->decimal('co2_compensation', 12, 2)->default(0);
            $table->decimal('co2_net', 12, 2)->default(0);
            $table->decimal('benchmark_avg_event_co2_per_person', 10, 2)->default(100);
            $table->decimal('benchmark_green_event_co2_per_person', 10, 2)->default(30);
            $table->boolean('is_carbon_neutral')->default(false);
            $table->timestamp('report_generated_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_carbon_reports'); }
};
