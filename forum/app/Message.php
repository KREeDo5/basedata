<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Traits\Functions;

class Message extends Model
{
    use Functions;
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

    public function createMessage(Request $messageData)
    {
        DB::beginTransaction();

        try {
            if (Message::where('id_thread', $messageData->input('threadId'))
                ->where('id_user', $messageData->input('userId'))
                ->where('text', $messageData->input('text'))
                ->exists())
            {
                $response = [
                    'meta' => ['success' => false, 'error' => 'this message is already exist'],
                    'data' => (object) []
                ];
                return response()->json($response, 409);
            }
            if (!Thread::where('id', $messageData->input('threadId'))
                ->where('status', 'open')
                ->exists())
            {
                $response = [
                    'meta' => ['success' => false, 'error' => 'this thread does not exist or closed'],
                    'data' => (object) []
                ];
                return response()->json($response, 405);
            }

            $message = new Message();
            $message->text = $messageData->input('text');
            $message->id_user = $messageData->input('userId');
            $message->id_thread = $messageData->input('threadId');
            $message->save();
            
            $messageId = $message->id;

            if ($messageData->hasFile('messageImages'))
            {
                $messageImages = $messageData->file('messageImages');
                if (!is_array($messageImages))
                {
                    return $this->getResponse(400, 'invalid file format(not array)');
                }
                
                foreach ($messageImages as $messageImage)
                {
                    if ($messageImage->isValid())
                    {
                        $messageImagePath = $messageImage->store('messageImages');
                        MessageImage::insert([
                            'id_message' => $messageId,
                            'path' => $messageImagePath
                        ]);
                    }
                };
            }
            DB::commit();
        } catch (\Exception $error) {
            DB::rollBack();
            return $this->getResponse(500, 'creating message error');
        }
    
        return $this->getResponse(200);
    }
}
