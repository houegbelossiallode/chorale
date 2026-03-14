<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sondage extends Model
{
    protected $fillable = ['user_id', 'event_id', 'repetition_id', 'choix'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function repetition()
    {
        return $this->belongsTo(Repetition::class);
    }
}
