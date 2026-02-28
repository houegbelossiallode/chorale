<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [''];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function sousMenus()
    {
        return $this->hasMany(SousMenu::class);
    }
}
