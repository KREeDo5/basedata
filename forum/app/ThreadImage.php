<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThreadImage extends Model
{
    protected $table = 'thread_image';
    public $timestamps = false;

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class, 'id_thread');
    }
}
