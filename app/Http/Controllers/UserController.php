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
    public function __construct(){
        $this->middleware('auth');
    }
    
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
        //$model = User::find($id);
        $model = Ibo::find($id);
        $model->email = $request->email;
        $model->gender_id = $request->gender_id;
        $model->birth_date = $request->birth_date;
        $model->marital_status_id = $request->marital_status_id;
        $model->tin = $request->tin;
        $model->sss = $request->sss;
        $model->address = $request->address;
        $model->city = $request->city;
        $model->province = $request->province;
        $model->contact_no = $request->contact_no;
        $model->pickup_center_id = $request->pickup_center_id;
        $model->bank_id = $request->bank_id;
        $model->account_no = $request->account_no;
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
