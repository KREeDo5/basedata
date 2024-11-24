<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserHasSubscribe;
use App\Message;
use App\Thread;
use App\ThreadImage;
use App\Role;

class MainController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function user($id)
    {
        if (User::where('id', $id)->exists())
        {
            $user = User::where('id', $id)
            ->select('name', 'description', 'image_path', 'registration_date')
            ->get()[0];

            $subscriptions = User::join('user_has_subscribe', 'user.id', '=', 'user_has_subscribe.subscription')
            ->select('user.name', 'user.image_path')
            ->where('user_has_subscribe.id_user', $id)
            ->get();

            $subscribers = User::join('user_has_subscribe', 'user.id', '=', 'user_has_subscribe.id_user')
            ->select('user.name', 'user.image_path')
            ->where('user_has_subscribe.subscription', $id)
            ->get();

            dump($user);
            dump($subscriptions);
            dump($subscribers);
        }
        else
        {
            return 'wrong user id';
        }
    }

    public function thread($id) // НАДО ВЫВЕСТИ ИЗОБРАЖЕНИЯ СООБЩЕНИЯ
    {
        if (Thread::where('id', $id)->where('visibility', 'visible')->exists())
        {
            $thread = Thread::join('user', 'thread.id_user', '=', 'user.id')
            ->select('thread.title', 'thread.text', 'thread.created_at', 'user.name', 'user.image_path')
            ->where('thread.id', $id)
            ->get()[0];

            $threadImages = ThreadImage::where('id_thread', $id)
            ->select('path')
            ->get();

            $threadMessages = Message::join('user', 'message.id_user', '=', 'user.id')
            ->select('message.text', 'message.created_at', 'user.name', 'user.image_path')
            ->where('message.id_thread', $id)
            ->where('message.visibility', 'visible')
            ->get();

            dump($thread);
            dump($threadImages);
            dump($threadMessages);
        }
        else
        {
            return 'wrong thread id';
        }
    }

    public function auth()
    {
        return view('login');
    }

    public function auth_check(Request $loginData)
    {
        if (User::where('login', $loginData->input('login'))
            ->exists())
        {
            if (User::where('login', $loginData->input('login'))
                ->where('password', $loginData->input('password'))
                ->exists())
            {
                return'success';
            }
            else
            {
                return 'wrong password';
            }
        }
        else
        {
            return 'wrong login';
        }
    }

    public function registration()
    {
        return view('registration');
    }

    public function registration_check(Request $registrationData)
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
                return 'this username is already exists';
            }
        }
        else
        {
            return 'this login is already exists';
        }
    }
}
