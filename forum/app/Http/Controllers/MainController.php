<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserHasSubscribe;
use App\User;
use App\Category;
use App\Message;
use App\Thread;
use App\ThreadImage;
use App\Role;

/*
написать сериалайзы в моделях
здесь чисто обращатся к ним и "парсить"
возвращать json
*/

class MainController extends Controller
{
    public function home() {
        return view('home');
    }

    public function auth() {
        return view('login');
    }

    public function registration() {
        return view('registration');
    }

    public function profile() {
        return view('profile');
    }

    public function user(Request $request) {
        return User::find(1)->getUserInfo($request);
    }

    public function thread(Request $request) {
        return Thread::find(1)->getThreadInfo($request);
    }

    //public function mainCategories() {
    //    return Category::find(1)->getCategories(NULL);
    //}

    public function categories() {
        return Category::find(1)->getAllCategories();
    }

    public function allThreads() {
        return Thread::find(1)->getThreads(NULL);
    }

    public function categoryThreads(Request $request) {
        return Thread::find(1)->getThreads($request);
    }

    public function auth_check(Request $loginData) {
        return User::find(1)->authorization($loginData);
    }

    public function registration_check(Request $registrationData) {
        return User::find(1)->registration($registrationData);
    }

    public function changeProfile(Request $profileData) {
        return User::find(1)->changeProfile($profileData);
    }

    //public function changeProfileImage(Request $newImage) {
    //    return User::find(1)->changeProfileImage($newImage);
    //}

    public function changePassword(Request $data) {
        return User::find(1)->changePassword($data);
    }

    public function subscribe(Request $data) {
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
        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => (object) []
        ];
        return response()->json($response, 200);


        return UserHasSubscribe::find(1)->subscribe($data);
    }

    public function unsubscribe(Request $data) {
        UserHasSubscribe::where('id_user', $data->input('subscriber'))
            ->where('subscription', $data->input('subscription'))
            ->delete();
        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => (object) []
        ];

        
        return UserHasSubscribe::find(1)->unsubscribe($data);
    }
}
