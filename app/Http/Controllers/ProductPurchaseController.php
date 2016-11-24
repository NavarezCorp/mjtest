<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\ProductPurchase;
use App\Product;
use App\ProductAmount;
use Session;
use Auth;
use App\ProductCode;
use App\Logger;

class ProductPurchaseController extends Controller
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
        $data = DB::table('product_purchases')->orderBy('id', 'desc')->paginate(15);
        return view('productpurchase.index', ['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data['products'] = Product::pluck('name', 'id');
        $data['user_ibo_id'] = Auth::user()->ibo_id;
        
        return view('productpurchase.create', ['data'=>$data]);
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
        //foreach($request->product_id as $value){
        foreach($request->product_code as $key => $value){
            if(!empty($value)){
                $res = ProductCode::find($request->pcid[$key]);
                
                $model = new ProductPurchase;
                $model->ibo_id = $request->ibo_id;
                $model->product_id = $res['product_id'];
                $model->product_code_id = $res['id'];
                $model->purchase_amount = ProductAmount::where('product_id', $res['product_id'])->where('is_active', true)->first()->amount;
                $model->save();
                
                $model = ProductCode::find($res['id']);
                $model->assigned_to_dealer_ibo_id = $request->ibo_id;
                $model->datetime_assigned_to_dealer = date('Y-m-d H:i');
                $model->assigned_to_dealer_creator = Auth::user()->ibo_id;
                $model->save();
            }
        }
        
        Session::flash('message', 'Product purchase of "' . $request->ibo_id . '" was successfully saved');
        return redirect('/productpurchase');
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
