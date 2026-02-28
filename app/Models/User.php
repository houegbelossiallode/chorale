<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

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
        ];
    }

    public function pupitre()
    {
        return $this->belongsTo(Pupitre::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Likes reçus par ce choriste
     */
    public function likesReceived()
    {
        return $this->hasMany(Like::class, 'choriste_id');
    }

    /**
     * Nombre total de likes
     */
    public function getLikesCountAttribute()
    {
        return $this->likesReceived()->count();
    }

    /**
     * Enregistrements personnels de ce choriste
     */
    public function enregistrements()
    {
        return $this->hasMany(Enregistrement::class);
    }
}
