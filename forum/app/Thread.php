<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Traits\Functions;

class Thread extends Model
{
    use Functions;
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

        $sortField = $request->header('sortField', 'created_at');
        $sortDirection = $request->header('sortDirection', 'desc');

        $categoriesId = [];
        if ($categoryId)
        {
            $categoriesId = getAllSubcategories($categoryId);
            $categoriesId[] = $categoryId;
        }
        
        $threads = Thread::with(['user'])
            ->where('visibility', 'visible')
            ->when(!empty($categoriesId), function ($query) use ($categoriesId) {
              $query->whereIn('id_category', $categoriesId);
            });

        if ($sortField == 'messagesCount')
        {
            $threads->withCount('messages')
                ->orderBy('messages_count', $sortDirection);
        }
        elseif ($sortField == 'lastMessage')
        {
            $theads->leftJoin('messages', 'thread.id', '=', 'messages.thread_id')
                ->groupBy('thread.id')
                ->orderBy(DB::raw('MAX(messages.created_at)'), $sortDirection);
        }
        else
        {
            $threads->orderBy($sortField, $sortDirection);
        }


        $sortedThreads = $threads->get();

        // не делаю проверку на существование, потому что, если нет тредов в категории, пусть всё равно отрисовывается пустая страница
        $data = [
            'threads' => $sortedThreads->map(function ($thread) {
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

            })
        ];

        return $this->getResponse(200, '', $data);
    }

    private function getAllSubcategories($categoryId)
    {
        $subcategories = Category::where('id_parent_category', $categoryId)->get();
        $ids = [];
        foreach ($subcategories as $subcategory) {
            $ids[] = $subcategory->id;
            $ids = array_merge($ids, $this->getAllSubcategories($subcategory->id));
        }
        return $ids;
    }

    public function getThreadInfo(Request $request) {
        $id = $request->header('id');
        $thread = Thread::with(['user', 'images', 'messages.user', 'messages.images'])
                ->where('id', $id)
                ->where('visibility', 'visible')
                ->first();

        if (!$thread) {
            return $this->getResponse(404, 'thread not found');
        }

        $data = [
            'threadInfo' => [
                'title' => $thread->title,
                'text' => $thread->text,
                'created_at' => $thread->created_at,
                'isClosed' => $thread->status == 'open' ? false : true,
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
            ]
        ];

        return $this->getResponse(200, '', $data);
    }

    public function createThread(Request $threadData)
    {
        DB::beginTransaction();

        try {
            if (Thread::where('id_category', $threadData->input('categoryId'))
                ->where('title', $threadData->input('title'))
                ->exists())
            {
                return $this->getResponse(409, 'similar thread is already exist');
            }

            if (!Category::where('id', $threadData->input('categoryId'))
                ->exists())
            {
                return $this->getResponse(405, 'this category does not exist');
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
                    return $this->getResponse(400, 'invalid file format(not array)');
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
            DB::commit();
        } catch (\Exception $error) {
            DB::rollBack();
            return $this->getResponse(500, 'creating thread error');
        }
    
        return $this->getResponse(200);
    }

    public function closeThread(Request $data) {
        $thread = Thread::where('id', $data->input('threadId'))
                ->first();

        if (!$thread)
        {
            return $this->getResponse(404, 'thread not found');
        }

        if ($thread->id_user == $data->input('userId'))
        {
            $thread->status = 'closed';
            $thread->save();

            return $this->getResponse(200); 
        }

        return $this->getResponse(403, 'you can not close this thread');
    }
}
