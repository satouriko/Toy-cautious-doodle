<?php

namespace App\Http\Controllers\Task;

use App\Family;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FamilyController extends Controller
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
        $Families = Family::where('uid', $uid)->get();
        $data_str = $Families->toJson();
        return $data_str;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $family = new Family();

        $family->uid = $request->user()['id'];;
        $family->title = $request->title;
        $family->description = $request->description;
        $family->destination = $request->destination;
        //$family->priority = $request->priority;

        $family->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $family = Family::find($id);
        $family->title = $request->title;
        $family->description = $request->description;
        $family->destination = $request->destination;
        //$family->priority = $request->priority;

        $family->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Family::destroy($id);
    }
}
