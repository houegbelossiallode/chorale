<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $guarded = [];

    /**
     * L'utilisateur qui a liké (si connecté)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Le choriste qui reçoit le like
     */
    public function choriste()
    {
        return $this->belongsTo(User::class, 'choriste_id');
    }
}
