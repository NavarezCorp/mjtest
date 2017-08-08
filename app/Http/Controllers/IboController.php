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
use Carbon\Carbon;
use App\CommissionRecord;
use App\Commission;
use App\Matching;
use Illuminate\Support\Facades\Auth;

class IboController extends Controller {
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('role:admin,staff', ['only'=>['ibo_search']]);
    }
    
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
        $model->placement_position = strtoupper($request->placement_position);
        $model->total_purchase_amount = $request->total_purchase_amount;
        $model->ranking_lions_id = $request->ranking_lions_id;
        $model->is_admin = $request->has('is_admin');
        $model->activation_code = $request->activation_code;
        $model->activation_code_type = $request->activation_code_type;
        $model->email = $request->email;
        $model->gender_id = $request->gender;
        $model->birth_date = $request->birth_date;
        $model->marital_status_id = !empty($request->marital_status) ? $request->marital_status : null;
        $model->tin = $request->tin;
        $model->sss = $request->sss;
        $model->address = $request->address;
        $model->city = $request->city;
        $model->province = $request->province;
        $model->contact_no = $request->contact_no;
        $model->pickup_center_id = $request->pickup_center;
        $model->bank_id = !empty($request->bank_id) ? $request->bank_id : null;
        $model->account_no = $request->account_no;
        $model->registered_by = Auth::user()->ibo_id;
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
        //$data = Ibo::find($id);
        
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
        //$model->is_part_company = $request->has('is_part_company');
        //$model->sponsor_id = $request->sponsor_id;
        //$model->placement_id = $request->placement_id;
        //$model->placement_position = strtoupper($request->placement_position);
        $model->total_purchase_amount = $request->total_purchase_amount;
        $model->ranking_lions_id = $request->ranking_lions_id;
        //$model->is_admin = $request->has('is_admin');
        //$model->activation_code = $request->activation_code;
        //$model->activation_code_type = $request->activation_code_type;
        $model->email = $request->email;
        $model->gender_id = $request->gender;
        $model->birth_date = $request->birth_date;
        $model->marital_status_id = !empty($request->marital_status) ? $request->marital_status : null;
        $model->tin = $request->tin;
        $model->sss = $request->sss;
        $model->address = $request->address;
        $model->city = $request->city;
        $model->province = $request->province;
        $model->contact_no = $request->contact_no;
        $model->pickup_center_id = $request->pickup_center;
        $model->bank_id = !empty($request->bank_id) ? $request->bank_id : null;
        $model->account_no = $request->account_no;
        $model->save();
        
        $model = User::where('ibo_id', $id)->first();
        $model->name = $request->firstname . ' ' . $request->lastname;
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
    
    public function ibo_search(){
        return view('ibo.search');
    }
    
    public function search(){
        $data = null;
        
        if(!empty($_GET['ibo_id'])){
            $data['ibo']['info'][0] = Ibo::find($_GET['ibo_id']);
            $data['ibo']['info'][0]['ibo_id'] = sprintf('%09d', $_GET['ibo_id']);
            $data['ibo']['commissions'] = $this->ibo_commissions($_GET['ibo_id']);
            $data['ibo']['placement'] = Helper::check_placement($_GET['ibo_id']);
        }
        else if(!empty($_GET['name'])){
            $search_key = 'name';
            
            $res = User::where('name', 'like', '%' . $_GET['name'] . '%')->get();
            
            foreach($res as $key => $val){
                $data['ibo']['info'][$key] = Ibo::find($val->ibo_id);
                $data['ibo']['info'][$key]['ibo_id'] = sprintf('%09d', $val->ibo_id);
            }
        }
        else{
            $search_key = 'no key';
        }
        
        echo json_encode($data);
    }
    
    private function ibo_commissions($id){
        $data = null;
        $param_ = null;
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);
        
        for($i = $date_->weekOfYear; $i >= 1; $i--){
            $data['commission'][$i]['date_start'] = $date_->startOfWeek()->format('F j, Y');
            $data['commission'][$i]['date_end'] = $date_->endOfWeek()->format('F j, Y');

            $param_['id'] = $id;
            $param_['start_date'] = $date_->startOfWeek()->toDateTimeString();
            $param_['end_date'] = $date_->endOfWeek()->toDateTimeString();

            $direct_count = 0;

            $res = Ibo::where('sponsor_id', $id)
                ->where('activation_code_type', '!=', 'FS')
                ->where('activation_code_type', '!=', 'CD')
                ->whereBetween('created_at', [$date_->startOfWeek()->toDateTimeString(), $date_->endOfWeek()->toDateTimeString()])
                ->orderBy('created_at', 'desc')->get();

            $direct_count = count($res);

            $data['commission'][$i]['direct'] = $direct_count * Commission::where('name', 'Direct Sponsor Commission')->first()->amount;

            $indirect_ = CommissionRecord::where('sponsor_id', $id)
                ->where('commission_type_id', 2)
                ->whereBetween('created_at', [$date_->startOfWeek()->toDateTimeString(), $date_->endOfWeek()->toDateTimeString()])
                ->orderBy('created_at', 'desc')->get();
            
            $data['commission'][$i]['indirect'] = 0;
            $data['commission'][$i]['matching'] = $this->get_matching_bonus($param_);
            $data['commission'][$i]['fifth_pair'] = $this->get_fifth_pair($param_);

            $data['commission'][$i]['fifth_pairs'] = $data['commission'][$i]['fifth_pair'] * Commission::where('name', 'Matching Bonus')->first()->amount;
            $data['commission'][$i]['matching'] = $data['commission'][$i]['matching'] * Commission::where('name', 'Matching Bonus')->first()->amount - $data['commission'][$i]['fifth_pairs'];
            $data['commission'][$i]['gross'] = ($data['commission'][$i]['direct'] + $data['commission'][$i]['indirect'] + $data['commission'][$i]['matching']);
            $data['commission'][$i]['tax'] = $data['commission'][$i]['matching'] * .1;
            $data['commission'][$i]['net_commission'] = $data['commission'][$i]['gross'] - $data['commission'][$i]['tax'];

            $date_->subWeek();
        }
        
        return $data;
    }
    
    private function get_matching_bonus($param){
        $res = Matching::where('ibo_id', $param['id'])
            ->whereBetween('datetime_matched', [$param['start_date'], $param['end_date']])
            ->get();
        
        return count($res);
    }
    
    private function get_fifth_pair($param){
        $res = Matching::where('ibo_id', $param['id'])
            ->whereBetween('datetime_matched', [$param['start_date'], $param['end_date']])
            ->whereRaw('counter % 5 = 0')
            ->get();
        
        return count($res);
    }

    public function process_app($id){
        if($id == 'all'){
            $ibos = DB::table('ibos')->select('id')->orderBy('created_at', 'asc')->get();

            foreach($ibos as $value){
                $model = Ibo::find($value->id);
                $model->app = Helper::get_app($value->id);
                $model->save();
            }
        }
        else{
            $model = Ibo::find($id);
            $model->app = Helper::get_app($id);
            $model->save();
        }
        
        echo json_encode('app done');
    }
    
    public function process_agp($id){
        if($id == 'all'){
            $ibos = DB::table('ibos')->select('id')->orderBy('created_at', 'asc')->get();

            foreach($ibos as $value){
                $model = Ibo::find($value->id);
                $model->agp = Helper::get_agp($value->id);
                $model->save();
            }
        }
        else{
            $model = Ibo::find($id);
            $model->agp = Helper::get_agp($id);
            $model->save();
        }
        
        echo json_encode('agp done');
    }
    
    public function app_agp(){
        $data['list'] = DB::table('ibos')
            ->orderBy('firstname', 'asc')
            ->orderBy('middlename', 'asc')
            ->orderBy('lastname', 'asc')
            ->paginate(15);
        
        return view('ibo.appagp', compact('data'));
    }
    
    public function appagp_search(){
        $data = null;
        
        $data = $_GET;
        
        if(!empty($_GET['ibo_id'])) $data['list'] = Ibo::where('id', intval($_GET['ibo_id']))->get();
        else if(!empty($_GET['name'])){
            $res = User::whereRaw('name like "%' . $_GET['name'] . '%"')
                ->orderBy('name', 'asc')
                ->get();
            
            foreach($res as $key => $val) $data['list'][] = Ibo::find($val->ibo_id);
        }
        
        return view('ibo.appagp', compact('data'));
    }
}
