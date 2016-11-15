<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Product;
use App\ProductCode;
use App\User;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Str;
use App\Logger;
use DB;
use Carbon\Carbon;

class ProductCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['product_codes'] = DB::table('product_codes')->orderBy('created_at', 'desc')->paginate(15);
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
        $howmanychar = 8;
        $howmanycode = $_GET['howmanyproducts'];
        
        for($i = 0; $i < $howmanycode; $i++){
            $model = new ProductCode;
            $model->code = encrypt(Str::upper($this->sfi_get_code($howmanychar)));
            $model->product_id = $_GET['product_id'];
            $model->created_by = Auth::user()->ibo_id;
            
            switch($_GET['transfer_to']){
                case 'pc':
                    $model->assigned_to_pc_ibo_id = $_GET['ibo_id'];
                    $model->datetime_assigned_to_pc = Carbon::now();
                    $model->assigned_to_pc_creator = Auth::user()->ibo_id;
                    break;

                case 'ms':
                    $model->assigned_to_ms_ibo_id = $_GET['ibo_id'];
                    $model->datetime_assigned_to_ms = Carbon::now();
                    $model->assigned_to_ms_creator = Auth::user()->ibo_id;
                    break;
            }
            
            $model->save();
        }
        
        return json_encode(ProductCode::get());
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
            $pdf = PDF::loadView('productcode.print', ['data'=>$data]);
            $pdf->setPaper('a4', 'landscape');
            return $pdf->stream();
        }
    }
}
