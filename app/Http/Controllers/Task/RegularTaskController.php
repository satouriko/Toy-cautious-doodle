<?php

namespace App\Http\Controllers\Task;

use App\Task;
use App\Tasksign;
use App\Tasksignheader;
use App\Tasksignheadertask;
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
        $Tasks = Task::where('uid', $uid)->where('temporary', false)->where('valid', true)->with('family')->get();
        foreach ($Tasks as $task)
        {
            $day_cnt = Tasksign::where('task_id', $task->id)->where('grade','Checked')->count();
            $task->day_cnt = $day_cnt;
        }
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

        $task->uid = $uid;
        $task->startdate = $request->startdate;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->family_id = $request->family_id;
        $task->temporary = $request->temporary;
        if(!$task->temporary) {
            $task->valid = true;
            $task->period = $request->period;
            if ($task->period == 0)
                $task->period = 1;
            $task->activeday = $request->activeday;
            $task->type = $request->type;
        }
        else {
            $task->type = "activity";
        }

        $task->save();

        /**
         * 维护表头表
         */

        //取回未来/过去最近的表头
        $header_past_cnt = Tasksignheader::where('uid', $uid)
            ->where('begindate', '<', $request->startdate)
            ->count();
        if ($header_past_cnt > 0)
            $header_past = Tasksignheader::where('uid', $uid)
                ->where('begindate', '<', $request->startdate)
                ->orderBy('begindate', 'desc')
                ->first();
        $header_future_cnt = Tasksignheader::where('uid', $uid)
            ->where('begindate', '>', $request->startdate)
            ->count();
        if ($header_future_cnt > 0)
            $header_future = Tasksignheader::where('uid', $uid)
                ->where('begindate', '>', $request->startdate)
                ->orderBy('begindate', 'asc')
                ->first();

        //取回或新建现在的表头
        $header_now_cnt = Tasksignheader::where('uid', $uid)
            ->where('begindate', $request->startdate)
            ->count();
        $header_now = Tasksignheader::firstOrNew(['uid' => $uid, 'begindate' => $request->startdate]);

        //维护上一，当前表头
        if ($header_past_cnt > 0) {
            $header_past->enddate = $request->startdate;
            $header_past->save();
        }
        if ($header_future_cnt > 0) {
            $header_now->enddate = $header_future->begindate;
        }
        $header_now->save();

        /*
         * 维护表头任务表
         */

        //当前表头为新建，继承上一表头
        if ($header_now_cnt == 0 && $header_past_cnt > 0) {
            //取回上一表头
            $headertasks_past = Tasksignheadertask::where('tasksignheader_id', $header_past->id)
                ->get();
            foreach ($headertasks_past as $headertask_past) {
                $tasksignheadertask = new Tasksignheadertask();
                $tasksignheadertask->task_id = $headertask_past->task_id;
                $tasksignheadertask->tasksignheader_id = $header_now->id;
                $tasksignheadertask->save();
            }
        }

        //向当前和未来表头添加新任务
        $headers_all2update = Tasksignheader::where('uid', $uid)
            ->where('begindate', '>=', $request->startdate)
            ->get();
        foreach ($headers_all2update as $header_all2update) {
            $tasksignheadertask = new Tasksignheadertask();
            $tasksignheadertask->task_id = $task->id;
            $tasksignheadertask->tasksignheader_id = $header_all2update->id;
            $tasksignheadertask->save();
        }


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
        if($task->period == 0)
            $task->period = 1;
        $task->activeday = $request->activeday;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->family_id = $request->family_id;
        $task->type = $request->type;

        $task->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $uid = $request->user()['id'];
        $task = Task::find($id);
        $task->valid = false;
        $task->expiredate = date('Y-m-d');
        $task->save();

        /**
         * 维护表头表
         */

        //取回未来/过去最近的表头
        $header_past_cnt = Tasksignheader::where('uid', $uid)
            ->where('begindate', '<', date('Y-m-d'))
            ->count();
        if ($header_past_cnt > 0)
            $header_past = Tasksignheader::where('uid', $uid)
                ->where('begindate', '<', date('Y-m-d'))
                ->orderBy('begindate', 'desc')
                ->first();
        $header_future_cnt = Tasksignheader::where('uid', $uid)
            ->where('begindate', '>', date('Y-m-d'))
            ->count();
        if ($header_future_cnt > 0)
            $header_future = Tasksignheader::where('uid', $uid)
                ->where('begindate', '>', date('Y-m-d'))
                ->orderBy('begindate', 'asc')
                ->first();

        //取回或新建现在的表头
        $header_now_cnt = Tasksignheader::where('uid', $uid)
            ->where('begindate', date('Y-m-d'))
            ->count();
        $header_now = Tasksignheader::firstOrNew(['uid' => $uid, 'begindate' => date('Y-m-d')]);

        //维护上一，当前表头
        if ($header_past_cnt > 0) {
            $header_past->enddate = date('Y-m-d');
            $header_past->save();
        }
        if ($header_future_cnt > 0) {
            $header_now->enddate = $header_future->begindate;
        }
        $header_now->save();

        /*
         * 维护表头任务表
         */

        //当前表头为新建，继承上一表头
        if ($header_now_cnt == 0 && $header_past_cnt > 0) {
            //取回上一表头
            $headertasks_past = Tasksignheadertask::where('tasksignheader_id', $header_past->id)
                ->get();
            foreach ($headertasks_past as $headertask_past) {
                $tasksignheadertask = new Tasksignheadertask();
                $tasksignheadertask->task_id = $headertask_past->task_id;
                $tasksignheadertask->tasksignheader_id = $header_now->id;
                $tasksignheadertask->save();
            }
        }

        //向当前和未来表头删除新任务
        Tasksignheadertask::where('task_id', $id)
            ->whereHas('tasksignheader', function ($query) {
                $query->where('begindate', '>=', date('Y-m-d'));

            })
            ->delete();

    }
}
