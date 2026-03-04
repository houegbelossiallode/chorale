<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repetition extends Model
{
    protected $fillable = ['titre', 'description', 'lieu', 'start_time', 'end_time', 'event_id'];

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function chants()
    {
        return $this->belongsToMany(Chant::class, 'chant_repetitions');
    }
}
