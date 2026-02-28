<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SousMenu extends Model
{
    protected $guarded = [''];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }
}
