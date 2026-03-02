<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $guarded = [''];

    protected $casts = [
        'is_granted' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function sousMenu()
    {
        return $this->belongsTo(SousMenu::class);
    }
}
