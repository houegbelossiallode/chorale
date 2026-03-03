<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enregistrement extends Model
{
    protected $fillable = ['user_id', 'chant_id', 'repertoire_id', 'file_path', 'chef_comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chant()
    {
        return $this->belongsTo(Chant::class);
    }

    public function repertoire()
    {
        return $this->belongsTo(Repertoire::class);
    }
}
