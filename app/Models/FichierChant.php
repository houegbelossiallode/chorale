<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichierChant extends Model
{
    protected $guarded = [''];

    public function chant()
    {
        return $this->belongsTo(Chant::class);
    }

    public function pupitre()
    {
        return $this->belongsTo(Pupitre::class);
    }
}
