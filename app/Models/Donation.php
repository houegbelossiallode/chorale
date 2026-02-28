<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = ['donateur_id', 'projet_id', 'amount', 'payment_method', 'reference_transaction'];

    public function donateur()
    {
        return $this->belongsTo(Donateur::class);
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
}
