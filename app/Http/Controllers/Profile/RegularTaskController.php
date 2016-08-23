<?php

namespace App\Http\Controllers\Profile;

use App\Task;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RegularTaskController extends Controller
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
        $Tasks = Task::where('uid', $uid)->where('temporary', false)->where('valid', true)->get();
        $data_str = json_encode($Tasks);
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
        $task = new Task();

        $task->uid = $request->user()['id'];
        $task->startdate = $request->startdate;
        $task->period = $request->period;
        $task->activeday = $request->activeday;
        $task->temporary = false;
        $task->valid = true;
        $task->title = $request->title;
        $task->description = $request->description;

        $task->save();
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
        $task = Task::find($id);
        $task->period = $request->period;
        $task->activeday = $request->activeday;
        $task->title = $request->title;
        $task->description = $request->description;

        $task->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        $task->valid = false;
        $task->expiredate = date('Y-m-d');
        $task->save();
    }
}
