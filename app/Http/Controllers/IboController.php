<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Ibo;
use Session;

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
        $data = DB::table('ibos')->orderBy('id', 'desc')->paginate(15);
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
        return view('ibo.create');
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
        //var_dump($request->has('is_admin'));
        
        $model = new Ibo;
        $model->firstname = $request->firstname;
        $model->middlename = $request->middlename;
        $model->lastname = $request->lastname;
        $model->is_part_company = $request->has('is_part_company');
        $model->sponsor_id = $request->sponsor_id;
        $model->placement_id = $request->placement_id;
        $model->placement_position = $request->placement_position;
        $model->total_purchase_amount = $request->total_purchase_amount;
        $model->ranking_lions_id = $request->ranking_lions_id;
        $model->is_admin = $request->has('is_admin');
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
}
