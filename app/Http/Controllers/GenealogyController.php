<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Ibo;
use Carbon\Carbon;
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
        $data['sponsor_id'] = $_GET['sponsor_id'];
        $data['placement_id'] = $_GET['placement_id'];
        $data['left_counter'] = $this->get_downlines(['id'=>$_GET['sponsor_id'], 'position'=>'L']);
        $data['right_counter'] = $this->get_downlines(['id'=>$_GET['sponsor_id'], 'position'=>'R']);
        
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
        
        if($data['children']){
            for($a=0; $a<2; $a++){
                if(!empty($data['children'][$a])){
                    $data['children'][$a]['children'] = $this->get_children($data['children'][$a]['name']);
                
                    if($data['children'][$a]['children']){
                        for($b=0; $b<2; $b++){
                            if(isset($data['children'][$a]['children'][$b])){
                                $data['children'][$a]['children'][$b]['children'] = $this->get_children($data['children'][$a]['children'][$b]['name']);

                                if(!$data['children'][$a]['children'][$b]['children']){
                                    $data['children'][$a]['children'][$b]['children'][] = ['id'=>'', 'name'=>'', 'title'=>''];
                                    $data['children'][$a]['children'][$b]['children'][] = ['id'=>'', 'name'=>'', 'title'=>''];
                                }
                            }
                            else{
                                $placement_position = ($a == 1) ? 'R' : 'L';
                                $data['children'][$a]['children'][$b]['id'] = $data['children'][$a]['name'] . '|' . $placement_position;
                                $data['children'][$a]['children'][$b]['name'] = '';
                                $data['children'][$a]['children'][$b]['title'] = '';
                                $data['children'][$a]['children'][$b]['children'][] = ['id'=>'', 'name'=>'', 'title'=>''];
                                $data['children'][$a]['children'][$b]['children'][] = ['id'=>'', 'name'=>'', 'title'=>''];
                            }
                        }
                    }
                    else{
                        for($b=0; $b<2; $b++){
                            $data['children'][$a]['children'][$b] = ['id'=>'', 'name'=>'', 'title'=>''];

                            for($c=0; $c<2; $c++) $data['children'][$a]['children'][$b]['children'][$c] = ['id'=>'', 'name'=>'', 'title'=>''];
                        }
                    }
                }
                else{
                    //print_r($data);
                    $placement_position = ($a == 1) ? 'R' : 'L';
                    
                    $data['children'][$a] = ['id'=>$data['name'] . '|' . $placement_position, 'name'=>'', 'title'=>''];
                    
                    for($b=0; $b<2; $b++){
                        $data['children'][$a]['children'][$b] = ['id'=>'', 'name'=>'', 'title'=>''];

                        for($c=0; $c<2; $c++) $data['children'][$a]['children'][$b]['children'][$c] = ['id'=>'', 'name'=>'', 'title'=>''];
                    }
                }
            }
        }
        else{
            for($a=0; $a<2; $a++){
                $data['children'][$a] = ['id'=>'test', 'name'=>'', 'title'=>''];
                
                for($b=0; $b<2; $b++){
                    $data['children'][$a]['children'][$b] = ['id'=>'', 'name'=>'', 'title'=>''];
                    
                    for($c=0; $c<2; $c++) $data['children'][$a]['children'][$b]['children'][$c] = ['id'=>'', 'name'=>'', 'title'=>''];
                }
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
            ->select('id', 'firstname', 'middlename', 'lastname', 'placement_position', 'placement_id')
            ->where('id', $id)
            ->first();
        
        $res_children = DB::table('ibos')
            ->select('id', 'firstname', 'middlename', 'lastname', 'placement_position', 'placement_id')
            ->where('placement_id', $res_parent->id)
            ->get();
        
        if($res_children->count()){
            foreach($res_children as $value){
                if($value->placement_position == 'L'){
                    $children[] = [
                        'id'=>$value->placement_id,
                        'name'=>$value->id,
                        'title'=>$value->firstname . ' ' . $value->middlename . ' ' . $value->lastname
                    ];
                }

                if($value->placement_position == 'R'){
                    $children[] = [
                        'id'=>$value->placement_id,
                        'name'=>$value->id,
                        'title'=>$value->firstname . ' ' . $value->middlename . ' ' . $value->lastname
                    ];
                }
            }
        }
        else{
            $children[] = ['id'=>$res_parent->id . '|L', 'name'=>'', 'title'=>''];
            $children[] = ['id'=>$res_parent->id . '|R', 'name'=>'', 'title'=>''];
        }
        
        $data = [
            'id'=>$res_parent->placement_id,
            'name'=>$res_parent->id,
            'title'=>$res_parent->firstname . ' ' . $res_parent->middlename . ' ' . $res_parent->lastname,
            'children'=>$children
        ];
        
        return $data;
    }
    
    public function get_children($id){
        $data = null;
        
        $res_children = DB::table('ibos')
            ->select('id', 'firstname', 'middlename', 'lastname', 'placement_position', 'placement_id')
            ->where('placement_id', $id)
            ->get();
        
        if($res_children->count()){
            foreach($res_children as $value){
                if($value->placement_position == 'L'){
                    $data[] = [
                        'id'=>$value->placement_id,
                        'name'=>$value->id,
                        'title'=>$value->firstname . ' ' . $value->middlename . ' ' . $value->lastname
                    ];
                }

                if($value->placement_position == 'R'){
                    $data[] = [
                        'id'=>$value->placement_id,
                        'name'=>$value->id,
                        'title'=>$value->firstname . ' ' . $value->middlename . ' ' . $value->lastname
                    ];
                }
            }
        }
        else{
            $data[] = ['id'=>$id . '|L', 'name'=>'', 'title'=>''];
            $data[] = ['id'=>$id . '|R', 'name'=>'', 'title'=>''];
        }
        
        return $data;
    }
    
    public function get_downlines($param){
        $counter = 0;
        $ids = null;
        
        $res = Ibo::where('placement_id', $param['id'])->get();
        
        switch($param['position']){
            case 'L':
                if(!empty($res) && !empty($res[0])){
                    $counter = 1;
                    $ids = $this->fetcher_(['id'=>$res[0]['id']]);
                }
                
                break;
                
            case 'R':
                if(!empty($res) && !empty($res[1])){
                    $counter = 1;
                    $ids = $this->fetcher_(['id'=>$res[1]['id']]);
                }
                
                break;
        }
        
        while(!empty($ids)){
            $temp = null;
            $counter += count($ids);

            foreach($ids as $value){
                $res = $this->fetcher_(['id'=>$value]);

                if(!empty($res)) foreach($res as $val) $temp[] = $val;
            }

            $ids = $temp;
        }
        
        return $counter;
    }
    
    public function fetcher_($param){
        $data = null;
        
        $res = Ibo::where('placement_id', $param['id'])->get();
        
        foreach($res as $value) $data[] = $value->id;
        
        return $data;
    }
}
