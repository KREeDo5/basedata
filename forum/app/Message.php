<?php

namespace App;

use Illuminate\Http\Request;
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

    public function createMessage(Request $data) {
        if (Message::where('id_thread', $data->input('threadId'))
            ->where('id_user', $data->input('userId'))
            ->where('text', $data->input('text'))
            ->exists())
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'this message is already exist'],
                'data' => (object) []
            ];
            return response()->json($response, 409);
        }
        $message = new Message();
        $message->text = $data->input('text');
        $message->id_user = $data->input('userId');
        $message->id_thread = $data->input('threadId');
        $message->save();
        
        $messageId = $message->id;
        $messageImages = $data->input('messageImages');

        if (is_array($messageImages) && !empty($messageImages))
        {
            foreach ($messageImages as $messageImage)
            {
                MessageImage::insert([
                    'id_message' => $messageId,
                    'path' => $messageImage
                ]);
            };
        }
    
        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => (object) []
        ];
        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
