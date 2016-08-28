<?php

namespace App\Http\Controllers\Profile;

use App\Goal;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GoalController extends Controller
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
        $Goals = Goal::where('uid', $uid)->get();
        $data_str = $Goals->toJson();
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

        $goal = new Goal();

        $goal->uid = $uid;
        $goal->date = $request->date;
        $goal->title = $request->title;
        $goal->description = $request->description;
        $goal->state = "Pending";

        $goal->save();

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
        $goal = Goal::find($id);

        $goal->state = $request->state;

        $goal->save();
    }

}
