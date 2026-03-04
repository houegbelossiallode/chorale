<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }

    public function principalImage()
    {
        return $this->hasOne(EventImage::class)->where('is_principal', DB::raw('true'));
    }

    public function repertoire()
    {
        return $this->belongsToMany(Chant::class, 'repertoire')->withPivot('id', 'partie_event_id')->withTimestamps();
    }

    public function repertoireEntries()
    {
        return $this->hasMany(Repertoire::class, 'event_id');
    }

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_public' => 'boolean',
        'is_repertoire_public' => 'boolean',
    ];
}
