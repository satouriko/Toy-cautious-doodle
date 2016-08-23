<?php

namespace App\Http\Controllers\Profile;

use App\Nickname;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class NicknameController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $uid = $request->user()['id'];
        $Nicknames = Nickname::where('uid', $uid)->get();
        $data_str = json_encode($Nicknames);
        return $data_str;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nickname = new Nickname;

        $nickname->uid = $request->user()['id'];;
        $nickname->nickname = $request->nickname;

        $nickname->save();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Nickname::destroy($id);
    }
}
