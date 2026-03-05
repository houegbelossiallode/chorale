<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['libelle', 'default_image', 'actif'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
