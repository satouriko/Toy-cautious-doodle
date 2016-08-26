<?php

namespace App\Http\Controllers\Task;

use App\Tasksign;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TaskSignController extends Controller
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $uid = $request->user()['id'];
        $sign_today_cnt = Task::where('uid', $uid)->where('date', date('Y-m-d'))->count();
        if ($sign_today_cnt > 0) {
            $signed = true;
        } else {
            $signed = false;
            //按任务（！不是按表头）计算出今日任务
        }
        return json_encode($signed);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ids = $request->task_id;
        $grades = $request->grade;
        $reasons = $request->reason;
        $comments = $request->comment;
        $uid = $request->user()['id'];

        $tasksignheader = Tasksignheader::where('uid', $uid)
            ->orderBy('begindate', 'desc')
            ->first();

        foreach ($ids as $looper => $task_id) {

            $tasksign = new Tasksign();
            $tasksign->uid = $uid;
            $tasksign->date = date('Y-m-d');
            $tasksign->task_id = $task_id;
            $tasksign->grade = $grades[$looper];
            $tasksign->reason = $reasons[$looper];
            $tasksign->comment = $comments[$looper];
            $tasksign->tasksignheader_id = $tasksignheader['id'];

            $tasksign->save();
        }


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
