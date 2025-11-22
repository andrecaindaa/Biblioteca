<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Appended attributes
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Retorna iniciais do utilizador
     */
    public function initials()
    {
        $name = $this->name;
        $initials = '';

        $names = explode(' ', $name);
        foreach ($names as $n) {
            if (!empty(trim($n))) {
                $initials .= strtoupper($n[0]);
                if (strlen($initials) >= 2) break;
            }
        }

        return $initials ?: 'U';
    }

    public function requisicoes()
    {
        return $this->hasMany(Requisicao::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

   public function isAdmin(): bool
{
    // 1) Se role_id estiver definido e for 1 (convenção usada nas migrations)
    if ($this->role_id === 1) {
        return true;
    }

    // 2) fallback: se existir relação role, comparar o nome case-insensitive
    return (bool) ($this->role && strtolower($this->role->nome) === 'admin');
}

}
