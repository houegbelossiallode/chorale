<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    protected $fillable = ['title', 'objectif', 'atteint'];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
