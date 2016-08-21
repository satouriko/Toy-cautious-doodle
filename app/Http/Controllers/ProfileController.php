<?php

namespace App\Http\Controllers;

use App\Nickname;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user()['email'];
        $uid = $request->user()['id'];
        return view('profile',['user' => $user, 'uid' => $uid]);
    }
}
