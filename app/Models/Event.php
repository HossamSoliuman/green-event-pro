<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'type',
        'description',
        'start_date',
        'end_date',
        'expected_participants',
        'venue_name',
        'venue_address',
        'venue_city',
        'venue_country',
        'venue_lat',
        'venue_lng',
        'is_outdoor',
        'is_hybrid',
        'status',
        'uz62_score',
        'uz62_score_max',
        'uz62_percentage',
        'uz62_passed',
        'carbon_footprint_kg_co2',
        'carbon_footprint_per_person',
        'certification_phase',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_outdoor' => 'boolean',
        'is_hybrid' => 'boolean',
        'uz62_passed' => 'boolean',
        'uz62_score' => 'decimal:2',
        'uz62_score_max' => 'decimal:2',
        'uz62_percentage' => 'decimal:2',
        'carbon_footprint_kg_co2' => 'decimal:2',
        'carbon_footprint_per_person' => 'decimal:2',
        'venue_lat' => 'decimal:6',
        'venue_lng' => 'decimal:6',
    ];

    const TYPES = [
        'meeting_congress' => 'Kongress/Tagung',
        'meeting_company' => 'Firmentagung',
        'meeting_seminar' => 'Seminar/Workshop',
        'meeting_b2b_fair' => 'B2B Fachmesse',
        'event_culture' => 'Kulturevent',
        'event_sport' => 'Sportveranstaltung',
        'event_theater' => 'Theaterfestival',
        'event_outdoor' => 'Open-Air Event',
        'hybrid' => 'Hybride Veranstaltung',
    ];

    const STATUSES = ['draft', 'active', 'completed', 'certified'];
    const CERTIFICATION_PHASES = ['none', 'aspiring', 'phase1', 'phase2', 'certified'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function mobility(): HasOne
    {
        return $this->hasOne(EventMobility::class);
    }

    public function accommodations(): HasMany
    {
        return $this->hasMany(EventAccommodation::class);
    }

    public function venue(): HasOne
    {
        return $this->hasOne(EventVenue::class);
    }

    public function procurement(): HasOne
    {
        return $this->hasOne(EventProcurement::class);
    }

    public function exhibitor(): HasOne
    {
        return $this->hasOne(EventExhibitor::class);
    }

    public function catering(): HasOne
    {
        return $this->hasOne(EventCatering::class);
    }

    public function communication(): HasOne
    {
        return $this->hasOne(EventCommunication::class);
    }

    public function social(): HasOne
    {
        return $this->hasOne(EventSocial::class);
    }

    public function technology(): HasOne
    {
        return $this->hasOne(EventTechnology::class);
    }

    public function tvProduction(): HasOne
    {
        return $this->hasOne(EventTvProduction::class);
    }

    public function carbonReports(): HasMany
    {
        return $this->hasMany(EventCarbonReport::class);
    }

    public function latestCarbonReport(): HasOne
    {
        return $this->hasOne(EventCarbonReport::class)->latestOfMany();
    }

    public function uz62Scores(): HasMany
    {
        return $this->hasMany(EventUz62Score::class);
    }

    public function latestUz62Score(): HasOne
    {
        return $this->hasOne(EventUz62Score::class)->latestOfMany();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EventDocument::class);
    }

    public function wasteData(): HasMany
    {
        return $this->hasMany(EventWasteData::class);
    }

    public function isMeeting(): bool
    {
        return str_starts_with($this->type, 'meeting_');
    }

    public function isSportEvent(): bool
    {
        return $this->type === 'event_sport';
    }

    public function isTheaterEvent(): bool
    {
        return $this->type === 'event_theater';
    }

    public function getDurationDays(): int
    {
        if (!$this->start_date || !$this->end_date) return 1;
        return max(1, $this->start_date->diffInDays($this->end_date) + 1);
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'draft' => 'badge-gray',
            'active' => 'badge-blue',
            'completed' => 'badge-yellow',
            'certified' => 'badge-green',
            default => 'badge-gray',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
