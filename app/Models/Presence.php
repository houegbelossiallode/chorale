<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = ['user_id', 'repetition_id', 'status', 'motif'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repetition()
    {
        return $this->belongsTo(Repetition::class);
    }
}
