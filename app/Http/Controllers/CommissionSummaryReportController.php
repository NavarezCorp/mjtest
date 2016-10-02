<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Ibo;
use App\Commission;
use Carbon\Carbon;
use DB;

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
        
        $data['type'] = $_GET['type'];
        
        switch($_GET['type']){
            case 'weekly':        
                for($i = $date_->weekOfYear; $i >= 1; $i--){
                    $data['commission'][$i]['date_start'] = $date_->startOfWeek()->format('F j, Y');
                    $data['commission'][$i]['date_end'] = $date_->endOfWeek()->format('F j, Y');
                    
                    $res = Ibo::where('sponsor_id', $id)
                        ->whereBetween('created_at', [$date_->startOfWeek()->toDateTimeString(), $date_->endOfWeek()->toDateTimeString()])
                        ->orderBy('created_at', 'desc')->get();
                    
                    $data['commission'][$i]['direct'] = count($res) * Commission::where('name', 'Direct Sponsor Commission')->first()->amount;
                    
                    // indirect sponsor commission
                    $param_['id'] = $id;
                    $param_['start_date'] = $date_->startOfWeek()->toDateTimeString();
                    $param_['end_date'] = $date_->endOfWeek()->toDateTimeString();
                    $data['commission'][$i]['indirect'] = $this->get_indirect($param_) * Commission::where('name', 'Indirect Sponsor Commission')->first()->amount;
                    // indirect sponsor commission
                    
                    $data['commission'][$i]['matching'] = 0;
                    $data['commission'][$i]['fifth_pairs'] = 0;
                    $data['commission'][$i]['net_commission'] = $data['commission'][$i]['direct'] + $data['commission'][$i]['indirect'] + $data['commission'][$i]['matching'] + $data['commission'][$i]['fifth_pairs'];
                    
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
                
                break;
        }
        
        return view('commissionsummaryreport.index', ['data'=>$data]);
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
        $data['counter'] = 0;
        
        $data['level_1'] = $this->fetcher_($param);
        
        // level 2
        if(!empty($data['level_1'])){
            foreach($data['level_1'] as $value){
                $param['id'] = $value;
                $res = $this->fetcher_($param);
                $data['counter'] += count($res);
                $data['level_2'][] = $res;
            }
        }
        
        // level 3
        if(!empty($data['level_2'])){
            foreach($data['level_2'] as $value){
                $param['id'] = $value;
                $res = $this->fetcher_($param);
                $data['counter'] += count($res);
                $data['level_3'][] = $res;
            }
        }
        
        // level 4
        if(!empty($data['level_3'])){
            foreach($data['level_3'] as $value){
                $param['id'] = $value;
                $res = $this->fetcher_($param);
                $data['counter'] += count($res);
                $data['level_4'][] = $res;
            }
        }
        
        // level 5
        if(!empty($data['level_4'])){
            foreach($data['level_4'] as $value){
                $param['id'] = $value;
                $res = $this->fetcher_($param);
                $data['counter'] += count($res);
                $data['level_5'][] = $res;
            }
        }
        
        // level 6
        if(!empty($data['level_5'])){
            foreach($data['level_5'] as $value){
                $param['id'] = $value;
                $res = $this->fetcher_($param);
                $data['counter'] += count($res);
                $data['level_6'][] = $res;
            }
        }
        
        // level 7
        if(!empty($data['level_6'])){
            foreach($data['level_6'] as $value){
                $param['id'] = $value;
                $res = $this->fetcher_($param);
                $data['counter'] += count($res);
                $data['level_7'][] = $res;
            }
        }
        
        // level 8
        if(!empty($data['level_7'])){
            foreach($data['level_7'] as $value){
                $param['id'] = $value;
                $res = $this->fetcher_($param);
                $data['counter'] += count($res);
                $data['level_8'][] = $res;
            }
        }
        
        return $data['counter'];
    }
    
    public function fetcher_($param){
        $data = null;
        
        $res = Ibo::where('sponsor_id', $param['id'])->whereBetween('created_at', [$param['start_date'], $param['end_date']])->orderBy('created_at', 'desc')->get();
        
        foreach($res as $value) $data[] = $value->id;
        
        return $data;
    }
}
