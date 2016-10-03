<?php

namespace App\Http\Controllers\Task;

use App\Family;
use App\Ongoingtask;
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

        //维护Ongoing Tasks
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
        foreach ($rgtasks as $task) {
            if(Tasksign::where('taskdate', date('Y-m-d'))->where('task_id', $task->id)->count() == 0)
                Ongoingtask::firstOrCreate(['taskdate' => date('Y-m-d'), 'task_id' => $task->id]);
        }
        foreach ($tptasks as $task) {
            if(Tasksign::where('taskdate', date('Y-m-d'))->where('task_id', $task->id)->count() == 0)
                Ongoingtask::firstOrCreate(['taskdate' => date('Y-m-d'), 'task_id' => $task->id]);
        }

        //开始生成Tasksign Index
        $headers = Tasksignheader::where('uid', $uid)
            ->orderBy('begindate', 'desc')
            ->get();
        $ret = array();
        $ret_header_temp = array('header' => array(), 'tasksign' => array()); //一级缓存
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
                if ($tasksigns_day_cnt > 0) {
                    $tasksigns_day = Tasksign::where('tasksignheader_id', $header->id)
                        ->where('date', $enddate)
                        ->orderBy('task_id', 'asc')
                        ->with('task')
                        ->get();
                    $tasksigns[$enddate->format('Y-m-d')] = $tasksigns_day;
                }
                else if($enddate == date_create(date('Y-m-d'))) {
                    $ogtasks = Ongoingtask::whereHas('task', function ($query) use ($uid) {
                        $query->where('uid', $uid);
                    })->with('task')->get();
                    foreach ($ogtasks as $ogtask) {
                        $ogtask->grade = "Pending";
                    }
                    $tasksigns[date('Y-m-d', time())] = $ogtasks;
                }
                date_modify($enddate, "-1 day");
            }

            $ret_header['tasksigns'] = $tasksigns;

            //智能合并表头
            if($ret_header['header'] != $ret_header_temp['header']) {
                if($ret_header_temp['header'] != null)
                    array_push($ret, $ret_header_temp);
                $ret_header_temp = $ret_header;
            }
            else {
                foreach ($ret_header['tasksigns'] as $key => $tasksign) {
                    $ret_header_temp['tasksigns'][$key] = $tasksign;
                }
            }
        }
        if($ret_header_temp['header'] != null)
            array_push($ret, $ret_header_temp);

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
            $tasks = Ongoingtask::whereHas('task', function ($query) use ($uid) {
                $query->where('uid', $uid);
            })->with('task')->get();
            //return json_encode($tasks);
            return view("tasksignadd", ['user' => $user, 'tasks' => $tasks, 'date_today' => date('Y-m-d', time())]);
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

        foreach ($ids as $looper => $ogtask_id) {
            $ogtask = Ongoingtask::where('id', $ogtask_id)->first();

            $tasksign = new Tasksign();
            $tasksign->date = date('Y-m-d');
            $tasksign->taskdate = $ogtask->taskdate;
            $tasksign->detail = $ogtask->detail;
            $tasksign->task_id = $ogtask->task_id;
            $tasksign->grade = $grades[$looper];
            $tasksign->reason = $reasons[$looper];
            $tasksign->comment = $comments[$looper];
            $tasksign->tasksignheader_id = $tasksignheader['id'];

            $tasksign->save();

            if($tasksign->grade == "Checked" || $tasksign->grade == "Cancelled")
                Ongoingtask::destroy($ogtask_id);
        }

        return redirect('/');

    }

    public function reset(Request $request)
    {
        $uid = $request->user()['id'];
        $tasksigns_today = Tasksign::where('date', date('Y-m-d'))
            ->whereHas('task', function ($query) use ($uid) {
                $query->where('uid', $uid);
            })->get();
        foreach ($tasksigns_today as $tasksign_today) {
            $ogtask = new Ongoingtask();
            $ogtask->taskdate = $tasksign_today->taskdate;
            $ogtask->detail = $tasksign_today->detail;
            $ogtask->task_id = $tasksign_today->task_id;
            $ogtask->save();
        }
        Tasksign::where('date', date('Y-m-d'))
            ->whereHas('task', function ($query) use ($uid) {
                $query->where('uid', $uid);
            })
            ->delete();
    }

}
