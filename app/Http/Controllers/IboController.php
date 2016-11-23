<?php
namespace App\Http\Controllers;

set_time_limit(0);

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Ibo;
use Session;
use App\User;
use App\ActivationCode;
use Illuminate\Validation\Rule;
use App\Helper;
use App\Bank;
use App\PickupCenter;
use App\Country;
use App\City;
use App\Logger;
use App\MaritalStatus;
use App\Gender;

class IboController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //
        $data = DB::table('ibos')->orderBy('id', 'asc')->paginate(15);
        return view('ibo.index', ['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
        $data['placement_id'] = !empty($_GET['placement_id']) ? $_GET['placement_id'] : '';
        $data['placement_position'] = !empty($_GET['placement_position']) ? $_GET['placement_position'] : '';
        $data['banks'] = Bank::pluck('name', 'id');
        $data['marital_status'] = MaritalStatus::pluck('name', 'id');
        $data['genders'] = Gender::pluck('name', 'id');
        
        $pickup_centers = PickupCenter::get();
        
        foreach($pickup_centers as $value){
            $country = Country::find($value->country_id)->name;
            $city = City::find($value->city_id)->name;
            
            $data['pickup_centers'][$country][$value->id] = $city . ' (' . $value->branch . ')';
        }
        
        return view('ibo.create', ['data'=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
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
        $model->activation_code = $request->activation_code;
        $model->activation_code_type = $request->activation_code_type;
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
        
        $model = new User;
        $model->name = $request->firstname . ' ' . $request->middlename . ' ' . $request->lastname;
        $model->email = sprintf('%09d', $new_id) . '@gmail.com';
        $model->password = bcrypt('123456');
        $model->ibo_id = $new_id;
        $model->remember_token = $model->getRememberToken();
        $model->save();
        
        $model = ActivationCode::find($request->cid);
        $model->datetime_used = date('Y-m-d H:i');
        $model->used_by_ibo_id = $new_id;
        $model->printed = true;
        $model->save();
        
        Helper::process_commission($new_id);
        
        Session::flash('message', 'IBO ' . sprintf('%09d', $new_id) . ' named "' . $request->firstname . ' ' . $request->middlename . ' ' . $request->lastname . '" was successfully created');
        //return redirect('/ibo');
        return view('ibo.show');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
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
    public function update(Request $request, $id){
        //
        $model = Ibo::find($id);
        $model->firstname = $request->firstname;
        $model->middlename = $request->middlename;
        $model->lastname = $request->lastname;
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
    public function destroy($id){
        //
    }
}
