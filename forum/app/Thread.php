<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thread extends Model
{
    protected $table = 'thread';
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $attributes = [
        'visibility' => 'visible'
    ];

    public function images(): HasMany {
        return $this->hasMany(ThreadImage::class, 'id_thread');
    }

    public function messages(): HasMany {
        return $this->hasMany(Message::class, 'id_thread');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getThreads($categoryId)
    {
        $threads = Thread::with(['user'])
            ->where('visibility', 'visible')
            ->when($categoryId !== null, function ($query) use ($categoryId) {
                $query->where('id_category', $categoryId);
            })
            ->get();

        // не делаю проверку на существование, потому что, если нет тредов в категории, пусть всё равно отрисовывается пустая страница
        $data = $threads->map(function ($thread) {
            return [
                'thread' => [ // мб убрать 'thread'?
                    'id' => $thread->id,
                    'title' => $thread->title,
                    'text' => $thread->text,
                    'created_at' => $thread->created_at,
                    'user' => [
                        'name' => $thread->user->name,
                        'image_path' => $thread->user->image_path,
                    ]
                ]
            ];
        });

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getThreadInfo($id)
    {
        $thread = Thread::with(['user', 'images', 'messages.user', 'messages.images'])
                ->where('id', $id)
                ->where('visibility', 'visible')
                ->first();

        if (!$thread)
        {
            return response()->json(['error' => 'Thread not found'], 404);
        }

        $data = [
            'thread' => [
                'title' => $thread->title,
                'text' => $thread->text,
                'created_at' => $thread->created_at,
                'user' => [
                    'name' => $thread->user->name,
                    'image_path' => $thread->user->image_path,
                ],
                'images' => $thread->images->map(function ($image) {
                    return $image->path;
                })->toArray(),
                'messages' => $thread->messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'text' => $message->text,
                        'created_at' => $message->created_at,
                        'user' => [
                            'name' => $message->user->name,
                            'image_path' => $message->user->image_path,
                        ],
                        'images' => $message->images->map(function ($image) {
                            return $image->path; // Можно добавить asset
                        })->toArray(),
                    ];
                })->toArray(),
            ],
        ];

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
