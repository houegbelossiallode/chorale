<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionFinanciere extends Model
{
    protected $guarded = [''];

    public function categorie(){
        return $this->belongsTo(CategorieFinanciere::class);
    }
}
