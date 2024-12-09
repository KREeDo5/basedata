<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table = 'message';
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $attributes = [
        'visibility' => 'visible'
    ];

    public function thread(): BelongsTo {
        return $this->belongsTo(Thread::class, 'id_thread');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function images(): HasMany {
        return $this->hasMany(MessageImage::class, 'id_message');
    }
}
