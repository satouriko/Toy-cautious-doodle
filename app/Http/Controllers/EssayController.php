<?php

namespace App\Http\Controllers;

use App\Essay;
use App\Tasksign;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EssayController extends Controller
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
        $user = $request->user()['email'];
        $essays = Essay::where('uid', $uid)->orderBy('time', 'desc')->get();
        //$data_str = $essays->toJson();
        //return $data_str;
        return view("essay", ['user' => $user, 'essays' => $essays]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uid = $request->user()['id'];

        $essay = new Essay();

        $essay->uid = $uid;
        $essay->time = date('Y-m-d h:i:s', time());
        $essay->content = $request->essaycontent;

        $sign_today_cnt = Tasksign::where('date', date('Y-m-d'))
            ->whereHas('task', function ($query) use ($uid) {
                $query->where('uid', $uid);
            })
            ->count();

        if ($sign_today_cnt > 0)
            $essay->isTasksigned = true;
        else
            $essay->isTasksigned = false;

        $essay->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
