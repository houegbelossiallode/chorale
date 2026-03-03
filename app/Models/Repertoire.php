<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repertoire extends Model
{
    protected $table = 'repertoire';
    protected $fillable = ['event_id', 'chant_id', 'partie_event_id', 'ordre'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function chant()
    {
        return $this->belongsTo(Chant::class);
    }

    public function partieEvent()
    {
        return $this->belongsTo(PartieEvent::class);
    }

    public function enregistrements()
    {
        return $this->hasMany(Enregistrement::class);
    }
}
