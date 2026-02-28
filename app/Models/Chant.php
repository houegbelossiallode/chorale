<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chant extends Model
{
    protected $guarded = [''];

    public function fichiers()
    {
        return $this->hasMany(FichierChant::class);
    }
}
