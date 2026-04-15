<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_socials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->boolean('security_concept_exists')->default(false);
            $table->boolean('security_staff_trained')->default(false);
            $table->boolean('accessibility_statement_created')->default(false);
            $table->string('accessibility_statement_url')->nullable();
            $table->boolean('accessibility_covers_mobility')->default(false);
            $table->boolean('accessibility_covers_hearing')->default(false);
            $table->boolean('accessibility_covers_vision')->default(false);
            $table->boolean('security_company_certified')->default(false);
            $table->string('security_company_name')->nullable();
            $table->boolean('disability_contact_in_invitation')->default(false);
            $table->boolean('disability_need_asked_at_registration')->default(false);
            $table->boolean('accessible_accommodation_offered')->default(false);
            $table->boolean('info_systems_barrier_free')->default(false);
            $table->boolean('assistant_free_entry')->default(false);
            $table->boolean('gender_neutral_language')->default(false);
            $table->boolean('balanced_gender_speakers')->default(false);
            $table->boolean('diversity_aspects_considered')->default(false);
            $table->boolean('family_offers_available')->default(false);
            $table->boolean('senior_offers')->default(false);
            $table->boolean('student_discounts')->default(false);
            $table->boolean('website_wcag_aa')->default(false);
            $table->boolean('website_wcag_aaa')->default(false);
            $table->boolean('regional_culture_nature_program')->default(false);
            $table->text('program_description')->nullable();
            $table->boolean('social_initiatives_supported')->default(false);
            $table->text('social_initiative_description')->nullable();
            $table->boolean('youth_protection_measures')->default(false);
            $table->boolean('no_happy_hour')->default(false);
            $table->boolean('non_alcoholic_cheaper')->default(false);
            $table->boolean('hybrid_features_precommunicated')->default(false);
            $table->boolean('sign_language_online')->default(false);
            $table->boolean('fair_play_communicated')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_socials'); }
};
