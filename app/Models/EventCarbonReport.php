<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCarbonReport extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'calculated_at' => 'datetime',
        'report_generated_at' => 'datetime',
        'is_carbon_neutral' => 'boolean',
        'co2_participant_travel' => 'decimal:2',
        'co2_staff_travel' => 'decimal:2',
        'co2_tv_crew_travel' => 'decimal:2',
        'co2_equipment_transport' => 'decimal:2',
        'co2_venue_energy' => 'decimal:2',
        'co2_catering' => 'decimal:2',
        'co2_accommodation' => 'decimal:2',
        'co2_tv_production_power' => 'decimal:2',
        'co2_other' => 'decimal:2',
        'co2_total' => 'decimal:2',
        'co2_per_person' => 'decimal:2',
        'co2_compensation' => 'decimal:2',
        'co2_net' => 'decimal:2',
    ];

    public function event(): BelongsTo { return $this->belongsTo(Event::class); }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
}
