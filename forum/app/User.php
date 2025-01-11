<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

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
                $response = [
                    'meta' => ['success' => true, 'error' => ''],
                    'data' => (object) []
                ];
                return response()->json($response, 200);
            }
            else
            {
                $response = [
                    'meta' => ['success' => false, 'error' => 'wrong password'],
                    'data' => (object) []
                ];
                return response()->json($response, 401);
            }
        }
        else
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'wrong login'],
                'data' => (object) []
            ];
            return response()->json($response, 401);
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
                $response = [
                    'meta' => ['success' => false, 'error' => 'this username is already exists'],
                    'data' => (object) []
                ];
                return response()->json($response, 409);
            }
        }
        else
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'this login is already exists'],
                'data' => (object) []
            ];
            return response()->json($response, 409);
        }

        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => (object) []
        ];

        return response()->json($response, 200);
    }

    public function getUserInfo(Request $request)
    {
        $id = $request->header('id');
        $user = User::where('id', $id)
            ->select('name', 'description', 'image_path', 'registration_date')
            ->first();

        if (!$user)
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'user not found'],
                'data' => (object) []
            ];
            return response()->json($response, 404);
        }

        //$user->image = null;
        //if ($user->image_path && Storage::exists($user->image_path))
        //{
        //    $filePath = storage_path('app/' . $user->image_path);
        //    $user->image = base64_encode(file_get_contents($filePath));
        //}

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

        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => ['userData' => $data]
        ];

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function changeProfile(Request $profileData)
    {
        $user = User::where('id', $profileData->input('id'))
            ->first();

        if (!$user)
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'user not found'],
                'data' => (object) []
            ];
            return response()->json($response, 404);
        }

        $image_path = null;
        if ($profileData->hasFile('image') && $profileData->file('image')->isValid())
        {
            $image_path = $profileData->file('image')->store('avatars');
        }
        User::where('id', $profileData->input('id'))
            ->update([
                'name' => $profileData->input('name'),
                'description' => $profileData->input('description'),
                'image_path' => $image_path
            ]);

        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => (object) []
        ];

        return response()->json($response, 200);
    }

    public function changePassword(Request $data)
    {
        $user = User::where('id', $data->input('id'))
            ->first();

        if (!$user)
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'user not found'],
                'data' => (object) []
            ];
            return response()->json($response, 404);
        }

        if ($user->password !== $data->input('oldPassword'))
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'wrong old password'],
                'data' => (object) []
            ];
            return response()->json($response, 401);
        }

        User::where('id', $data->input('id'))
            ->update(['password' => $data->input('newPassword')]);

        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => (object) []
        ];

        return response()->json($response, 200);
    }
}
