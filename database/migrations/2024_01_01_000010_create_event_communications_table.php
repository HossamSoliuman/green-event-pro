<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_communications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->boolean('internal_comm_done')->default(false);
            $table->date('internal_comm_date')->nullable();
            $table->string('internal_comm_method')->nullable();
            $table->boolean('external_comm_in_invitation')->default(false);
            $table->boolean('external_comm_on_website')->default(false);
            $table->boolean('external_comm_on_program')->default(false);
            $table->boolean('external_comm_at_event')->default(false);
            $table->boolean('participants_motivated_to_participate')->default(false);
            $table->string('green_contact_name')->nullable();
            $table->string('green_contact_email')->nullable();
            $table->string('green_contact_phone')->nullable();
            $table->boolean('contact_publicly_communicated')->default(false);
            $table->boolean('feedback_survey_done')->default(false);
            $table->string('feedback_method')->nullable();
            $table->boolean('feedback_results_documented')->default(false);
            $table->boolean('eco_accommodations_communicated')->default(false);
            $table->boolean('green_transport_to_hotel_communicated')->default(false);
            $table->boolean('kpis_collected')->default(false);
            $table->boolean('kpis_used_for_improvement')->default(false);
            $table->text('kpi_description')->nullable();
            $table->boolean('neighbours_informed')->default(false);
            $table->string('certification_phase')->default('none');
            $table->boolean('phase1_signed_agreement')->default(false);
            $table->date('phase1_date')->nullable();
            $table->date('phase2_certified_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_communications'); }
};
