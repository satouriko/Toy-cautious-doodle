<?php

namespace App\Http\Controllers\Task;

use App\Task;
use App\Tasksign;
use App\Tasksignheader;
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

    public function check(Request $request)
    {
        $uid = $request->user()['id'];
        $sign_today_cnt = Tasksign::where('date', date('Y-m-d'))
            ->whereHas('task', function ($query) use ($uid) {
                $query->where('uid', $uid);
            })
            ->count();
        if ($sign_today_cnt > 0)
            return "signed";
        else
            return "unsigned";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $uid = $request->user()['id'];
        $sign_today_cnt = Tasksign::where('date', date('Y-m-d'))
            ->whereHas('task', function ($query) use ($uid) {
                $query->where('uid', $uid);
            })
            ->count();
        if ($sign_today_cnt > 0)
            return redirect('/');
        else
        {
            $user = $request->user()['email'];
            //按任务（！不是按表头）计算出今日任务
            $rgtasks_all = Task::where('uid', $uid)->where('temporary', false)->where('valid', true)->get();
            $rgtasks = array();
            foreach ($rgtasks_all as $rgtask_all) {
                $activedays = explode(',', $rgtask_all->activeday);
                $date_now = date_create(date('Y-m-d'));
                $date_start = date_create($rgtask_all->startdate);
                $diff = date_diff($date_start, $date_now);
                $diff_int = $diff->format("%d");
                if ($diff_int > 0 && in_array($diff_int % $rgtask_all->period + 1, $activedays)) {
                    array_push($rgtasks, $rgtask_all);
                }

            }
            $tptasks = Task::where('uid', $uid)->where('temporary', true)->where('startdate', date('Y-m-d'))->get()
                ->toArray();
            $tasks = array_merge($rgtasks, $tptasks);
            //return json_encode($tasks);
            return view("tasksignadd", ['user' => $user, 'tasks' => $tasks]);
        }
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
            $tasksign->date = date('Y-m-d');
            $tasksign->task_id = $task_id;
            $tasksign->grade = $grades[$looper];
            $tasksign->reason = $reasons[$looper];
            $tasksign->comment = $comments[$looper];
            $tasksign->tasksignheader_id = $tasksignheader['id'];

            $tasksign->save();
        }

        return redirect('/');

    }

}
