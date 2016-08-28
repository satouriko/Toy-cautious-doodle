<?php

namespace App\Http\Controllers\Task;

use App\Task;
use App\Tasksignheader;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TemporaryTaskController extends Controller
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
        $Tasks = Task::where('uid', $uid)->where('temporary', true)->where('startdate', $request->date)->get();
        $data_str = $Tasks->toJson();
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
        $uid = $request->user()['id'];
        $task = new Task();

        $taskheader_cnt = Tasksignheader::where('uid', $uid)->count();
        if($taskheader_cnt == 0)
        {
            $header = new Tasksignheader();
            $header->uid = $uid;
            $header->begindate = $request->startdate;
            $header->save();
        }

        $task->uid = $uid;
        $task->startdate = $request->startdate;
        $task->temporary = true;
        $task->title = $request->title;
        $task->description = $request->description;

        $task->save();
    }

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
        Task::destroy($id);
    }
}
