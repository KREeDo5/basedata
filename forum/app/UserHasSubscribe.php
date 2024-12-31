<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHasSubscribe extends Model
{
    protected $table = 'user_has_subscribe';
    public $timestamps = false;
    protected $primaryKey = 'id_user';
    public $incrementing = false;

    public function subscribe(Request $data)
    {
        $user1 = User::where('id', $data->input('subscriber'))
            ->first();
        $user2 = User::where('id', $data->input('subscription'))
            ->first();
        if (!$user1 or !$user2)
        {
            return response()->json(['success' => false, 'error' => 'user not found'], 404);
        }
        UserHasSubscribe::insert([
            'id_user' => $data->input('subscriber'),
            'subscription' => $data->input('subscription')
        ]);
        return response()->json(['success' => true, 'error' => ''], 200);
    }

    public function unsubscribe(Request $data)
    {
        UserHasSubscribe::where('id_user', $data->input('subscriber'))
            ->where('subscription', $data->input('subscription'))
            ->delete();
        return response()->json(['success' => true, 'error' => ''], 200);
    }
}
