<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\ProductAmount;
use App\Product;
use App\User;
use Session;

class ProductAmountController extends Controller
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
        $data = DB::table('product_amounts')->orderBy('id', 'desc')->paginate(15);
        return view('productamount.index', ['data'=>$data]);
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
        $data['users'] = User::pluck('name', 'id');
        return view('productamount.create', ['data'=>$data]);
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
        $model = new ProductAmount;
        $model->product_id = $request->product_id;
        $model->amount = $request->amount;
        $model->is_active = $request->has('is_active');
        $model->created_by = $request->created_by;
        $model->save();
        
        Session::flash('message', 'Product amount was successfully created');
        return redirect('/productamount');
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
        $data['info'] = ProductAmount::find($id);
        $data['products'] = Product::pluck('name', 'id');
        $data['users'] = User::pluck('name', 'id');
        return view('productamount.edit', ['data'=>$data]);
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
        $model = ProductAmount::find($id);
        $model->product_id = $request->product_id;
        $model->amount = $request->amount;
        $model->created_by = $request->created_by;
        $model->is_active = $request->has('is_active');
        $model->save();
        
        Session::flash('message', 'Product Amount was successfully updated');
        return redirect('/productamount');
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
