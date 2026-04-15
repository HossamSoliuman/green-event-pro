<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('type');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('expected_participants')->default(0);
            $table->string('venue_name');
            $table->string('venue_address')->nullable();
            $table->string('venue_city');
            $table->string('venue_country', 2)->default('AT');
            $table->decimal('venue_lat', 10, 6)->nullable();
            $table->decimal('venue_lng', 10, 6)->nullable();
            $table->boolean('is_outdoor')->default(false);
            $table->boolean('is_hybrid')->default(false);
            $table->string('status')->default('draft');
            $table->decimal('uz62_score', 8, 2)->nullable();
            $table->decimal('uz62_score_max', 8, 2)->nullable();
            $table->decimal('uz62_percentage', 5, 2)->nullable();
            $table->boolean('uz62_passed')->nullable();
            $table->decimal('carbon_footprint_kg_co2', 12, 2)->nullable();
            $table->decimal('carbon_footprint_per_person', 10, 2)->nullable();
            $table->string('certification_phase')->default('none');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('events'); }
};
