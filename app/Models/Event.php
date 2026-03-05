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

    public function getThumbnailAttribute()
    {
        // 1. Image principale ou première image uploadée sur l'événement
        $image = null;
        if ($this->relationLoaded('images') && $this->images->count() > 0) {
            $image = $this->images->firstWhere('is_principal', true) ?? $this->images->first();
        } else {
            $image = $this->principalImage ?? $this->images()->first();
        }

        if ($image) {
            $path = $image->image_path;
            if (str_starts_with($path, 'http')) {
                return $path;
            }
            return asset('storage/' . $path);
        }

        // 2. Image par défaut configurée sur le type d'événement (via DB)
        $type = $this->type;
        if ($type && $type->default_image) {
            if (str_starts_with($type->default_image, 'http')) {
                return $type->default_image;
            }
            return asset('storage/' . $type->default_image);
        }

        // 3. Fallback générique
        return 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?q=80&w=2070&auto=format&fit=crop';
    }
}
