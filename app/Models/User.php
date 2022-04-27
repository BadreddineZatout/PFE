<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    public function fullname()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

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
        return $this->role_id == Role::where('name', 'admin')->first()->id;
    }

    public function isMinister()
    {
        return $this->role_id == Role::where('name', 'minister')->first()->id;
    }

    public function isDecider()
    {
        return $this->role_id == Role::where('name', 'decider')->first()->id;
    }

    public function isUniversityDecider()
    {
        return $this->isDecider() && Establishment::findOrFail($this->establishment_id)->isUniversity();
    }

    public function isResidenceDecider()
    {
        return $this->isDecider() && Establishment::findOrFail($this->establishment_id)->isResidence();
    }

    public function isAgentRestauration()
    {
        return $this->role_id == Role::where('name', 'agent restauration')->first()->id;
    }

    public function isAgentHebergement()
    {
        return $this->role_id == Role::where('name', 'agent hebergement')->first()->id;
    }

    public function isAgentTransport()
    {
        return $this->role_id == Role::where('name', 'agent transport')->first()->id;
    }
}
