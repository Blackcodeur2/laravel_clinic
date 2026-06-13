<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nom', 'prenom', 'username', 'email', 'password', 'role_id', 'photo_profile', 'is_active'])]

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the role of the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get consultations managed by this user (as a doctor).
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class, 'medecin_id');
    }

    /**
     * Check if user is an Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->nom === 'ADMIN';
    }

    /**
     * Check if user is a Responsable (e.g. Doctor).
     */
    public function isResponsable(): bool
    {
        return $this->role && $this->role->nom === 'RESPONSABLE';
    }

    /**
     * Check if user is a Caissier (Reception).
     */
    public function isCaissier(): bool
    {
        return $this->role && $this->role->nom === 'CAISSIER';
    }
}
