<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $guarded = [''];

    public function donateur()
    {
        return $this->belongsTo(Donateur::class);
    }

    public function transaction()
    {
        return $this->belongsTo(TransactionFinanciere::class);
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
}
