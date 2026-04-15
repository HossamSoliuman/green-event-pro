<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('hotel_name');
            $table->string('hotel_address')->nullable();
            $table->string('hotel_city')->nullable();
            $table->string('hotel_country', 2)->default('AT');
            $table->string('hotel_website')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->boolean('has_env_certification')->default(false);
            $table->string('certification_type')->nullable();
            $table->string('certification_name')->nullable();
            $table->date('certification_valid_until')->nullable();
            $table->boolean('has_secondary_certification')->default(false);
            $table->string('secondary_cert_type')->nullable();
            $table->boolean('self_declaration_completed')->default(false);
            $table->integer('self_declaration_points')->default(0);
            $table->decimal('distance_to_venue_km', 8, 2)->nullable();
            $table->boolean('accessible_by_foot')->default(false);
            $table->boolean('accessible_by_bike')->default(false);
            $table->boolean('accessible_by_public_transport')->default(false);
            $table->text('transport_description')->nullable();
            $table->boolean('hotel_informed_of_green_event')->default(false);
            $table->boolean('hotel_invited_for_env_certification')->default(false);
            $table->integer('contingent_reserved')->default(0);
            $table->integer('nights_reserved')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_accommodations'); }
};
