<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'password',
        'role',
        'locale',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    const ROLES = ['owner', 'admin', 'editor', 'viewer'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isAdmin(): bool { return in_array($this->role, ['owner', 'admin']); }
    public function isEditor(): bool { return in_array($this->role, ['owner', 'admin', 'editor']); }
    public function isViewer(): bool { return true; }

    public function canEdit(): bool
    {
        return $this->isEditor();
    }

    public function canManageUsers(): bool
    {
        return $this->isOwner();
    }

    public function canManageBilling(): bool
    {
        return $this->isOwner();
    }
}
