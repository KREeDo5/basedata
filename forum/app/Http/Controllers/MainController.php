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

    public function deleteProfilePicture(Request $data) {
        return User::find(1)->deleteProfilePicture($data);
    }

    public function changePassword(Request $data) {
        return User::find(1)->changePassword($data);
    }

    public function closeThread(Request $data) {
        return Thread::find(1)->closeThread($data);
    }

    public function subscribe(Request $data) {
        $user1 = User::where('id', $data->input('subscriber'))
            ->first();
        $user2 = User::where('id', $data->input('subscription'))
            ->first();
        if (!$user1 or !$user2)
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'user not found'],
                'data' => (object) []
            ];
            return response()->json($response, 404);
        }
        if ($data->input('subscriber') == $data->input('subscription'))
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'you can not subscribe yourself'],
                'data' => (object) []
            ];
            return response()->json($response, 409);
        }
        if (UserHasSubscribe::where('id_user', $data->input('subscriber'))
                ->where('subscription', $data->input('subscription'))
                ->exists())
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'you are already subscribed'],
                'data' => (object) []
            ];
            return response()->json($response, 409);
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
        return response()->json($response, 200);

        return UserHasSubscribe::find(1)->unsubscribe($data);
    }

    public function createCategory(Request $data) {
        return Category::find(1)->createCategory($data);
    }

    public function createThread(Request $data) {
        return Thread::find(1)->createThread($data);
    }

    public function createMessage(Request $data) {
        return Message::find(1)->createMessage($data);
    }
}
