<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Country;
use App\City;
use Session;
use App\PickupCenter;

class PickupCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['pickup_centers'] = DB::table('pickup_centers')->orderBy('id', 'desc')->paginate(15);
        return view('pickupcenter.index', ['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data['countries'] = Country::pluck('name', 'id');
        $data['cities'] = City::pluck('name', 'id');
        return view('pickupcenter.create', ['data'=>$data]);
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
        $model = new PickupCenter;
        $model->country_id = $request->country_id;
        $model->city_id = $request->city_id;
        $model->branch = $request->branch;
        $model->save();
        
        Session::flash('message', 'Pickup Center named "' . $request->name . '" was successfully created');
        return redirect('/pickupcenter');
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
        $data['countries'] = Country::pluck('name', 'id');
        $data['cities'] = City::pluck('name', 'id');
        $data['pickup_centers'] = PickupCenter::find($id);
        return view('pickupcenter.edit', ['data'=>$data]);
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
        $model = PickupCenter::find($id);
        $model->country_id = $request->country_id;
        $model->city_id = $request->city_id;
        $model->branch = $request->branch;
        $model->save();
        
        Session::flash('message', 'Pickup Center ' . $request->name . ' was successfully updated');
        return redirect('/pickupcenter');
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
