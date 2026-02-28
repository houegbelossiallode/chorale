<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['libelle'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
