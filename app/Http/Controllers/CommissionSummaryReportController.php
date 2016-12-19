<?php
namespace App\Http\Controllers;

ini_set('max_execution_time', 180);

use App\RankingLion;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Ibo;
use App\Commission;
use Carbon\Carbon;
use DB;
use App\ProductPurchase;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Rebate;
use App\Helper;
use App\CommissionRecord;
use App\Logger;
use App\Matching;

class CommissionSummaryReportController extends Controller {
    public function __construct(){
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //
        $data = null;
        $param_ = null;
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);
        
        //$user = Ibo::find($id);
        
        $data['type'] = $_GET['type'];
        
        switch($_GET['type']){
            case 'weekly':
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
                    
                    //$data['commission'][$i]['indirect'] = $indirect_->sum('commission_amount');
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
                
                return view('commissionsummaryreport.index', ['data'=>$data]);
                
                break;
                
            case 'monthly':
                $ibos = Ibo::all();
                
                $data['months'] = [
                    1=>'January',
                    2=>'February',
                    3=>'March',
                    4=>'April',
                    5=>'May',
                    6=>'June',
                    7=>'July',
                    8=>'August',
                    9=>'September',
                    10=>'October',
                    11=>'November',
                    12=>'December'
                ];
                
                $data['current_month'] = $date_->month;
                $data['selected_month'] = isset($_GET['month']) ? $_GET['month'] : $date_->month;
                $data['selected_year'] = isset($_GET['year']) ? $_GET['year'] : $date_->year;
                
                foreach($ibos as $i => $value){
                    $param_['id'] = $value->id;
                    $param_['month'] = $data['selected_month'];
                    $param_['year'] = $data['selected_year'];
                    
                    $res = Helper::process_rebates($param_);
                    
                    $data['rebate'][$i]['ibo_id'] = $value->id;
                    $data['rebate'][$i]['rebate'] = $res['rebates_total'];
                    $data['rebate'][$i]['ranking_lions'] = $res['ranking_lions'];
                    $data['rebate'][$i]['level'] = $res['level'];
                }
                
                return view('commissionsummaryreport.allrebate', ['data'=>$data]);
                
                break;
            
            case 'myrebate':
                $param_['id'] = $id;
                $param_['month'] = $_GET['month'];
                $param_['year'] = $_GET['year'];
                
                $data['level'] = 4;
                $data['ranking_lions'] = 'None';

                $data['rebate'] = Helper::process_rebates($param_);
                
                return view('commissionsummaryreport.rebate', ['data'=>$data]);
                
                break;
        }
    }
    
    private function get_upline_ibo_id($ibo_id){
        return Ibo::where('id', $ibo_id)->first()->placement_id;
    }
    
    private function get_position($key_, $array_){
        foreach($array_ as $key => $value){
            if(in_array($key, $value)) return $key;
        }
    }
    
    function addToRebatesArr($data, $indexData, $index, $ibo_id, $purchase_amount){
	//Get downline upline which ibo id is stored on placement_id
	$placemeny_id = $indexData['placement_id'];

	// Let's move this ibo up until found a 1,500 parent
	for($upline_cntr = $index-1; $upline_cntr >= 0; $upline_cntr--){
            if($upline_cntr == 0){
                // Add current downline to rebates_arr but add 1 on index to place it under it because current downline has reached maintaining balance
                if($purchase_amount >= 1500) $data['rebates_arr'][1][] = $ibo_id;
                else $data['rebates_arr'][0][] = $ibo_id;
            }
            else{
                foreach($data['ibos'][$index] as $childs){
                    $upline_purchase_amount = $childs['total_purchase'];	// Get upline purchase amount
                    $upline_ibo_id = $childs['ibo_id'];

                    if($upline_ibo_id == $placemeny_id){
                        // If upline has reached 1500 maintaining balance, let's look on rebates_arr on what index it was stored
                        if($upline_purchase_amount >= 1500){
                            // Check upline index stored on rebates_arr variable
                            for($rebates_arr_cntr = count($data['rebates_arr']); $rebates_arr_cntr >= 0; $rebates_arr_cntr--){
                                for($rebates_arr_cntr2 = count($data['rebates_arr'][$rebates_arr_cntr]); $rebates_arr_cntr2 >= 0; $rebates_arr_cntr2--){
                                    $rebates_arr_ibo_id = $data['rebates_arr'][$rebates_arr_cntr][rebates_arr_cntr2];

                                    if($rebates_arr_ibo_id == $upline_ibo_id){
                                        // Add current downline to rebates_arr but add 1 on index to place it under it because current downline has reached maintaining balance
                                        if($purchase_amount >= 1500) $data['rebates_arr'][$rebates_arr_cntr + 1][] = $ibo_id;
                                        else $data['rebates_arr'][$rebates_arr_cntr][] = $ibo_id;
                                        
                                        break;
                                    }
                                }
                            }
                        }
                        else{ // Meaning upline has not reached it's maintaining balance
                            // Check if reached index 0
                            // Meaning that all upline of downline has not reached maintaining balance
                            if($upline_cntr == 0){
                                // Add current downline to rebates_arr but add 1 on index to place it under it because current downline has reached maintaining balance
                                if($purchase_amount >= 1500) $data['rebates_arr'][1][] = $ibo_id;
                                else $data['rebates_arr'][0][] = $ibo_id;
                            }
                            else{
                                // Then we need to get upline's upline and check if it has reached 1500 maintaining balance
                                $placemeny_id = $childs['placement_id'];
                            }
                        }
                    }
                }
            }
	}	
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        //
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
    
    public function get_indirect($param){
        $counter = 0;
        $ids = null;
        $not_in = ['FS', 'CD'];
        
        $start_date = strtotime($param['start_date']);
        $end_date = strtotime($param['end_date']);
        
        $res = $this->fetcherEx_($param);
        
        if(!empty($res)){
            foreach($res as $value) $ids[] = $value['attributes']['id'];
        
            while(!empty($ids)){
                $temp = null;
                $ctr = 1;
                
                if($ctr <= 8){
                    foreach($ids as $value){
                        $param['id'] = $value;
                        $res = $this->fetcherEx_($param);

                        if(!empty($res)){
                            foreach($res as $val){
                                $temp[] = $val['attributes']['id'];

                                if(!in_array($val['attributes']['activation_code_type'], $not_in)){
                                    $ibo_date = strtotime($val['attributes']['created_at']);

                                    if(($ibo_date >= $start_date) && ($ibo_date <= $end_date)){
                                        $counter++;
                                        
                                        echo $ctr;
                                        print_r($val['attributes']);
                                    }
                                }
                            }
                        }

                        $ctr++;
                    }
                }
                
                $ids = $temp;
            }
        }
        
        return $counter;
    }
    
    public function fetcherEx_($param){
        $data = null;
        
        $res = Ibo::where('sponsor_id', $param['id'])->orderBy('created_at', 'desc')->get();
        
        foreach($res as $value) $data[] = $value;
        
        return $data;
    }
    
    public function fetcherEx__($param){
        $data = null;
        
        $res = Ibo::where('placement_id', $param['id'])->orderBy('created_at', 'desc')->get();
        
        foreach($res as $value) $data[] = $value;
        
        return $data;
    }
    
    public function fetcher_($param){
        $data = null;
        
        $res = Ibo::where('sponsor_id', $param['id'])
            ->where('activation_code_type', '!=', 'FS')
            ->where('activation_code_type', '!=', 'CD')
            ->whereBetween('created_at', [$param['start_date'], $param['end_date']])
            ->orderBy('created_at', 'desc')
            ->get();
        
        foreach($res as $value) $data[] = $value->id;
        
        return $data;
    }
    
    public function get_matching_bonus($param){
        $res = Matching::where('ibo_id', $param['id'])
            ->whereBetween('datetime_matched', [$param['start_date'], $param['end_date']])
            ->get();
        
        return count($res);
    }
    
    public function get_fifth_pair($param){
        $res = Matching::where('ibo_id', $param['id'])
            ->whereBetween('datetime_matched', [$param['start_date'], $param['end_date']])
            ->whereRaw('counter % 5 = 0')
            ->get();
        
        /*
        $res = DB::select('
            select * 
            from matchings 
            where 
                ibo_id = ' . $param['id'] . '
                counter % 5 = 0
        ');
        */
        //Logger::log($res);
        return count($res);
    }

    /*
    public function get_matching_bonus($param){
        $counter = 0;
        $ids = null;
        $data['left'] = 0;
        $data['right'] = 0;
        $position_str = null;
        
        $not_in = ['FS', 'CD'];
        $start_date = strtotime($param['start_date']);
        $end_date = strtotime($param['end_date']);
        
        $res = $this->fetcherEx__($param);
        
        if(!empty($res)){
            foreach($res as $value){
                $counter = 0;

                switch($value['attributes']['placement_position']){
                    case 'L':
                        $position_str = 'left';

                        if(!in_array($value['attributes']['activation_code_type'], $not_in)){
                            $ibo_date = strtotime($value['attributes']['created_at']);

                            if(($ibo_date >= $start_date) && ($ibo_date <= $end_date)) $counter++;
                        }

                        $ids = $this->fetcherEx__(['id'=>$value['attributes']['id']]);

                        break;

                    case 'R':
                        $position_str = 'right';

                        if(!in_array($value['attributes']['activation_code_type'], $not_in)){
                            $ibo_date = strtotime($value['attributes']['created_at']);

                            if(($ibo_date >= $start_date) && ($ibo_date <= $end_date)) $counter++;
                        }

                        $ids = $this->fetcherEx__(['id'=>$value['attributes']['id']]);

                        break;
                }

                while(!empty($ids)){
                    $temp = null;

                    foreach($ids as $value_){
                        if(!in_array($value_['attributes']['activation_code_type'], $not_in)){
                            $ibo_date = strtotime($value_['attributes']['created_at']);

                            if(($ibo_date >= $start_date) && ($ibo_date <= $end_date)) $counter++;
                        }

                        $res = $this->fetcherEx__(['id'=>$value_['attributes']['id']]);

                        if(!empty($res)){
                            foreach($res as $val){
                                $temp[] = $val;
                            }
                        }
                    }

                    $ids = $temp;
                }

                $data[$position_str] = $counter;
            }
        }
        
        return $data;
    }
    */
    
    public function get_ibos_total_purchase($param){
        $ibos = [];
        $temp = array($param['id']);
        
        $buff['ibo_id'] = $param['id'];
        $buff['total_purchase'] = $this->get_total_purchase($param);
        $buff['duration_start'] = $param['start_date'];
        $buff['duration_end'] = $param['end_date'];
        $ibos[0][$param['id']] = $buff;
        
        for($i = 1; $i <= $param['level']; $i++){
            $data = null;
            $buff = null;
            
            if(!empty($temp)) $res = Ibo::whereIn('placement_id', $temp)->get();
            
            if(!empty($res)){
                foreach($res as $key => $value){
                    $data[] = $value->id;
                    $param['id'] = $value->id;
                    
                    $buff[$param['id']]['ibo_id'] = $value->id;
                    $buff[$param['id']]['placement_id'] = $value->placement_id;
                    $buff[$param['id']]['total_purchase'] = $this->get_total_purchase($param) ?: 0;
                    $buff[$param['id']]['duration_start'] = $param['start_date'];
                    $buff[$param['id']]['duration_end'] = $param['end_date'];
                }
                
                $temp = $data;
                $ibos[$i] = $buff;
            }
        }
        
        return $ibos;
    }
    
    public function get_total_purchase($param){
        $res = DB::table('product_purchases')
            ->select(DB::raw('sum(purchase_amount) as total_purchase'))
            ->where('ibo_id', $param['id'])
            ->whereBetween('created_at', [$param['start_date'], $param['end_date']])
            ->first();
        
        return $res->total_purchase;
    }
    
    public function get_all($search = null){
        $data = null;
        $param_ = null;
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);
        
        $ibos = Ibo::all();
        
        $data['type'] = 'all';
        $data['current_week_no'] = $date_->weekOfYear;
        $data['selected_week'] = $date_->weekOfYear;
        
        if(!empty($search)){
            $pieces = explode('|', $search);
            
            $date_->subWeek($date_->weekOfYear - ($pieces[0]));
            $date_->year($pieces[1]);
            
            $data['selected_week'] = $pieces[0];
        }
        
        foreach($ibos as $i => $value){
            $ibo = Ibo::find($value->id);
                
            $data['commission'][$i]['ibo_name'] = $value->firstname . ' ' . $value->middlename . ' ' . $value->lastname . ' (' . sprintf('%09d', $value->id) . ')';
            
            $data['date_start'] = $date_->startOfWeek()->format('F j, Y');
            $data['date_end'] = $date_->endOfWeek()->format('F j, Y');
            
            $param_['id'] = $value->id;
            $param_['start_date'] = $date_->startOfWeek()->toDateTimeString();
            $param_['end_date'] = $date_->endOfWeek()->toDateTimeString();

            $direct_count = 0;
            
            $res = Ibo::where('sponsor_id', $value->id)
                ->where('activation_code_type', '!=', 'FS')
                ->where('activation_code_type', '!=', 'CD')
                ->whereBetween('created_at', [$date_->startOfWeek()->toDateTimeString(), $date_->endOfWeek()->toDateTimeString()])
                ->orderBy('created_at', 'desc')->get();

            $direct_count = count($res);
            
            $data['commission'][$i]['direct'] = $direct_count * Commission::where('name', 'Direct Sponsor Commission')->first()->amount;
            
            //$data['commission'][$i]['indirect'] = $this->get_indirect($param_) * Commission::where('name', 'Indirect Sponsor Commission')->first()->amount;
            $indirect_ = CommissionRecord::where('sponsor_id', $value->id)
                ->where('commission_type_id', 2)
                ->whereBetween('created_at', [$date_->startOfWeek()->toDateTimeString(), $date_->endOfWeek()->toDateTimeString()])
                ->orderBy('created_at', 'desc')->get();

            //$data['commission'][$i]['indirect'] = $indirect_->sum('commission_amount');
            $data['commission'][$i]['indirect'] = 0;
            $data['commission'][$i]['matching'] = $this->get_matching_bonus($param_);
            $data['commission'][$i]['fifth_pair'] = $this->get_fifth_pair($param_);

            $data['commission'][$i]['fifth_pairs'] = $data['commission'][$i]['fifth_pair'] * Commission::where('name', 'Matching Bonus')->first()->amount;
            $data['commission'][$i]['matching'] = $data['commission'][$i]['matching'] * Commission::where('name', 'Matching Bonus')->first()->amount - $data['commission'][$i]['fifth_pairs'];
            $data['commission'][$i]['gross'] = ($data['commission'][$i]['direct'] + $data['commission'][$i]['indirect'] + $data['commission'][$i]['matching']);
            $data['commission'][$i]['tax'] = $data['commission'][$i]['matching'] * .1;
            $data['commission'][$i]['net_commission'] = $data['commission'][$i]['gross'] - $data['commission'][$i]['tax'];
        }
        
        return view('commissionsummaryreport.all', ['data'=>$data]);
    }
    
    public function manual_compute($id){
        Helper::process_commission($id);
    }
    
    public function process_waiting($id){
        if($id == 'all'){
            $ibos = DB::table('ibos')->select('id')->orderBy('created_at', 'asc')->get();
            
            foreach($ibos as $value) Helper::process_waiting($value->id);
        }
        else Helper::process_waiting($id);
    }
    
    public function process_matching($id){
        if($id == 'all'){
            $ibos = DB::table('waitings')->select('ibo_id')->orderBy('created_at', 'asc')->get();
            
            foreach($ibos as $value) Helper::process_matching($value->ibo_id);
        }
        else Helper::process_matching($id);
    }
    
    public function process_auto_matching($id){
        Helper::process_auto_matching($id);
    }
    
    public function get_ibo_commission($id){
        $data = null;
        $param_ = null;
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);
        
        $user = Ibo::find($id);
        
        $date_->subWeek();
        
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

            //$data['commission'][$i]['indirect'] = $indirect_->sum('commission_amount');
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

        return view('ibo.commission', ['data'=>$data]);
    }

    public function get_ibo_indirect($id){
        $data = null;
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);

        $user = Ibo::find($id);

        $date_->subWeek();

        for($i = $date_->weekOfYear; $i >= 1; $i--){
            $data['commission'][$i]['date_start'] = $date_->startOfWeek()->format('F j, Y');
            $data['commission'][$i]['date_end'] = $date_->endOfWeek()->format('F j, Y');

            $indirect_ = CommissionRecord::where('sponsor_id', $id)
                ->where('commission_type_id', 2)
                ->whereBetween('created_at', [$date_->startOfWeek()->toDateTimeString(), $date_->endOfWeek()->toDateTimeString()])
                ->orderBy('created_at', 'desc')->get();

            $data['commission'][$i]['indirect'] = $indirect_->sum('commission_amount');

            $date_->subWeek();
        }

        return view('ibo.indirect', ['data'=>$data]);
    }

    public function get_indirect_500_up($search = null){
        $data = null;
        $param_ = null;
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);

        $ibos = Ibo::all();

        $data['type'] = '500 up';
        $data['current_week_no'] = $date_->weekOfYear;
        $data['selected_week'] = $date_->weekOfYear;

        if(!empty($search)){
            $pieces = explode('|', $search);
            $date_->subWeek($date_->weekOfYear - ($pieces[0]));
            $date_->year($pieces[1]);
            $data['selected_week'] = $pieces[0];
        }

        foreach($ibos as $i => $value){
            $ibo = Ibo::find($value->id);

            $data['commission'][$i]['ibo_name'] = $value->firstname . ' ' . $value->middlename . ' ' . $value->lastname . ' (' . sprintf('%09d', $value->id) . ')';
            $data['date_start'] = $date_->startOfWeek()->format('F j, Y');
            $data['date_end'] = $date_->endOfWeek()->format('F j, Y');

            $indirect_ = CommissionRecord::where('sponsor_id', $value->id)
                ->where('commission_type_id', 2)
                ->whereBetween('created_at', [$date_->startOfWeek()->toDateTimeString(), $date_->endOfWeek()->toDateTimeString()])
                ->orderBy('created_at', 'desc')->get();

            $data['commission'][$i]['indirect'] = $indirect_->sum('commission_amount');
        }

        return view('commissionsummaryreport.500up', ['data'=>$data]);
    }

    public function get_all_indirect($search = null){
        $data = null;
        $param_ = null;
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);

        $ibos = Ibo::all();

        $data['type'] = 'All';
        $data['current_week_no'] = $date_->weekOfYear;
        $data['selected_week'] = $date_->weekOfYear;

        if(!empty($search)){
            $pieces = explode('|', $search);
            $date_->subWeek($date_->weekOfYear - ($pieces[0]));
            $date_->year($pieces[1]);
            $data['selected_week'] = $pieces[0];
        }

        foreach($ibos as $i => $value){
            $ibo = Ibo::find($value->id);

            $data['commission'][$i]['ibo_name'] = $value->firstname . ' ' . $value->middlename . ' ' . $value->lastname . ' (' . sprintf('%09d', $value->id) . ')';
            $data['date_start'] = $date_->startOfWeek()->format('F j, Y');
            $data['date_end'] = $date_->endOfWeek()->format('F j, Y');

            $indirect_ = CommissionRecord::where('sponsor_id', $value->id)
                ->where('commission_type_id', 2)
                ->whereBetween('created_at', [$date_->startOfWeek()->toDateTimeString(), $date_->endOfWeek()->toDateTimeString()])
                ->orderBy('created_at', 'desc')->get();

            $data['commission'][$i]['indirect'] = $indirect_->sum('commission_amount');
        }

        return view('commissionsummaryreport.allindirect', ['data'=>$data]);
    }
}
