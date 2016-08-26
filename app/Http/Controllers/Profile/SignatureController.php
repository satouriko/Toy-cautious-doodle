<?php

namespace App\Http\Controllers\Profile;

use App\Signature;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SignatureController extends Controller
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
        $Signatures = Signature::where('uid', $uid)->get();
        $data_str = $Signatures->toJson();
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
        $signature = new Signature();

        $signature->uid = $request->user()['id'];;
        $signature->signature = $request->signature;

        $signature->save();
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
        $signature = Signature::find($id);
        $signature->signature = $request->signature;
        $signature->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Signature::destroy($id);
    }
}
