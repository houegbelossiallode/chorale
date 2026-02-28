<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pupitre extends Model
{
    protected $fillable = ['name', 'description', 'responsable_id'];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
