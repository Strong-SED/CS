<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Rôles disponibles
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN_CS = 'admin_cs';
    public const ROLE_MEDECIN = 'medecin';
    public const ROLE_SECRETAIRE = 'secretaire';
    public const ROLE_LABORANTIN = 'laborantin';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'specialite',
        'email_verified_at',
        'email_verification_token',
        'centre_de_sante_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Scopes pour chaque rôle
    public function scopeSuperAdmins(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_SUPER_ADMIN);
    }

    public function scopeAdminCS(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_ADMIN_CS);
    }

    public function scopeMedecins(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_MEDECIN);
    }

    public function scopeSecretaires(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_SECRETAIRE);
    }

    public function scopeLaborantins(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_LABORANTIN);
    }

    // Méthodes de vérification de rôle
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdminCS(): bool
    {
        return $this->role === self::ROLE_ADMIN_CS;
    }

    public function isMedecin(): bool
    {
        return $this->role === self::ROLE_MEDECIN;
    }

    public function isSecretaire(): bool
    {
        return $this->role === self::ROLE_SECRETAIRE;
    }

    public function isLaborantin(): bool
    {
        return $this->role === self::ROLE_LABORANTIN;
    }

    public function centreDeSante()
    {
        return $this->belongsTo(CentreDeSante::class);
    }
}
