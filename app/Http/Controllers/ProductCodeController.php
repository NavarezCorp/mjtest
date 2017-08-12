<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Product;
use App\ProductCode;
use App\User;
use Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Str;
use App\Logger;
use DB;
use Carbon\Carbon;
use App\Ibo;

class ProductCodeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('role:admin,staff-1', ['except'=>['check_product_code']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        session()->forget('product_codes_to_be_printed');
        
        $data['products'] = Product::pluck('name', 'id');
        
        return view('productcode.index', ['data'=>$data]);
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
    
    public function get_product_code(){
        $data = null;
        $howmanychar = 8;
        $howmanycode = $_GET['howmanyproducts'];
        
        for($i = 0; $i < $howmanycode; $i++){
            $model = new ProductCode;
            $model->code = encrypt(Str::upper($this->sfi_get_code($howmanychar)));
            $model->product_id = $_GET['product_id'];
            $model->created_by = Auth::user()->ibo_id;
            $model->assigned_to_pc_ibo_id = $_GET['ibo_id'];
            $model->datetime_assigned_to_pc = Carbon::now();
            $model->assigned_to_pc_creator = Auth::user()->ibo_id;
            $model->save();
            
            $data['new_product_code'][$i]['id'] = $model->id;
            $data['new_product_code'][$i]['code'] = decrypt($model->code);
            $data['new_product_code'][$i]['product'] = Product::find($model->product_id)->name;
            
            $created_by = Ibo::find($model->created_by);
            $data['new_product_code'][$i]['created_by'] = $created_by->firstname . ' ' . $created_by->middlename . ' ' . $created_by->lastname;
            
            $assigned_to_pc_ibo_id = Ibo::find($model->assigned_to_pc_ibo_id);
            $user_type = User::where('ibo_id', $model->assigned_to_pc_ibo_id)->first();
            $user_type_str = !empty($user_type->role) ? $user_type->role : 'Dealer';
            $data['new_product_code'][$i]['transfered_to'] = $assigned_to_pc_ibo_id->firstname . ' ' . $assigned_to_pc_ibo_id->middlename . ' ' . $assigned_to_pc_ibo_id->lastname . '<br>(' . sprintf('%09d', $model->assigned_to_pc_ibo_id) . ') ' . $user_type_str;
            
            $data['new_product_code'][$i]['datetime_transfered'] = Carbon::parse($model->created_at)->toDateTimeString();
            
            session()->push('product_codes_to_be_printed', $data['new_product_code'][$i]);
        }
        
        foreach(ProductCode::get() as $value){
            $transfered_to = Ibo::find($value->assigned_to_pc_ibo_id);
            $user_type = User::where('ibo_id', $value->assigned_to_pc_ibo_id)->first();
            
            $user_type_str = !empty($user_type->role) ? $user_type->role : 'Ordinary';
            
            $value->transfered_to = $transfered_to->firstname . ' ' . $transfered_to->middlename . ' ' . $transfered_to->lastname . '<br>(' . sprintf('%09d', $value->assigned_to_pc_ibo_id) . ') ' . $user_type_str;
            
            $data['all_product_code'][] = $value;
        }
        
        return json_encode($data);
    }
    
    private function sfi_get_code($length){
        $randomData = base64_encode(file_get_contents('/dev/urandom', false, null, 0, $length) . uniqid(mt_rand(), true));
        return substr(strtr($randomData, array('/' => '', '=' => '', '+' => '')), 0, $length);
    }
    
    public function check_product_code(){
        $data = null;
        
        $model = ProductCode::all();
        
        foreach($model as $value){
            if(decrypt($value->code) == $_GET['code']){
                $data['code_to_check'] = $_GET['code'];
                $data['id'] = $value->id;
                $data['product'] = Product::find($value->product_id);
                $data['datetime_used'] = !empty($value->datetime_assigned_to_dealer) ? $value->datetime_assigned_to_dealer : null;
                
                break;
            }
        }
        
        return json_encode($data);
    }
    
    public function print_product_codes($type){
        if($type == 'all'){
            $pdf = PDF::loadView('productcode.print', ['data'=>session()->get('all_product_codes_to_be_printed')]);
            $pdf->setPaper('a4', 'landscape');
        }
        else{
            $pdf = PDF::loadView('productcode.print', ['data'=>session()->get('product_codes_to_be_printed')]);
            $pdf->setPaper('a4', 'landscape');
        }
        
        return $pdf->stream();
    }
    
    public function get_all_product_codes(){
        session()->forget('all_product_codes_to_be_printed');
        
        $data['where']['assigned_to_pc_ibo_id'] = 0;
        $data['where']['product_id'] = 0;
        $data['disable_print'] = 'link-disabled';
        
        if(!empty($_GET['transfered_to']) || !empty($_GET['product_id'])){
            if(!empty($_GET['transfered_to'])){
                $data['where']['assigned_to_pc_ibo_id'] = $_GET['transfered_to'];
                $data_['where']['assigned_to_pc_ibo_id'] = $_GET['transfered_to'];
            }
            
            if(!empty($_GET['product_id'])){
                $data['where']['product_id'] = $_GET['product_id'];
                $data_['where']['product_id'] = $_GET['product_id'];
            }
            
            $data['disable_print'] = '';
            
            $data['product_codes'] = DB::table('product_codes')
                ->where($data_['where'])
                ->orderBy('id', 'desc')
                ->paginate(15);
            
            $res = DB::table('product_codes')->where($data_['where'])->orderBy('id', 'desc')->get();
            
            foreach($res as $key => $value){
                //$data['all_product_codes_to_be_printed'][] = (array)$value;
                $data['all_product_codes_to_be_printed'][$key]['id'] = $value->id;
                $data['all_product_codes_to_be_printed'][$key]['code'] = decrypt($value->code);
                $data['all_product_codes_to_be_printed'][$key]['product'] = Product::find($value->product_id)->name;

                $created_by = Ibo::find($value->created_by);
                $data['all_product_codes_to_be_printed'][$key]['created_by'] = $created_by->firstname . ' ' . $created_by->middlename . ' ' . $created_by->lastname;

                $assigned_to_pc_ibo_id = Ibo::find($value->assigned_to_pc_ibo_id);
                $user_type = User::where('ibo_id', $value->assigned_to_pc_ibo_id)->first();
                $user_type_str = !empty($user_type->role) ? $user_type->role : 'Dealer';
                $data['all_product_codes_to_be_printed'][$key]['transfered_to'] = $assigned_to_pc_ibo_id->firstname . ' ' . $assigned_to_pc_ibo_id->middlename . ' ' . $assigned_to_pc_ibo_id->lastname . '<br>(' . sprintf('%09d', $value->assigned_to_pc_ibo_id) . ') ' . $user_type_str;

                $data['all_product_codes_to_be_printed'][$key]['datetime_transfered'] = Carbon::parse($value->created_at)->toDateTimeString();
                $data['all_product_codes_to_be_printed'][$key]['assigned_to_dealer_ibo_id'] = $value->assigned_to_dealer_ibo_id;
                
                //session()->put('all_product_codes_to_be_printed', $data['all_product_codes_to_be_printed']);
                session()->push('all_product_codes_to_be_printed', $data['all_product_codes_to_be_printed'][$key]);
            }
        }
        else{
            $data['product_codes'] = DB::table('product_codes')->orderBy('id', 'desc')->paginate(15);
            /*
            $res = DB::table('product_codes')->orderBy('id', 'desc')->get();
            foreach($res as $value) $data['all_product_codes_to_be_printed'][] = (array)$value;
            session()->put('all_product_codes_to_be_printed', DB::table('product_codes')->orderBy('id', 'desc')->get());
            */
        }
        
        $data['products'] = Product::pluck('name', 'id');
        $data['ibos'] = DB::table('ibos')->select('id')->orderBy('created_at', 'asc')->get();
        
        return view('productcode.all', ['data'=>$data]);
    }
}
