<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageImage extends Model
{
    protected $table = 'message_image';
    public $timestamps = false;

    public function message(): BelongsTo {
        return $this->belongsTo(Message::class, 'id_message');
    }
}
