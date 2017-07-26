<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper;
use App\Logger;

class ParticularController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = null;
        
        if(isset($_GET['id'])){
            $param['id'] = $_GET['id'];
            $param['from'] = date('Y-m-d', strtotime($_GET['from']));
            $param['to'] = date('Y-m-d', strtotime($_GET['to']));
            
            $res = Helper::get_matched($param);
            
            $data['name'] = Helper::get_ibo_name($_GET['id']);
            $data['ibo_id'] = sprintf('%09d', $_GET['id']);
            $data['from'] = $_GET['from'];
            $data['to'] =  $_GET['to'];
            
            foreach($res as $key => $val){
                $param = [
                    'id'=>$_GET['id'],
                    'from'=>$val->date,
                    'to'=>$val->date
                ];
                
                $fifth_amount = Helper::get_fifth_pair($param);
                $new_entry = Helper::get_new_entry($param);
                
                $data['particulars'][$key]['date'] = $val->date;
                $data['particulars'][$key]['left']['ow'] = 0;
                $data['particulars'][$key]['left']['nw'] = 0;
                $data['particulars'][$key]['left']['ne'] = count($new_entry['new_left']);
                $data['particulars'][$key]['right']['ow'] = 0;
                $data['particulars'][$key]['right']['nw'] = 0;
                $data['particulars'][$key]['right']['ne'] = count($new_entry['new_right']);;
                $data['particulars'][$key]['match'] = $val->amount - $fifth_amount;
                $data['particulars'][$key]['fifth'] = $fifth_amount;
                
                $tax = $data['particulars'][$key]['match'] * .1;
                
                $data['particulars'][$key]['amount'] = $data['particulars'][$key]['match'] - $tax;
            }
        }
        //dd($data);
        return view('particular.index', ['data'=>$data]);
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
        echo 'show';
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