<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_uz62_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->timestamp('calculated_at');
            $table->string('event_type');
            $table->boolean('muss_passed')->default(false);
            $table->json('muss_failed_criteria')->nullable();
            $table->decimal('points_achieved', 8, 2)->default(0);
            $table->decimal('points_max', 8, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->boolean('passed')->default(false);
            $table->json('section_scores')->nullable();
            $table->boolean('certification_eligible')->default(false);
            $table->string('certification_phase')->default('none');
            $table->timestamp('report_generated_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_uz62_scores'); }
};
