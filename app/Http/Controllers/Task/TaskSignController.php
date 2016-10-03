<?php

namespace App\Http\Controllers\Task;

use App\Family;
use App\Task;
use App\Tasksign;
use App\Tasksignheader;
use App\Tasksignheadertask;
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
    public function index(Request $request)
    {
        $uid = $request->user()['id'];
        $headers = Tasksignheader::where('uid', $uid)
            ->orderBy('begindate', 'desc')
            ->get();
        $ret = array();
        foreach ($headers as $header) {
            if ($header->begindate == $header->enddate)
                continue;
            $ret_header = array();
            $tsheader_tasks = Tasksignheadertask::where('tasksignheader_id', $header->id)
                ->orderBy('task_id', 'asc')
                ->with('task')
                ->get();
            $tsheader_family_ids = array();
            foreach ($tsheader_tasks as $tsheader_task) {
                array_push($tsheader_family_ids,$tsheader_task->task->family_id);
            }
            $tsheader_families = Family::whereIn('id',$tsheader_family_ids)->get();
            $ret_header['header'] = $tsheader_families;
            $begindate = date_create($header->begindate);
            if ($header->enddate == '0000-00-00')
                $enddate = date_create(date('Y-m-d'));
            else {
                $enddate = date_create($header->enddate);
                date_modify($enddate, "-1 day");
            }

            $tasksigns = array();
            while (date_diff($begindate, $enddate)->format("%R%a") >= 0) {
                $tasksigns_day_cnt = Tasksign::where('tasksignheader_id', $header->id)
                    ->where('date', $enddate)
                    ->count();
                if ($tasksigns_day_cnt > 0)
                    $tasksigns_day = Tasksign::where('tasksignheader_id', $header->id)
                        ->where('date', $enddate)
                        ->orderBy('task_id', 'asc')
                        ->with('task')
                        ->get();
                else {
                    $tasksigns_day = array();
                    $rgtasks_all = Task::where('uid', $uid)->where('temporary', false)->where('valid', true)->get();
                    foreach ($rgtasks_all as $rgtask_all) {
                        $activedays = explode(',', $rgtask_all->activeday);
                        $date_start = date_create($rgtask_all->startdate);
                        $diff = date_diff($date_start, $enddate);
                        $diff_int = $diff->format("%R%a");
                        if ($diff_int >= 0 && in_array($diff_int % $rgtask_all->period + 1, $activedays)) {
                            $tasksignrg_day_fake['task_id'] = $rgtask_all->id;
                            $tasksignrg_day_fake['task'] = $rgtask_all;
                            $tasksignrg_day_fake['grade'] = "Pending";
                            array_push($tasksigns_day, $tasksignrg_day_fake);
                        }
                    }
                    $tptasks = Task::where('uid', $uid)->where('temporary', true)->where('startdate', $enddate)->get();
                    foreach ($tptasks as $tptask) {
                        $tasksigntp_day_fake['task_id'] = $tptask->id;
                        $tasksigntp_day_fake['grade'] = "Pending";
                        $tasksigntp_day_fake['task'] = $tptask;
                        array_push($tasksigns_day, $tasksigntp_day_fake);
                    }
                }
                $tasksigns[$enddate->format('Y-m-d')] = $tasksigns_day;
                date_modify($enddate, "-1 day");
            }
            $ret_header['tasksigns'] = $tasksigns;
            array_push($ret, $ret_header);
        }

        return json_encode($ret);
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
        else {
            $user = $request->user()['email'];
            //按任务（！不是按表头）计算出今日任务
            $rgtasks_all = Task::where('uid', $uid)->where('temporary', false)->where('valid', true)->get();
            $rgtasks = array();
            foreach ($rgtasks_all as $rgtask_all) {
                $activedays = explode(',', $rgtask_all->activeday);
                $date_now = date_create(date('Y-m-d'));
                $date_start = date_create($rgtask_all->startdate);
                $diff = date_diff($date_start, $date_now);
                $diff_int = $diff->format("%R%a");
                if ($diff_int >= 0 && in_array($diff_int % $rgtask_all->period + 1, $activedays)) {
                    array_push($rgtasks, $rgtask_all);
                }

            }
            $tptasks = Task::where('uid', $uid)->where('temporary', true)->where('startdate', date('Y-m-d'))->get();
            $tasks = $rgtasks;
            foreach ($tptasks as $tptask) {
                array_push($tasks, $tptask);
            }
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
            ->where('begindate', '<=', date('Y-m-d'))
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

    public function reset(Request $request)
    {
        $uid = $request->user()['id'];
        Tasksign::where('date', date('Y-m-d'))
            ->whereHas('task', function ($query) use ($uid) {
                $query->where('uid', $uid);
            })
            ->delete();
    }

}
