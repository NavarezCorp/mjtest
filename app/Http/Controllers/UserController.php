<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Session;
use App\Bank;
use App\MaritalStatus;
use App\Gender;
use App\Country;
use App\City;
use App\Ibo;
use App\PickupCenter;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //$data = User::find($id);
        //return view('user.edit', ['data'=>$data]);
        
        $data['ibo'] = Ibo::find($id);
        $data['banks'] = Bank::pluck('name', 'id');
        $data['marital_status'] = MaritalStatus::pluck('name', 'id');
        $data['genders'] = Gender::pluck('name', 'id');
        
        $pickup_centers = PickupCenter::get();
        
        foreach($pickup_centers as $value){
            $country = Country::find($value->country_id)->name;
            $city = City::find($value->city_id)->name;
            
            $data['pickup_centers'][$country][$value->id] = $city . ' (' . $value->branch . ')';
        }
        
        return view('user.profile', ['data'=>$data]);
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
        $model = User::find($id);
        $model->name = $request->name;
        $model->email = $request->email;
        
        if($request->password) $model->password = bcrypt($request->password);
        
        $model->ibo_id = $request->ibo_id;
        $model->save();
        
        Session::flash('message', 'Your profile was successfully updated');
        return redirect('/home');
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
