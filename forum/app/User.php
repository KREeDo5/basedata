<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use App\Traits\Functions;

class User extends Model
{
    use Functions;
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
                $user = User::where('login', $loginData->input('login'))
                        ->where('password', $loginData->input('password'))
                        ->first();
                $response = [
                    'meta' => ['success' => true, 'error' => ''],
                    'data' => ['userId' => $user->id]
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'meta' => ['success' => false, 'error' => 'wrong password'],
                    'data' => (object) [],
                ];
                return response()->json($response, 401);
            }
        } else {
            $response = [
                'meta' => ['success' => false, 'error' => 'wrong login'],
                'data' => (object) [],
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
                $user = new User();
                $user->name = $registrationData->input('name');
                $user->login = $registrationData->input('login');
                $user->password = $registrationData->input('password');
                $user->id_role = Role::where('title', 'пользователь')->value('id');
                $user->save();

                $response = [
                    'meta' => ['success' => true, 'error' => ''],
                    'data' => ['userId' => $user->id]
                ];
                return response()->json($response, 200);
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

        return $this->getResponse(200);
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

        $subscriptions = User::join('user_has_subscribe', 'user.id', '=', 'user_has_subscribe.subscription')
        ->select('user.id', 'user.name', 'user.image_path')
        ->where('user_has_subscribe.id_user', $id)
        ->get();

        $subscribers = User::join('user_has_subscribe', 'user.id', '=', 'user_has_subscribe.id_user')
        ->select('user.id', 'user.name', 'user.image_path')
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

        $name = $user->name;
        $image_path = $user->image_path;

        $newName = $profileData->input('name');
        if ($newName !== '' && $newName !== null)
        {
            if (User::where('name', $profileData->input('name'))
                ->where('id', '!=', $user->id)
                ->doesntExist())
            {
                $name = $profileData->input('name');
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
                'meta' => ['success' => false, 'error' => 'username can not be empty'],
                'data' => (object) []
            ];
            return response()->json($response, 400);
        }

        if ($profileData->hasFile('image') && $profileData->file('image')->isValid())
        {
            $image_path = $profileData->file('image')->store('avatars');
        }

        User::where('id', $profileData->input('id'))
            ->update([
                'name' => $name,
                'description' => $profileData->input('description'),
                'image_path' => $image_path
            ]);

        return $this->getResponse(200);
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

        return $this->getResponse(200);
    }

    public function deleteProfilePicture(Request $data)
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

        if ($user->image_path)
        {
            if (Storage::exists($user->image_path))
            {
                Storage::delete($user->image_path);
            }
            $user->image_path = null;
            $user->save();
            return $this->getResponse(200);
        }

        $response = [
            'meta' => ['success' => false, 'error' => 'no profile picture to delete'],
            'data' => (object) []
        ];
        return response()->json($response, 400);
    }
}
