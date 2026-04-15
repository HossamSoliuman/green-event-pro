<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventUz62Score extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'calculated_at' => 'datetime',
        'report_generated_at' => 'datetime',
        'muss_passed' => 'boolean',
        'passed' => 'boolean',
        'certification_eligible' => 'boolean',
        'muss_failed_criteria' => 'array',
        'section_scores' => 'array',
        'points_achieved' => 'decimal:2',
        'points_max' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];

    public function event(): BelongsTo { return $this->belongsTo(Event::class); }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
}
