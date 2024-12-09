<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Category;
use App\UserHasSubscribe;
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

    public function user($id) {
        return User::find(1)->getUserInfo($id);
    }

    public function thread($id) {
        return Thread::find(1)->getThreadInfo($id);
    }

    public function mainCategories() {
        return Category::find(1)->getCategories(NULL);
    }

    public function categories($parentCategoryId) {
        return Category::find(1)->getCategories($parentCategoryId);
    }

    public function allThreads() {
        return Thread::find(1)->getThreads(NULL);
    }

    public function categoryThreads($categoryId) {
        return Thread::find(1)->getThreads($categoryId);
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

    public function changeProfileImage(Request $newImage) {
        return User::find(1)->changeProfileImage($newImage);
    }
}
