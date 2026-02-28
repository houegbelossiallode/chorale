<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donateur extends Model
{
    protected $fillable = ['name', 'email', 'phone'];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
