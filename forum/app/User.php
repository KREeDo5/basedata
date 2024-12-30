<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    protected $table = 'user';
    public $timestamps = false;
    const CREATED_AT = 'registration_date';

    public function threads(): HasMany {
        return $this->hasMany(Thread::class, 'id_user');
    }

    public function messages(): HasMany {
        return $this->hasMany(Message::class, 'id_user');
    }

    public function authorization(Request $loginData)
    {
        if (User::where('login', $loginData->input('login'))
            ->exists())
        {
            if (User::where('login', $loginData->input('login'))
                ->where('password', $loginData->input('password'))
                ->exists())
            {
                return response()->json(['success' => true, 'error' => ''], 200);
            }
            else
            {
                return response()->json(['success' => false, 'error' => 'wrong password'], 401);
            }
        }
        else
        {
            return response()->json(['success' => false, 'error' => 'wrong login'], 401);
        }
    }

    public function registration(Request $registrationData)
    {
        if (User::where('login', $registrationData->input('login'))
            ->doesntExist())
        {
            if (User::where('name', $registrationData->input('name'))
                ->doesntExist())
            {
                User::insert([
                    'name' => $registrationData->input('name'),
                    'login' => $registrationData->input('login'),
                    'password' => $registrationData->input('password'),
                    'id_role' => Role::where('title', 'пользователь')->value('id'),
                ]);
            }
            else
            {
                return response()->json(['success' => false, 'error' => 'this username is already exists'], 409);
            }
        }
        else
        {
            return response()->json(['success' => false, 'error' => 'this login is already exists'], 409);
        }
        return response()->json(['success' => true, 'error' => ''], 200);
    }

    public function getUserInfo($id)
    {
        $user = User::where('id', $id)
            ->select('name', 'description', 'image_path', 'registration_date')
            ->first();

        if (!$user)
        {
            return response()->json(['success' => false, 'error' => 'user not found'], 404);
        }

        $subscriptions = User::join('user_has_subscribe', 'user.id', '=', 'user_has_subscribe.subscription')
        ->select('user.name', 'user.image_path')
        ->where('user_has_subscribe.id_user', $id)
        ->get();

        $subscribers = User::join('user_has_subscribe', 'user.id', '=', 'user_has_subscribe.id_user')
        ->select('user.name', 'user.image_path')
        ->where('user_has_subscribe.subscription', $id)
        ->get();

        $data = [
            'user' => $user,
            'subscriptions' => $subscriptions,
            'subscribers' => $subscribers,
        ];
        
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function changeProfile(Request $profileData)
    {
        $user = User::where('id', $profileData->input('id'))
            ->first();
        if (!$user)
        {
            return response()->json(['success' => false, 'error' => 'user not found'], 404);
        }
        User::where('id', $profileData->input('id'))
            ->update([
                'name' => $profileData->input('name'),
                'description' => $profileData->input('description'),
                'image_path' => $profileData->input('image_path')
            ]);
        return response()->json(['success' => true, 'error' => ''], 200);
    }
}
