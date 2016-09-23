<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Ibo;
use DB;

class GenealogyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['ibo_id'] = $_GET['ibo_id'];
        return view('genealogy.index', ['data'=>$data]);
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
        $data = $this->get_genealogy($id);
        
        foreach($data['children'] as $pkey => $pvalue){
            $data['children'][$pkey]['children'] = $this->get_children($pvalue['name']);
            
            if($data['children'][$pkey]['children']){
                foreach($data['children'][$pkey]['children'] as $ckey => $cvalue){
                    $data['children'][$pkey]['children'][$ckey]['children'] = $this->get_children($cvalue['name']);
                }
            }
            else{
                $data['children'][$pkey]['children'][] = ['name'=>'', 'title'=>''];
                $data['children'][$pkey]['children'][] = ['name'=>'', 'title'=>''];
            }
        }
        
        return $data;
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
    
    public function get_genealogy($id){
        $data = null;
        $children = null;
        
        $res_parent = DB::table('ibos')
            ->select('id', 'firstname', 'middlename', 'lastname', 'placement_position')
            ->where('id', $id)
            ->first();
        
        $res_children = DB::table('ibos')
            ->select('id', 'firstname', 'middlename', 'lastname', 'placement_position')
            ->where('placement_id', $res_parent->id)
            ->get();
        
        
        foreach($res_children as $value){
            if($value->placement_position == 'L'){
                $children[] = [
                    'name'=>$value->id,
                    'title'=>$value->firstname . ' ' . $value->middlename . ' ' . $value->lastname
                ];
            }

            if($value->placement_position == 'R'){
                $children[] = [
                    'name'=>$value->id,
                    'title'=>$value->firstname . ' ' . $value->middlename . ' ' . $value->lastname
                ];
            }
        }
        
        $data = [
            'name'=>$res_parent->id,
            'title'=>$res_parent->firstname . ' ' . $res_parent->middlename . ' ' . $res_parent->lastname,
            'children'=>$children
        ];
        
        return $data;
    }
    
    public function get_children($id){
        $data = null;
        
        $res_children = DB::table('ibos')
            ->select('id', 'firstname', 'middlename', 'lastname', 'placement_position')
            ->where('placement_id', $id)
            ->get();
        
        
        foreach($res_children as $value){
            if($value->placement_position == 'L'){
                $data[] = [
                    'name'=>$value->id,
                    'title'=>$value->firstname . ' ' . $value->middlename . ' ' . $value->lastname
                ];
            }

            if($value->placement_position == 'R'){
                $data[] = [
                    'name'=>$value->id,
                    'title'=>$value->firstname . ' ' . $value->middlename . ' ' . $value->lastname
                ];
            }
        }
        
        return $data;
    }
}
