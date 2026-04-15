<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('event_exhibitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->integer('total_exhibitors')->default(0);
            $table->boolean('exhibitors_informed')->default(false);
            $table->boolean('stand_builders_informed')->default(false);
            $table->boolean('contracts_signed_with_exhibitors')->default(false);
            $table->boolean('waste_separation_agreed')->default(false);
            $table->boolean('no_single_use_agreed')->default(false);
            $table->boolean('print_reduction_signed_50pct')->default(false);
            $table->integer('exhibitors_signed_print_reduction')->default(0);
            $table->boolean('giveaway_reduction_signed_50pct')->default(false);
            $table->integer('exhibitors_signed_giveaway_reduction')->default(0);
            $table->boolean('stands_provided_by_organizer_reused')->default(false);
            $table->boolean('exhibitor_stands_reused_10pct')->default(false);
            $table->boolean('exhibitor_stands_reused_25pct')->default(false);
            $table->boolean('exhibitor_stands_reused_50pct')->default(false);
            $table->boolean('exhibitor_stands_reused_100pct')->default(false);
            $table->integer('exhibitors_using_reused_stands')->default(0);
            $table->boolean('stands_eco_materials_50pct')->default(false);
            $table->boolean('stands_led_lighting_50pct')->default(false);
            $table->boolean('stands_uz75_10pct')->default(false);
            $table->boolean('stands_uz75_30pct')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('event_exhibitors'); }
};
