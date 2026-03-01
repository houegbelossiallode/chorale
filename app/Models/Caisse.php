<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caisse extends Model
{
    protected $fillable = ['nom', 'solde'];

    public function transactions()
    {
        return $this->hasMany(TransactionFinanciere::class);
    }
}
