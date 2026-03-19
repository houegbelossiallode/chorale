<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterHistory extends Model
{
    protected $fillable = [
        'subject',
        'content',
        'recipient_count',
        'sent_by',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class , 'sent_by');
    }
}
