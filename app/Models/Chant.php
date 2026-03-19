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

    public function repertoireEvents()
    {
        return $this->belongsToMany(Event::class , 'repertoire')->withPivot('partie_event_id')->withTimestamps();
    }

    public function categorieChant()
    {
        return $this->belongsTo(CategorieChant::class);
    }
}
