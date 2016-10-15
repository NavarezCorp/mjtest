<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Ibo;
use Session;
use App\User;
use Illuminate\Validation\Rule;

class IboController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = DB::table('ibos')->orderBy('id', 'asc')->paginate(15);
        return view('ibo.index', ['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data['placement_id'] = !empty($_GET['placement_id']) ? $_GET['placement_id'] : '';
        $data['placement_position'] = !empty($_GET['placement_position']) ? $_GET['placement_position'] : '';
        return view('ibo.create', ['data'=>$data]);
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
        /*
        $this->validate($request, [
            'activation_code'=>'exists:activation_codes,code',
        ]);
        */
        /*
        Validator::make($request, [
            'activation_code' => [
                'required',
                Rule::exists('activation_codes')->where(function($query){
                    $query->where('code', decrypt($request->activation_code));
                }),
            ],
        ]);
        */
        $last_ibo_id = Ibo::where('is_part_company', false)->orderBy('id', 'desc')->first();
        
        if($last_ibo_id === NULL) $new_id = 1;
        else $new_id = $last_ibo_id->id + 1;
        
        $model = new Ibo;
        $model->id = $new_id;
        $model->firstname = $request->firstname;
        $model->middlename = $request->middlename;
        $model->lastname = $request->lastname;
        $model->is_part_company = $request->has('is_part_company');
        $model->sponsor_id = ltrim($request->sponsor_id, '0');
        $model->placement_id = $request->placement_id;
        $model->placement_position = $request->placement_position;
        $model->total_purchase_amount = $request->total_purchase_amount;
        $model->ranking_lions_id = $request->ranking_lions_id;
        $model->is_admin = $request->has('is_admin');
        $model->save();
        
        $model = new User;
        $model->name = $request->firstname . ' ' . $request->middlename . ' ' . $request->lastname;
        $model->email = sprintf('%09d', $new_id) . '@gmail.com';
        $model->password = bcrypt('123456');
        $model->ibo_id = $new_id;
        $model->remember_token = $model->getRememberToken();
        $model->save();
        
        Session::flash('message', 'IBO named "' . $request->firstname . ' ' . $request->middlename . ' ' . $request->lastname . '" was successfully created');
        return redirect('/ibo');
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
        $data = Ibo::find($id);
        return view('ibo.edit', ['data'=>$data]);
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
        $model = Ibo::find($id);
        $model->sponsor_id = $request->sponsor_id;
        $model->placement_id = $request->placement_id;
        $model->placement_position = $request->placement_position;
        $model->save();
        
        Session::flash('message', 'IBO ' . $request->firstname . ' ' . $request->middlename . ' ' . $request->lastname . ' was successfully updated');
        return redirect('/ibo');
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
}
