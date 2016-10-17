<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\ActivationCode;
use App\ActivationType;
use App\User;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

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
        $data['all'] = DB::table('activation_codes')->orderBy('id', 'desc')->paginate(15);
        $data['not_yet_printed'] = DB::table('activation_codes')->where('printed', false)->orderBy('id', 'desc')->paginate(15);
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
    
    public function check_activation_code(){
        $data = null;
        
        $model = ActivationCode::all();
        
        foreach($model as $value){
            if(decrypt($value->code) == $_GET['code']){
                $data['code_to_check'] = $_GET['code'];
                $data['id'] = $value->id;
                //$data['code'] = $value->code;
                $data['activation_code_type'] = ActivationType::find($value->activation_type_id)->name;
                $data['created_by'] = User::find($value->created_by)->name;
                $data['used_by_ibo'] = !empty($value->used_by_ibo_id) ? User::find($value->used_by_ibo_id)->name : null;
                $data['datetime_used'] = !empty($value->datetime_used) ? $value->datetime_used : null;
                $data['created_at'] = $value->created_at;
                
                break;
            }
        }
        
        return json_encode($data);
    }
    
    public function print_code($type){
        $data = null;
        
        switch($type){
            case 'nypc':
                $data = ActivationCode::where('printed', false)->orderBy('id', 'desc')->get();
                break;
            
            case 'all':
                $data = ActivationCode::orderBy('id', 'desc')->get();
                break;
        }
        
        if($data){
            $pdf = PDF::loadView('activationcode.print', ['data'=>$data]);
            $pdf->setPaper('a4', 'landscape');
            return $pdf->stream();
        }
    }
}
