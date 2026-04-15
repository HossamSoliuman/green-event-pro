<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'country',
        'vat_number',
        'website',
        'logo_path',
        'subscription_plan',
        'subscription_status',
        'subscription_ends_at',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
    ];

    protected $casts = [
        'subscription_ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function getPlanLimits(): array
    {
        return match ($this->subscription_plan) {
            'starter' => ['events_per_year' => 5, 'users' => 1, 'api_access' => false, 'white_label_pdf' => false],
            'professional' => ['events_per_year' => 25, 'users' => 5, 'api_access' => true, 'white_label_pdf' => false],
            'enterprise' => ['events_per_year' => PHP_INT_MAX, 'users' => 20, 'api_access' => true, 'white_label_pdf' => true],
            'agency' => ['events_per_year' => PHP_INT_MAX, 'users' => PHP_INT_MAX, 'api_access' => true, 'white_label_pdf' => true],
            default => ['events_per_year' => 2, 'users' => 1, 'api_access' => false, 'white_label_pdf' => false],
        };
    }

    public function isSubscriptionActive(): bool
    {
        return in_array($this->subscription_status, ['active', 'trialing']);
    }
}
