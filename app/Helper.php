<?php

namespace App;

use App\Commission;
use App\CommissionRecord;
use App\Ibo;
use App\Logger;
use App\Matching;
use App\Waiting;
use App\Rebate;
use App\RankingLion;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Helper {
    const COMMISSION_DIRECT = 1;
    const COMMISSION_DIRECT_NAME = 'Direct Sponsor Commission';
    
    const COMMISSION_INDIRECT = 2;
    const COMMISSION_INDIRECT_NAME = 'Indirect Sponsor Commission';
    
    public static function process_commission($id){
        Logger::log('starting process direct and indirect commission');
        
        $not_in = ['FS', 'CD'];
        
        // get ibo record
        $res = Ibo::find($id);
        
        // check if ibo record already exist in commission record table
        $check = CommissionRecord::where('from_ibo_id', $id)
            ->where('created_at', $res->created_at)
            ->get();
        
        // if ibo not exist in commission record table and not fs or cd
        if(!$check->count() && !in_array($res->activation_code_type, $not_in)){
            if(!empty($res)){
                // save direct commission for upline
                $model = new CommissionRecord;
                $model->sponsor_id = $res->sponsor_id;
                $model->commission_type_id = self::COMMISSION_DIRECT;
                $model->from_ibo_id = $res->id;
                $model->commission_amount = Commission::where('name', self::COMMISSION_DIRECT_NAME)->first()->amount;
                $model->created_by = Auth::user()->id;
                $model->created_at = $res->created_at;
                $model->save();

                $search_id = $res->sponsor_id;
                $created_at = $res->created_at;
                $from_ibo_id = $res->id;

                for($i = 1; $i <= 7; $i++){
                    if(!empty($search_id)){
                        $res_ = Ibo::find($search_id);

                        if(!empty($res_)){
                            // save indirect commission for indirect upline upto level 7
                            $model = new CommissionRecord;
                            $model->sponsor_id = $res_->sponsor_id;
                            $model->commission_type_id = self::COMMISSION_INDIRECT;
                            $model->from_ibo_id = $from_ibo_id;
                            $model->commission_amount = Commission::where('name', self::COMMISSION_INDIRECT_NAME)->first()->amount;
                            $model->created_by = Auth::user()->id;
                            $model->created_at = $created_at;
                            $model->save();

                            $search_id = $res_->sponsor_id;
                        }
                    }
                }
            }
        }
        
        self::process_auto_matching($id);
        
        Logger::log('Done processing direct and indirect commission');
    }
    
    public static function process_waiting($id){
        Logger::log('starting processing waiting');
        
        // check if ibo already exist in the record
        $check = Waiting::where('ibo_id', $id)->get();
        
        if(!$check->count()){
            $ids = null;
            $data['left'] = null;
            $data['right'] = null;
            $position_str = null;
            $not_in = ['FS', 'CD'];
            
            // get first level downline
            $res = Ibo::where('placement_id', $id)->get();
            
            if(!empty($res)){
                foreach($res as $value){
                    $counter = null;
                    
                    // check placement position
                    switch($value['attributes']['placement_position']){
                        case 'L':
                            $position_str = 'left';
                            break;

                        case 'R':
                            $position_str = 'right';
                            break;
                    }
                    
                    // if not fs or cd get downline ibo_id
                    if(!in_array($value['attributes']['activation_code_type'], $not_in)) $counter[] = $value['attributes']['id'];
                    
                    // get second level downline
                    $ids = Ibo::where('placement_id', $value['attributes']['id'])->get();
                    
                    while(!empty($ids)){
                        $temp = null;

                        foreach($ids as $value_){
                            // get third level downline upto last level downline
                            $res = Ibo::where('placement_id', $value_['attributes']['id'])->get();
                            
                            // if not fs or cd get downline ibo_id
                            if(!in_array($value_['attributes']['activation_code_type'], $not_in)) $counter[] = $value_['attributes']['id'];
                            
                            if(!empty($res)) foreach($res as $val) $temp[] = $val;
                        }

                        $ids = $temp;
                    }

                    // store the ibo ids for left or right
                    $data[$position_str] = $counter;
                }
            }
            
            // rearrange all left by created_at from oldest to latest
            if(!empty($data['left'])){
                $res_left = Ibo::whereIn('id', $data['left'])->orderBy('created_at', 'asc')->get();
                
                foreach($res_left as $key => $value){
                    //$data['new_left'][$key]['id'] = $value['attributes']['id'];
                    //$data['new_left'][$key]['created_at'] = $value['attributes']['created_at'];
                    $data['new_left'][] = $value['attributes']['id'];
                }
            }
            else $data['new_left'] = null;
            
            // rearrange all right by created_at from oldest to latest
            if(!empty($data['right'])){
                $res_right = Ibo::whereIn('id', $data['right'])->orderBy('created_at', 'asc')->get();
                
                foreach($res_right as $key => $value){
                    //$data['new_right'][$key]['id'] = $value['attributes']['id'];
                    //$data['new_right'][$key]['created_at'] = $value['attributes']['created_at'];
                    $data['new_right'][] = $value['attributes']['id'];
                }
            }
            else $data['new_right'] = null;

            // save left and right
            $model = new Waiting;
            $model->ibo_id = $id;
            $model->left = !empty($data['new_left']) ? implode(',', $data['new_left']) : '';
            $model->right = !empty($data['new_right']) ? implode(',', $data['new_right']) : '';
            $model->save();
        }
        
        Logger::log('Done processing waiting');
    }
    
    public static function process_matching($id){
        Logger::log('starting processing matching');
        
        $res = Waiting::where('ibo_id', $id)->first();
        
        // convert to array form
        $data['left_'] = !empty($res->left) ? explode(',', $res->left) : null;
        $data['right_'] = !empty($res->right) ? explode(',', $res->right) : null;
        
        // get counter for iteration
        $data['ctr'] = min(count($data['left_']), count($data['right_']));
        
        // get record id for saving new left and right
        $data['record_id'] = $res->id;
        
        for($i = 0; $i < $data['ctr']; $i++){
            // get first items
            $arr_[] = $data['left_'][0];
            $arr_[] = $data['right_'][0];
            
            $res = Ibo::whereIn('id', $arr_)->orderBy('created_at', 'desc')->get();
            
            // get latest created_at between first items to be used for datetime_matched
            $data['created_at_'] = $res[0]['created_at'];
            
            // save matched items
            $model = new Matching;
            $model->datetime_matched = $data['created_at_'];
            $model->left = $data['left_'][0];
            $model->right = $data['right_'][0];
            $model->ibo_id = $id;
            
            // get latest counter value
            $res = Matching::where('ibo_id', $id)->orderBy('counter', 'desc')->first();
            $counter_ = !empty($res) ? $res->counter : 0;
            $model->counter = $counter_ + 1;
            
            $model->save();
            
            // remove first items
            array_splice($data['left_'], 0, 1);
            array_splice($data['right_'], 0, 1);
        }
        
        // save new left and right
        $model = Waiting::find($data['record_id']);
        $model->left = !empty($data['left_']) ? implode(',', $data['left_']) : '';
        $model->right = !empty($data['right_']) ? implode(',', $data['right_']) : '';
        $model->save();
        
        Logger::log('Done processing matching');
    }
    
    public static function process_auto_matching($id){
        $not_in = ['FS', 'CD'];
        $search_id = $id;
        
        // save new ibo to waiting table
        self::process_waiting($search_id);
        
        Logger::log('starting auto matching');
        
        // get record of new ibo
        $ibo = Ibo::find($search_id);
        
        if(!in_array($ibo->activation_code_type, $not_in)){
            while($search_id){
                if(!empty($ibo)){
                    if(!empty($ibo->placement_id)){
                        // get waiting record of upper level
                        $res = Waiting::where('ibo_id', $ibo->placement_id)->first();

                        // convert to array form
                        $data['left'] = !empty($res->left) ? explode(',', $res->left) : null;
                        $data['right'] = !empty($res->right) ? explode(',', $res->right) : null;

                        // check ibo placement position
                        switch($ibo->placement_position){
                            case 'L':
                                // if ibo not exist in left add it
                                if(!empty($data['left'])){
                                    //if(!in_array($ibo->id, $data['left'])) $data['left'][] = $id;
                                    if(!in_array($id, $data['left'])) $data['left'][] = $id;
                                }
                                else $data['left'][] = $id;

                                break;

                            case 'R':
                                // if ibo not exist in right add it
                                if(!empty($data['right'])){
                                    //if(!in_array($ibo->id, $data['right'])) $data['right'][] = $id;
                                    if(!in_array($id, $data['right'])) $data['right'][] = $id;
                                }
                                else $data['right'][] = $id;

                                break;
                        }

                        // save new left and right
                        $model = Waiting::find($res->id);
                        $model->left = !empty($data['left']) ? implode(',', $data['left']) : '';
                        $model->right = !empty($data['right']) ? implode(',', $data['right']) : '';
                        $model->save();
                    }
                }

                $search_id = $ibo->placement_id;
                
                $ibo = Ibo::find($search_id);
            }
        }
        
        // process matching for all
        $ibos = DB::table('waitings')->select('ibo_id')->orderBy('created_at', 'asc')->get();    
        foreach($ibos as $value) self::process_matching($value->ibo_id);
        
        Logger::log('Done processing auto matching');
    }
    
    public static function process_rebates($param){
        $data = null;
        $param_ = null;
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);
        
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

        $param_['id'] = $param['id'];

        $data['level'] = 4;
        $data['ranking_lions'] = 'None';
        $data['percentage'] = 2;
        
        $rlid = Ibo::find($param['id'])->ranking_lions_id;
        
        if($rlid){
            $rl = RankingLion::find($rlid);
            $data['ranking_lions'] = $rl->name;
            $r = Rebate::find($rl->rebates_id);
            $data['percentage'] = $r->percentage;
            $data['level'] = $r->level;
        }

        $param_['level'] = 9;
        $param_['start_date'] = $date_->parse('first day of ' . $months[$param['month']] . ' ' . $param['year'])->toDateString();
        $param_['end_date'] = $date_->parse('last day of ' . $months[$param['month']] . ' ' . $param['year'])->toDateString();
        $data['ibos'] = self::get_ibos_total_purchase($param_);

        $data['rebates_arr'] = null;

        $indexData = $data['ibos'][0][$param['id']];

        // Get ibo purchase amount
        $purchase_amount = $indexData['total_purchase'];

        $ibo_id = $indexData['ibo_id'];

        // Meaning current logged in user is not eligible
        if($purchase_amount >=1500){
            // Add to rebates variable to store it's info
            $data['rebates_arr'][0][] = $ibo_id;

            for($index = 1; $index < count($data['ibos']); $index++){
                if(!empty($data['ibos'][$index])){
                    foreach($data['ibos'][$index] as $indexData){
                        // Get ibo purchase amount
                        $purchase_amount = $indexData['total_purchase'];
                        $ibo_id = $indexData['ibo_id'];

                        //Get downline upline which ibo id is stored on placement_id
                        $placemeny_id = $indexData['placement_id'];
                        $hasAdded = false;

                        // Let's move this ibo up until found a 1,500 parent
                        for($upline_cntr = $index-1; $upline_cntr >= 0; $upline_cntr--){
                            if($upline_cntr == 0){
                                // Add current downline to rebates_arr but add 1 on index to place it under it because current downline has reached maintaining balance
                                if($purchase_amount >= 1500) $data['rebates_arr'][1][] = $ibo_id;
                                else $data['rebates_arr'][0][] = $ibo_id;
                            }
                            else{
                                foreach($data['ibos'][$upline_cntr] as $childs){
                                    // Get upline purchase amount
                                    $upline_purchase_amount = $childs['total_purchase'];
                                    $upline_ibo_id = $childs['ibo_id'];

                                    if($upline_ibo_id == $placemeny_id){
                                        // If upline has reached 1500 maintaining balance, let's look on rebates_arr on what index it was stored
                                        if($upline_purchase_amount >= 1500){
                                            // Check upline index stored on rebates_arr variable
                                            for($rebates_arr_cntr = count($data['rebates_arr']) - 1; $rebates_arr_cntr >= 0; $rebates_arr_cntr--){
                                                foreach($data['rebates_arr'][$rebates_arr_cntr] as $rebates_arr_ibo_id){
                                                    if($rebates_arr_ibo_id == $upline_ibo_id){
                                                        // Add current downline to rebates_arr but add 1 on index to place it under it because current downline has reached maintaining balance
                                                        if($purchase_amount >= 1500) $data['rebates_arr'][$rebates_arr_cntr + 1][] = $ibo_id;
                                                        else $data['rebates_arr'][$rebates_arr_cntr][] = $ibo_id;

                                                        $hasAdded = true;

                                                        break;
                                                    }
                                                }

                                                if($hasAdded == true) break;
                                            }
                                        }
                                        else{ // Meaning upline has not reached it's maintaining balance
                                            // Check if reached index 0
                                            // Meaning that all upline of downline has not reached maintaining balance
                                            if($upline_cntr == 0){
                                                // Add current downline to rebates_arr but add 1 on index to place it under it because current downline has reached maintaining balance
                                                if($purchase_amount >= 1500) $data['rebates_arr'][1][] = $ibo_id;
                                                else $data['rebates_arr'][0][] = $ibo_id;

                                                $hasAdded = true;
                                            }
                                            else{
                                                // Then we need to get upline's upline and check if it has reached 1500 maintaining balance
                                                $placemeny_id = $childs['placement_id'];
                                            }
                                        }
                                    }

                                    if($hasAdded == true) break;
                                }
                            }

                            if($hasAdded == true) break;
                        }
                    }
                }
            }
        }

        $data['user_ibo_id'] = $param['id'];

        foreach($data['ibos'] as $key => $value){
            if($key == 0) continue;
            else{
                if(!empty($value)){
                    $data['ibos_levels'][] = $value;

                    foreach($value as $value_) $data['ibos_total_purchases'][$value_['ibo_id']] = $value_;

                }
            }
        }

        $data['rebates_total'] = 0;

        if(!empty($data['rebates_arr'])){
            foreach($data['rebates_arr'] as $key => $value){
                if($key == 0) continue;
                else{
                    if($key < ($data['level'] + 1)){
                        if(!empty($value)){
                            $purchases = 0;

                            foreach($value as $value_) $purchases += $data['ibos_total_purchases'][$value_]['total_purchase'];

                            $data['rebates']['level_' . $key]['total'] = $purchases;
                            $data['rebates']['level_' . $key]['percented'] = $purchases * (!empty(Rebate::where('level', $key)->first()->percentage) ? Rebate::where('level', $key)->first()->percentage : 0) / 100;
                            $data['rebates_total'] += $data['rebates']['level_' . $key]['percented'];
                        }
                    }
                }
            }
        }
        
        return $data;
    }
    
    public static function get_ibos_total_purchase($param){
        $ibos = [];
        $temp = array($param['id']);
        
        $buff['ibo_id'] = $param['id'];
        $buff['total_purchase'] = self::get_total_purchase($param);
        $buff['duration_start'] = $param['start_date'];
        $buff['duration_end'] = $param['end_date'];
        $ibos[0][$param['id']] = $buff;
        $i = 1;
        
        //for($i = 1; $i <= $param['level']; $i++){
        while($temp){
            $data = null;
            $buff = null;
            
            if(!empty($temp)) $res = Ibo::whereIn('placement_id', $temp)->get();
            
            if(!empty($res)){
                foreach($res as $key => $value){
                    $data[] = $value->id;
                    $param['id'] = $value->id;
                    
                    $buff[$param['id']]['ibo_id'] = $value->id;
                    $buff[$param['id']]['placement_id'] = $value->placement_id;
                    $buff[$param['id']]['total_purchase'] = self::get_total_purchase($param) ?: 0;
                    $buff[$param['id']]['duration_start'] = $param['start_date'];
                    $buff[$param['id']]['duration_end'] = $param['end_date'];
                }
                
                $temp = $data;
                $ibos[$i] = $buff;
            }
            
            $i++;
        }
        
        return $ibos;
    }
    
    public static function get_total_purchase($param){
        $res = DB::table('product_purchases')
            ->select(DB::raw('sum(purchase_amount) as total_purchase'))
            ->where('ibo_id', $param['id'])
            ->whereBetween('created_at', [$param['start_date'] . ' 00:00:00', $param['end_date'] . ' 23:59:59'])
            ->first();
        
        return $res->total_purchase;
    }

    public static function get_app($id){
        $res = DB::table('product_purchases')
            ->select(DB::raw('sum(purchase_amount) as app'))
            ->where('ibo_id', $id)
            ->first();

        return $res->app;
    }
    
    public static function get_total_weeks_of_year($year){
        $date_ = Carbon::now('Asia/Manila');
        $date_->setWeekStartsAt(Carbon::SATURDAY);
        $date_->setWeekEndsAt(Carbon::FRIDAY);
        $weeks = $date_->parse('last day of december ' . $year)->weekOfYear;
        
        return $weeks;
    }
}
