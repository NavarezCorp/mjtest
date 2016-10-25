<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Ibo;
use App\Commission;
use Carbon\Carbon;
use DB;
use App\ProductPurchase;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class CommissionSummaryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data = null;
        $param_ = null;
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);
        
        $user = Ibo::find($id);
        
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
                        ->where('activation_code_type', '=', null)
                        ->whereBetween('created_at', [$date_->startOfWeek()->toDateTimeString(), $date_->endOfWeek()->toDateTimeString()])
                        ->orderBy('created_at', 'desc')->get();

                    $direct_count = count($res);
                    
                    $data['commission'][$i]['direct'] = $direct_count * Commission::where('name', 'Direct Sponsor Commission')->first()->amount;
                    $data['commission'][$i]['indirect'] = $this->get_indirect($param_) * Commission::where('name', 'Indirect Sponsor Commission')->first()->amount;
                    
                    $param_['position'] = 'L';
                    $left_ = $this->get_matching_bonus($param_);
                    $param_['position'] = 'R';
                    $right_ = $this->get_matching_bonus($param_);
                    
                    $data['commission'][$i]['fifth_pairs'] = intval(min($left_, $right_) / 5) * Commission::where('name', 'Matching Bonus')->first()->amount;
                    $data['commission'][$i]['matching'] = (min($left_, $right_) * Commission::where('name', 'Matching Bonus')->first()->amount) - $data['commission'][$i]['fifth_pairs'];
                    $data['commission'][$i]['gross'] = ($data['commission'][$i]['direct'] + $data['commission'][$i]['indirect'] + $data['commission'][$i]['matching']);
                    $data['commission'][$i]['tax'] = $data['commission'][$i]['gross'] * .1;
                    $data['commission'][$i]['net_commission'] = $data['commission'][$i]['gross'] - $data['commission'][$i]['tax'];
                    
                    $date_->subWeek();
                }
                
                break;
                
            case 'monthly':
                $months = [
                    1=>'january',
                    2=>'february',
                    3=>'march',
                    4=>'april',
                    5=>'may',
                    6=>'june',
                    7=>'july',
                    8=>'august',
                    9=>'september',
                    10=>'october',
                    11=>'november',
                    12=>'december'
                ];
                
                /*
                for($i = $date_->month; $i >= 1; $i--){
                    $data['commission'][$i]['date_start'] = $date_->parse('first day of ' . $months[$i] . ' ' . $date_->year)->format('F j, Y');
                    $data['commission'][$i]['date_end'] = $date_->parse('last day of ' . $months[$i] . ' ' . $date_->year)->format('F j, Y');
                    
                    $firstday = $date_->parse('first day of ' . $months[$i] . ' ' . $date_->year)->toDateString() . ' 00:00:00';
                    $lastday = $date_->parse('last day of ' . $months[$i] . ' ' . $date_->year)->toDateString() . ' 23:59:59';
                    
                    $res = DB::table('ibos')
                        ->select(DB::raw('count(id) as count'))
                        ->where('sponsor_id', $id)
                        ->whereBetween('created_at', [$firstday, $lastday])
                        ->first();
                    
                    $data['commission'][$i]['direct'] = $res->count * Commission::where('name', 'Direct Sponsor Commission')->first()->amount;
                    $data['commission'][$i]['indirect'] = 0;
                    $data['commission'][$i]['matching'] = 0;
                    $data['commission'][$i]['fifth_pairs'] = 0;
                    $data['commission'][$i]['net_commission'] = $data['commission'][$i]['direct'] + $data['commission'][$i]['indirect'] + $data['commission'][$i]['matching'] + $data['commission'][$i]['fifth_pairs'];
                }
                */
                
                $param_['id'] = $id;
                $param_['level'] = 9;
                $param_['start_date'] = $date_->parse('first day of ' . $months[$date_->month] . ' ' . $date_->year)->toDateString() . ' 00:00:00';
                $param_['end_date'] = $date_->parse('last day of ' . $months[$date_->month] . ' ' . $date_->year)->toDateString() . ' 23:59:59';
                $data['ibos'] = $this->get_ibos_total_purchase($param_);
                
                if($data['ibos'][$id]['total_purchase'] >= 1500){
                    $data['rebates_arr'][0][] = $data['ibos'][$id]['ibo_id'];
                    
                    // level 1
                    if(!empty($data['ibos'][1])){
                        foreach($data['ibos'][1] as $value){
                            if($value['total_purchase'] >= 1500) $data['rebates_arr'][1][] = $value['ibo_id'];
                            else $data['rebates_arr'][0][] = $value['ibo_id'];
                        }
                    }
                    // level 1
                    
                    // level 2
                    if(!empty($data['ibos'][2])){
                        foreach($data['ibos'][2] as $value){
                            if($data['ibos'][1][$value['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][2][] = $value['ibo_id'];
                                else $data['rebates_arr'][1][] = $value['ibo_id'];
                            }
                            else $data['rebates_arr'][0][] = $value['ibo_id'];
                        }
                    }
                    // level 2
                    
                    // level 3
                    if(!empty($data['ibos'][3])){
                        foreach($data['ibos'][3] as $value){
                            if($data['ibos'][2][$value['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][3][] = $value['ibo_id'];
                                else $data['rebates_arr'][2][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][1][$data['ibos'][2][$value['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][2][] = $value['ibo_id'];
                                else $data['rebates_arr'][1][] = $value['ibo_id'];
                            }
                            else $data['rebates_arr'][0][] = $value['ibo_id'];
                        }
                    }
                    // level 3
                    
                    // level 4
                    if(!empty($data['ibos'][4])){
                        foreach($data['ibos'][4] as $value){
                            if($data['ibos'][3][$value['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][4][] = $value['ibo_id'];
                                else $data['rebates_arr'][3][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][2][$data['ibos'][3][$value['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][3][] = $value['ibo_id'];
                                else $data['rebates_arr'][2][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][1][$data['ibos'][2][$data['ibos'][3][$value['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][2][] = $value['ibo_id'];
                                else $data['rebates_arr'][1][] = $value['ibo_id'];
                            }
                            else $data['rebates_arr'][0][] = $value['ibo_id'];
                        }
                    }
                    // level 4
                    
                    // level 5
                    if(!empty($data['ibos'][5])){
                        foreach($data['ibos'][5] as $value){
                            if($data['ibos'][4][$value['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][5][] = $value['ibo_id'];
                                else $data['rebates_arr'][4][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][3][$data['ibos'][4][$value['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][4][] = $value['ibo_id'];
                                else $data['rebates_arr'][3][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][2][$data['ibos'][3][$data['ibos'][4][$value['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][3][] = $value['ibo_id'];
                                else $data['rebates_arr'][2][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][1][$data['ibos'][2][$data['ibos'][3][$data['ibos'][4][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][2][] = $value['ibo_id'];
                                else $data['rebates_arr'][1][] = $value['ibo_id'];
                            }
                            else $data['rebates_arr'][0][] = $value['ibo_id'];
                        }
                    }
                    // level 5
                    
                    // level 6
                    if(!empty($data['ibos'][6])){
                        foreach($data['ibos'][6] as $value){
                            if($data['ibos'][5][$value['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][6][] = $value['ibo_id'];
                                else $data['rebates_arr'][5][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][4][$data['ibos'][5][$value['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][5][] = $value['ibo_id'];
                                else $data['rebates_arr'][4][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$value['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][4][] = $value['ibo_id'];
                                else $data['rebates_arr'][3][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][2][$data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][3][] = $value['ibo_id'];
                                else $data['rebates_arr'][2][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][1][$data['ibos'][2][$data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][2][] = $value['ibo_id'];
                                else $data['rebates_arr'][1][] = $value['ibo_id'];
                            }
                            else $data['rebates_arr'][0][] = $value['ibo_id'];
                        }
                    }
                    // level 6
                    
                    // level 7
                    if(!empty($data['ibos'][7])){
                        foreach($data['ibos'][7] as $value){
                            if($data['ibos'][6][$value['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][7][] = $value['ibo_id'];
                                else $data['rebates_arr'][6][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][5][$data['ibos'][6][$value['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][6][] = $value['ibo_id'];
                                else $data['rebates_arr'][5][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$value['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][5][] = $value['ibo_id'];
                                else $data['rebates_arr'][4][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][4][] = $value['ibo_id'];
                                else $data['rebates_arr'][3][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][2][$data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][3][] = $value['ibo_id'];
                                else $data['rebates_arr'][2][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][1][$data['ibos'][2][$data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][2][] = $value['ibo_id'];
                                else $data['rebates_arr'][1][] = $value['ibo_id'];
                            }
                            else $data['rebates_arr'][0][] = $value['ibo_id'];
                        }
                    }
                    // level 7
                    
                    // level 8
                    if(!empty($data['ibos'][8])){
                        foreach($data['ibos'][8] as $value){
                            if($data['ibos'][7][$value['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][8][] = $value['ibo_id'];
                                else $data['rebates_arr'][7][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][6][$data['ibos'][7][$value['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][7][] = $value['ibo_id'];
                                else $data['rebates_arr'][6][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][5][$data['ibos'][6][$data['ibos'][7][$value['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][6][] = $value['ibo_id'];
                                else $data['rebates_arr'][5][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$data['ibos'][7][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][5][] = $value['ibo_id'];
                                else $data['rebates_arr'][4][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$data['ibos'][7][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][4][] = $value['ibo_id'];
                                else $data['rebates_arr'][3][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][2][$data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$data['ibos'][7][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][3][] = $value['ibo_id'];
                                else $data['rebates_arr'][2][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][1][$data['ibos'][2][$data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$data['ibos'][7][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][2][] = $value['ibo_id'];
                                else $data['rebates_arr'][1][] = $value['ibo_id'];
                            }
                            else $data['rebates_arr'][0][] = $value['ibo_id'];
                        }
                    }
                    // level 8
                    
                    // level 9
                    if(!empty($data['ibos'][9])){
                        foreach($data['ibos'][9] as $value){
                            if($data['ibos'][8][$value['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][9][] = $value['ibo_id'];
                                else $data['rebates_arr'][8][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][7][$data['ibos'][8][$value['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][8][] = $value['ibo_id'];
                                else $data['rebates_arr'][7][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][6][$data['ibos'][7][$data['ibos'][8][$value['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][7][] = $value['ibo_id'];
                                else $data['rebates_arr'][6][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][5][$data['ibos'][6][$data['ibos'][7][$data['ibos'][8][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][6][] = $value['ibo_id'];
                                else $data['rebates_arr'][5][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$data['ibos'][7][$data['ibos'][8][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][5][] = $value['ibo_id'];
                                else $data['rebates_arr'][4][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$data['ibos'][7][$data['ibos'][8][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][4][] = $value['ibo_id'];
                                else $data['rebates_arr'][3][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][2][$data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$data['ibos'][7][$data['ibos'][8][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][3][] = $value['ibo_id'];
                                else $data['rebates_arr'][2][] = $value['ibo_id'];
                            }
                            elseif($data['ibos'][1][$data['ibos'][2][$data['ibos'][3][$data['ibos'][4][$data['ibos'][5][$data['ibos'][6][$data['ibos'][7][$data['ibos'][8][$value['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['placement_id']]['total_purchase'] >= 1500){
                                if($value['total_purchase'] >= 1500) $data['rebates_arr'][2][] = $value['ibo_id'];
                                else $data['rebates_arr'][1][] = $value['ibo_id'];
                            }
                            else $data['rebates_arr'][0][] = $value['ibo_id'];
                        }
                    }
                    // level 9
                }
                
                echo json_encode($data);
                die();
                break;
        }
        
        return view('commissionsummaryreport.index', ['data'=>$data]);
    }
    
    private function get_upline_ibo_id($ibo_id){
        return Ibo::where('id', $ibo_id)->first()->placement_id;
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
    
    public function get_indirect($param){
        $counter = 0;
        $ids = null;
        
        $ids = $this->fetcher_($param);
        
        while(!empty($ids)){
            $temp = null;

            foreach($ids as $value){
                $param['id'] = $value;
                $res = $this->fetcher_($param);

                if(!empty($res)) foreach($res as $val) $temp[] = $val;
            }

            $ids = $temp;
            $counter += count($ids);
        }
        
        return $counter;
    }
    
    public function fetcher_($param){
        $data = null;
        
        $res = Ibo::where('sponsor_id', $param['id'])
            ->where('activation_code_type', '!=', 'FS')
            ->where('activation_code_type', '!=', 'CD')
            ->where('activation_code_type', '=', null)
            ->whereBetween('created_at', [$param['start_date'], $param['end_date']])
            ->orderBy('created_at', 'desc')
            ->get();
        
        foreach($res as $value) $data[] = $value->id;
        
        return $data;
    }
    
    public function get_matching_bonus($param){
        $counter = 0;
        $ids = null;
        
        $res = $this->fetcher__($param);
        
        switch($param['position']){
            case 'L':
                if(!empty($res[0])){
                    $counter++;
                    $param['id'] = $res[0];
                    $ids = $this->fetcher__($param);
                }
                
                break;
                
            case 'R':
                if(!empty($res[1])){
                    $counter++;
                    $param['id'] = $res[1];
                    $ids = $this->fetcher__($param);
                }
                
                break;
        }
        
        while(!empty($ids)){
            $temp = null;
            $counter += count($ids);
            
            foreach($ids as $value){
                $param['id'] = $value;
                $res = $this->fetcher__($param);
                
                if(!empty($res)) foreach($res as $val) $temp[] = $val;
            }

            $ids = $temp;
        }
        
        return $counter;
    }
    
    public function fetcher__($param){
        $data = null;
        
        $res = Ibo::where('placement_id', $param['id'])
            ->where('activation_code_type', '!=', 'FS')
            ->where('activation_code_type', '!=', 'CD')
            ->where('activation_code_type', '=', null)
            ->whereBetween('created_at', [$param['start_date'], $param['end_date']])
            ->orderBy('created_at', 'desc')
            ->get();
        
        foreach($res as $value) $data[] = $value->id;
        
        return $data;
    }
    
    public function get_ibos_total_purchase($param){
        $ibos = [];
        $temp = array($param['id']);
        
        $buff['ibo_id'] = $param['id'];
        $buff['total_purchase'] = $this->get_total_purchase($param);
        $buff['duration_start'] = $param['start_date'];
        $buff['duration_end'] = $param['end_date'];
        $ibos[$param['id']] = $buff;
        
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
    
    public function get_all(){
        $data = null;
        $param_ = null;
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);
        
        $ibos = Ibo::all();
        
        $data['type'] = 'all';
        
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
                ->where('activation_code_type', '!=', null)
                ->whereBetween('created_at', [$date_->startOfWeek()->toDateTimeString(), $date_->endOfWeek()->toDateTimeString()])
                ->orderBy('created_at', 'desc')->get();

            $direct_count = count($res);
            
            $data['commission'][$i]['direct'] = $direct_count * Commission::where('name', 'Direct Sponsor Commission')->first()->amount;
            $data['commission'][$i]['indirect'] = $this->get_indirect($param_) * Commission::where('name', 'Indirect Sponsor Commission')->first()->amount;

            $param_['position'] = 'L';
            $left_ = $this->get_matching_bonus($param_);
            $param_['position'] = 'R';
            $right_ = $this->get_matching_bonus($param_);

            $data['commission'][$i]['fifth_pairs'] = intval(min($left_, $right_) / 5) * Commission::where('name', 'Matching Bonus')->first()->amount;
            $data['commission'][$i]['matching'] = (min($left_, $right_) * Commission::where('name', 'Matching Bonus')->first()->amount) - $data['commission'][$i]['fifth_pairs'];
            $data['commission'][$i]['gross'] = ($data['commission'][$i]['direct'] + $data['commission'][$i]['indirect'] + $data['commission'][$i]['matching']);
            $data['commission'][$i]['tax'] = $data['commission'][$i]['gross'] * .1;
            $data['commission'][$i]['net_commission'] = $data['commission'][$i]['gross'] - $data['commission'][$i]['tax'];
        }
        
        return view('commissionsummaryreport.all', ['data'=>$data]);
    }
}
