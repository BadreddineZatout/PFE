<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Actions\Actionable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Actionable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $with = [
        'wilaya',
        'role',
        'establishment'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date'
    ];


    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin()
    {
        return $this->role_id == Role::ADMIN;
    }

    public function isMinister()
    {
        return $this->role_id == Role::MINISTER;
    }

    public function isDecider()
    {
        return $this->role_id == Role::DECIDER;
    }

    public function isUniversityDecider()
    {
        return $this->isDecider() && $this->establishment?->isUniversity();
    }

    public function isResidenceDecider()
    {
        return $this->isDecider() && $this->establishment?->isResidence();
    }

    public function isAgentRestauration()
    {
        return $this->role_id == Role::AGENT_RESTAURATION;
    }

    public function isAgentHebergement()
    {
        return $this->role_id == Role::AGENT_HEBERGEMENT;
    }

    public function isAgentTransport()
    {
        return $this->role_id == Role::AGENT_TRANSPORT;
    }
}
