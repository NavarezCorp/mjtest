<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Package;
use App\User;
use App\PackageType;
use Session;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = DB::table('packages')->orderBy('id', 'desc')->paginate(15);
        return view('package.index', ['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data['package_types'] = PackageType::pluck('name', 'id');
        $data['users'] = User::pluck('name', 'id');
        return view('package.create', ['data'=>$data]);
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
        $model = new Package;
        $model->activation_code = $request->activation_code;
        $model->package_type_id = $request->package_type_id;
        $model->created_by = $request->created_by;
        $model->is_used = $request->has('is_used');
        $model->datetime_used = $request->datetime_used;
        $model->used_by_ibo_id = $request->used_by_ibo_id;
        $model->encoded_by_ibo_id = $request->encoded_by_ibo_id;
        $model->save();
        
        Session::flash('message', 'Package was successfully created');
        return redirect('/package');
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
        $data['info'] = Package::find($id);
        $data['package_types'] = PackageType::pluck('name', 'id');
        $data['users'] = User::pluck('name', 'id');
        return view('package.edit', ['data'=>$data]);
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
        $model = Package::find($id);
        $model->package_type_id = $request->package_type_id;
        $model->created_by = $request->created_by;
        $model->datetime_used = $request->datetime_used;
        $model->used_by_ibo_id = $request->used_by_ibo_id;
        $model->encoded_by_ibo_id = $request->encoded_by_ibo_id;
        $model->is_used = $request->has('is_used');
        $model->save();
        
        Session::flash('message', 'Package was successfully updated');
        return redirect('/package');
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
