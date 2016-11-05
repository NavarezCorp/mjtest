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
        //$data['left_counter'] = $this->get_downlines(['id'=>$_GET['sponsor_id'], 'position'=>'L']);
        //$data['right_counter'] = $this->get_downlines(['id'=>$_GET['sponsor_id'], 'position'=>'R']);
        $data['counter'] = $this->get_downlines(['id'=>$_GET['sponsor_id']]);
        $data['waiting'] = $this->get_waiting(['id'=>$_GET['sponsor_id']]);
        
        //print_r($data['waiting']); die();
        
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
                            if(!empty($data['children'][$a]['children'][$b])){
                                $data['children'][$a]['children'][$b]['children'] = $this->get_children($data['children'][$a]['children'][$b]['name']);

                                if(empty($data['children'][$a]['children'][$b]['children'][0])){
                                    $data['children'][$a]['children'][$b]['children'][0] = [
                                        'id'=>$data['children'][$a]['children'][$b]['name'] . '|L',
                                        'name'=>'',
                                        'title'=>''
                                    ];
                                }
                                
                                if(empty($data['children'][$a]['children'][$b]['children'][1])){
                                    $data['children'][$a]['children'][$b]['children'][1] = [
                                        'id'=>$data['children'][$a]['children'][$b]['name'] . '|R',
                                        'name'=>'',
                                        'title'=>''
                                    ];
                                }
                            }
                            else{
                                $placement_position = ($b == 1) ? 'R' : 'L';
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
                $data['children'][$a] = ['id'=>'', 'name'=>'', 'title'=>''];
                
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
        if(!$id) return;
            
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
        
        switch($res_children->count()){
            case 0:
                $children[] = ['id'=>$res_parent->id . '|L', 'name'=>'', 'title'=>''];
                $children[] = ['id'=>$res_parent->id . '|R', 'name'=>'', 'title'=>''];
                break;
            
            case 1:
                switch($res_children[0]->placement_position){
                    case 'L':
                        $children[] = [
                            'id'=>$res_children[0]->placement_id,
                            'name'=>sprintf('%09d', $res_children[0]->id),
                            'title'=>$res_children[0]->firstname . ' ' . $res_children[0]->middlename . ' ' . $res_children[0]->lastname
                        ];
                        
                        $children[] = [
                            'id'=>$res_children[0]->placement_id . '|R',
                            'name'=>'',
                            'title'=>''
                        ];
                        
                        break;
                    
                    case 'R':
                        $children[] = [
                            'id'=>$res_children[0]->placement_id . '|L',
                            'name'=>'',
                            'title'=>''
                        ];
                        
                        $children[] = [
                            'id'=>$res_children[0]->placement_id,
                            'name'=>sprintf('%09d', $res_children[0]->id),
                            'title'=>$res_children[0]->firstname . ' ' . $res_children[0]->middlename . ' ' . $res_children[0]->lastname
                        ];
                        break;
                }
                
                break;
            
            case 2:
                if($res_children[0]->placement_position == 'L'){
                    $children[] = [
                        'id'=>$res_children[0]->placement_position,
                        'name'=>sprintf('%09d', $res_children[0]->id),
                        'title'=>$res_children[0]->firstname . ' ' . $res_children[0]->middlename . ' ' . $res_children[0]->lastname
                    ];
                    
                    $children[] = [
                        'id'=>$res_children[1]->placement_position,
                        'name'=>sprintf('%09d', $res_children[1]->id),
                        'title'=>$res_children[1]->firstname . ' ' . $res_children[1]->middlename . ' ' . $res_children[1]->lastname
                    ];
                }
                else{
                    $children[] = [
                        'id'=>$res_children[1]->placement_position,
                        'name'=>sprintf('%09d', $res_children[1]->id),
                        'title'=>$res_children[1]->firstname . ' ' . $res_children[1]->middlename . ' ' . $res_children[1]->lastname
                    ];
                    
                    $children[] = [
                        'id'=>$res_children[0]->placement_position,
                        'name'=>sprintf('%09d', $res_children[0]->id),
                        'title'=>$res_children[0]->firstname . ' ' . $res_children[0]->middlename . ' ' . $res_children[0]->lastname
                    ];
                }
                
                break;
        }
        
        $data = [
            'id'=>$res_parent->placement_id,
            'name'=>sprintf('%09d', $res_parent->id),
            'title'=>$res_parent->firstname . ' ' . $res_parent->middlename . ' ' . $res_parent->lastname,
            'children'=>$children
        ];
        //var_dump($data); die();
        return $data;
    }
    
    public function get_children($id){
        $data = null;
        
        $res_children = DB::table('ibos')
            ->select('id', 'firstname', 'middlename', 'lastname', 'placement_position', 'placement_id')
            ->where('placement_id', $id)
            ->get();
        
        /*
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
        */
        
        switch($res_children->count()){
            case 0:
                $data[] = ['id'=>$id . '|L', 'name'=>'', 'title'=>''];
                $data[] = ['id'=>$id . '|R', 'name'=>'', 'title'=>''];
                break;
            
            case 1:
                switch($res_children[0]->placement_position){
                    case 'L':
                        $data[0] = [
                            'id'=>$res_children[0]->placement_id,
                            'name'=>sprintf('%09d', $res_children[0]->id),
                            'title'=>$res_children[0]->firstname . ' ' . $res_children[0]->middlename . ' ' . $res_children[0]->lastname
                        ];
                        
                        $data[1] = [
                            'id'=>$res_children[0]->placement_id . '|R',
                            'name'=>'',
                            'title'=>''
                        ];
                        
                        break;
                    
                    case 'R':
                        $data[0] = [
                            'id'=>$res_children[0]->placement_id . '|L',
                            'name'=>'',
                            'title'=>''
                        ];
                        
                        $data[1] = [
                            'id'=>$res_children[0]->placement_id,
                            'name'=>sprintf('%09d', $res_children[0]->id),
                            'title'=>$res_children[0]->firstname . ' ' . $res_children[0]->middlename . ' ' . $res_children[0]->lastname
                        ];
                        break;
                }
                
                break;
            
            case 2:
                /*
                foreach($res_children as $value){
                    if($value->placement_position == 'L'){
                        $data[0] = [
                            'id'=>$value->placement_id,
                            'name'=>sprintf('%09d', $value->id),
                            'title'=>$value->firstname . ' ' . $value->middlename . ' ' . $value->lastname
                        ];
                    }

                    if($value->placement_position == 'R'){
                        $data[1] = [
                            'id'=>$value->placement_id,
                            'name'=>sprintf('%09d', $value->id),
                            'title'=>$value->firstname . ' ' . $value->middlename . ' ' . $value->lastname
                        ];
                    }
                }
                */
                //foreach($res_children as $key => $value){
                    if($res_children[0]->placement_position == 'L'){
                        $data[] = [
                            'id'=>$res_children[0]->placement_position,
                            'name'=>sprintf('%09d', $res_children[0]->id),
                            'title'=>$res_children[0]->firstname . ' ' . $res_children[0]->middlename . ' ' . $res_children[0]->lastname
                        ];

                        $data[] = [
                            'id'=>$res_children[1]->placement_position,
                            'name'=>sprintf('%09d', $res_children[1]->id),
                            'title'=>$res_children[1]->firstname . ' ' . $res_children[1]->middlename . ' ' . $res_children[1]->lastname
                        ];
                    }
                    else{
                        $data[] = [
                            'id'=>$res_children[1]->placement_position,
                            'name'=>sprintf('%09d', $res_children[1]->id),
                            'title'=>$res_children[1]->firstname . ' ' . $res_children[1]->middlename . ' ' . $res_children[1]->lastname
                        ];

                        $data[] = [
                            'id'=>$res_children[0]->placement_position,
                            'name'=>sprintf('%09d', $res_children[0]->id),
                            'title'=>$res_children[0]->firstname . ' ' . $res_children[0]->middlename . ' ' . $res_children[0]->lastname
                        ];
                    }
                //}
                break;
        }
        
        return $data;
    }
    
    public function get_downlines($param){
        $counter = 0;
        $ids = null;
        $data['left'] = 0;
        $data['right'] = 0;
        $position_str = null;
        
        $res = Ibo::where('placement_id', $param['id'])->get();
        
        if(!empty($res)){
            foreach($res as $value){
                switch($value->placement_position){
                    case 'L':
                        $position_str = 'left';
                        break;

                    case 'R':
                        $position_str = 'right';
                        break;
                }

                $counter = 1;
                $ids = $this->fetcher_(['id'=>$value->id]);

                while(!empty($ids)){
                    $temp = null;
                    $counter += count($ids);

                    foreach($ids as $value_){
                        $res = $this->fetcher_(['id'=>$value_]);

                        if(!empty($res)) foreach($res as $val) $temp[] = $val;
                    }

                    $ids = $temp;
                }

                $data[$position_str] = $counter;
            }
        }
        
        return $data;
    }
    
    public function get_waiting($param){
        $counter = 0;
        $ids = null;
        $data['left'] = 0;
        $data['right'] = 0;
        $position_str = null;
        $not_in = ['FS', 'CD'];
        
        $res = $this->fetcherEx_($param);
        
        if(!empty($res)){
            foreach($res as $value){
                $counter = 0;
                
                switch($value['attributes']['placement_position']){
                    case 'L':
                        $position_str = 'left';
                        break;

                    case 'R':
                        $position_str = 'right';
                        break;
                }
                
                if(!in_array($value['attributes']['activation_code_type'], $not_in)) $counter++;
                
                $ids = $this->fetcherEx_(['id'=>$value['attributes']['id']]);
                
                while(!empty($ids)){
                    $temp = null;

                    foreach($ids as $value_){
                        $res = $this->fetcherEx_(['id'=>$value_['attributes']['id']]);
                        
                        if(!in_array($value_['attributes']['activation_code_type'], $not_in)) $counter++;
                        
                        if(!empty($res)) foreach($res as $val) $temp[] = $val;
                    }

                    $ids = $temp;
                }
                
                $data[$position_str] = $counter;
            }
        }
        
        if($data['left'] > $data['right']){
            $data['left'] = $data['left'] - $data['right'];
            $data['right'] = 0;
        }
        else{
            $data['right'] = $data['right'] - $data['left'];
            $data['left'] = 0;
        }
        
        return $data;
    }
    
    public function fetcher_($param){
        $data = null;
        
        $res = Ibo::where('placement_id', $param['id'])->get();
        
        foreach($res as $value) $data[] = $value->id;
        
        return $data;
    }
    
    public function fetcherEx_($param){
        $data = null;
        
        $res = Ibo::where('placement_id', $param['id'])->orderBy('created_at', 'desc')->get();
        
        foreach($res as $value) $data[] = $value;
        
        return $data;
    }
}
