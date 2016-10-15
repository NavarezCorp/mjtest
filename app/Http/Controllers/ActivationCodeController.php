<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\ActivationCode;
use App\ActivationType;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivationCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['paginate'] = DB::table('activation_codes')->orderBy('id', 'desc')->paginate(15);
        $data['activation_types'] = ActivationType::pluck('name', 'id');
        return view('activationcode.index', ['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function get_activation_code(){
        $howmanychar = 8;
        $howmanycode = $_GET['howmanycode'];
        
        for($i = 0; $i < $howmanycode; $i++){
            $model = new ActivationCode;
            $model->code = encrypt(Str::upper($this->sfi_get_code($howmanychar)));
            $model->activation_type_id = $_GET['activation_type_id'];
            $model->created_by = Auth::user()->id;
            $model->save();
        }
        
        return json_encode(ActivationCode::get());
    }
    
    private function sfi_get_code($length){
        $randomData = base64_encode(file_get_contents('/dev/urandom', false, null, 0, $length) . uniqid(mt_rand(), true));
        return substr(strtr($randomData, array('/' => '', '=' => '', '+' => '')), 0, $length);
    }
}
