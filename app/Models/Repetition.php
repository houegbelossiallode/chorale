<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repetition extends Model
{
    protected $fillable = ['titre', 'description', 'lieu', 'start_time', 'end_time'];

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function chants()
    {
        return $this->belongsToMany(Chant::class, 'chant_repetitions');
    }
}
