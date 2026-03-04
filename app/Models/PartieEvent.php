<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartieEvent extends Model
{
    protected $guarded = [];

    public function chants()
    {
        return $this->belongsToMany(Chant::class, 'repertoire')->withTimestamps();
    }
}
