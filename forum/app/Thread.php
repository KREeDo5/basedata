<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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

    public function getThreads(Request $request) {
        $categoryId = $request->header('categoryId');
        //$categoriesId = [];
        //if ($categoryId) {
        //    $categoriesId = $this->getAllSubcategories($categoryId);
        //    $categoriesId[] = $categoryId;
        //}

        $threads = Thread::with(['user'])
            ->where('visibility', 'visible')
            ->when($categoryId !== null, function ($query) use ($categoryId) {
                $query->where('id_category', $categoryId);
            })
            ->get();

        // не делаю проверку на существование, потому что, если нет тредов в категории, пусть всё равно отрисовывается пустая страница
        $data = $threads->map(function ($thread) {
            return [
                'id' => $thread->id,
                'title' => $thread->title,
                'text' => $thread->text,
                'created_at' => $thread->created_at,
                'user' => [
                    'id' => $thread->user->id,
                    'name' => $thread->user->name,
                    'image_path' => $thread->user->image_path,
                ]
            ];
        });

        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => ['threads' => $data]
        ];
        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getThreadInfo(Request $request) {
        $id = $request->header('id');
        $thread = Thread::with(['user', 'images', 'messages.user', 'messages.images'])
                ->where('id', $id)
                ->where('visibility', 'visible')
                ->first();

        if (!$thread)
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'thread not found'],
                'data' => (object) []
            ];
            return response()->json($response, 404);
        }

        $data = [
            'title' => $thread->title,
            'text' => $thread->text,
            'created_at' => $thread->created_at,
            'user' => [
                'id' => $thread->user->id,
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
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'image_path' => $message->user->image_path,
                    ],
                    'images' => $message->images->map(function ($image) {
                        return $image->path; // Можно добавить asset
                    })->toArray(),
                ];
            })->toArray(),
        ];

        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => ['threadData' => $data]
        ];
        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function createThread(Request $threadData) {
        if (Thread::where('id_category', $threadData->input('categoryId'))
            ->where('title', $threadData->input('title'))
            ->exists())
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'similar thread is already exist'],
                'data' => (object) []
            ];
            return response()->json($response, 409);
        }
        if (!Category::where('id', $threadData->input('categoryId'))
            ->exists())
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'this category does not exist'],
                'data' => (object) []
            ];
            return response()->json($response, 405);
        }

        $thread = new Thread();
        $thread->id_category = $threadData->input('categoryId');
        $thread->id_user = $threadData->input('userId');
        $thread->title = $threadData->input('title');
        $thread->text = $threadData->input('text');
        $thread->save();

        $threadId = $thread->id;

        if ($threadData->hasFile('threadImages'))
        {
            $threadImages = $threadData->file('threadImages');
            if (!is_array($threadImages))
            {
                $response = [
                    'meta' => ['success' => false, 'error' => 'invalid file format(not array)'],
                    'data' => (object) []
                ];
                return response()->json($response, 400);
            }
            foreach ($threadImages as $threadImage)
            {
                if ($threadImage->isValid())
                {
                    $threadImagePath = $threadImage->store('threadImages');
                    ThreadImage::insert([
                        'id_thread' => $threadId,
                        'path' => $threadImagePath
                    ]);
                }
            };
        }
    
        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => (object) []
        ];
        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
