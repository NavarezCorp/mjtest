<?php

namespace App;

use App\Ibo;
use App\CommissionRecord;
use App\Commission;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Logger;

class Helper {
    const COMMISSION_DIRECT = 1;
    const COMMISSION_DIRECT_NAME = 'Direct Sponsor Commission';
    
    const COMMISSION_INDIRECT = 2;
    const COMMISSION_INDIRECT_NAME = 'Indirect Sponsor Commission';
    
    public static function process_commission($id){
        $not_in = ['FS', 'CD'];
        
        $res = Ibo::find($id);
        
        $check = CommissionRecord::where('from_ibo_id', $id)
            ->where('created_at', $res->created_at)
            ->get();
        
        if(!$check->count() && !in_array($res->activation_code_type, $not_in)){
            if(!empty($res)){
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
    }
    /*
    public static function process_matching_bonus($id){
        $param_ = null;
        
        $res = Ibo::find($id);
        
        if(!empty($res)){
            // get current year
            $param_['current_year'] = Carbon::now()->year;
            
            // set current date to ibo registration date
            $date_ = new Carbon($res->created_at, 'Asia/Manila');
            $date_->setWeekStartsAt(Carbon::SATURDAY);
            $date_->setWeekEndsAt(Carbon::FRIDAY);
            
            // set parameters
            $param_['id'] = $id;
            $param_['start_date'] = $date_->startOfWeek()->toDateTimeString();
            $param_['end_date'] = $date_->endOfWeek()->toDateTimeString();
            $param_['year'] = $date_->year;
            
            do{
                
            }while(1);
            // loop from ibo year of registration to current year
            for($i = $param_['year']; $i <= $param_['current_year']; $i++){
                $date_->year = $i;
                
                // current year have different looping process
                if($date_->year == $param_['current_year']){
                    echo 'current year' . '<br>';
                    print_r($param_);
                    die();
                    /*
                    for($j = $date_->weekOfYear; $j >= 1; $j--){

                    }
                    
                }
                else{
                    echo 'not current year' . '<br>';
                    print_r($param_);
                    die();
                    /*
                    for($j = $date_->weekOfYear; $j >= 1; $j++){
                        
                    }
                    
                }
            }
        }
    }
    */
    
    public static function process_waiting($id){
        $check = Waiting::where('ibo_id', $id)->get();
        
        if(!$check->count()){
            $ids = null;
            $data['left'] = null;
            $data['right'] = null;
            $position_str = null;
            $not_in = ['FS', 'CD'];

            $res = Ibo::where('placement_id', $id)->get();
            
            if(!empty($res)){
                foreach($res as $value){
                    $counter = null;
                    
                    switch($value['attributes']['placement_position']){
                        case 'L':
                            $position_str = 'left';
                            break;

                        case 'R':
                            $position_str = 'right';
                            break;
                    }
                    
                    if(!in_array($value['attributes']['activation_code_type'], $not_in)) $counter[] = $value['attributes']['id'];

                    $ids = Ibo::where('placement_id', $value['attributes']['id'])->get();
                    
                    while(!empty($ids)){
                        $temp = null;

                        foreach($ids as $value_){
                            $res = Ibo::where('placement_id', $value_['attributes']['id'])->get();

                            if(!in_array($value_['attributes']['activation_code_type'], $not_in)) $counter[] = $value_['attributes']['id'];

                            if(!empty($res)) foreach($res as $val) $temp[] = $val;
                        }

                        $ids = $temp;
                    }

                    $data[$position_str] = $counter;
                }
            }
            
            if(!empty($data['left'])){
                $res_left = Ibo::whereIn('id', $data['left'])->orderBy('created_at', 'asc')->get();
                
                foreach($res_left as $key => $value){
                    //$data['new_left'][$key]['id'] = $value['attributes']['id'];
                    //$data['new_left'][$key]['created_at'] = $value['attributes']['created_at'];
                    $data['new_left'][] = $value['attributes']['id'];
                }
            }
            else $data['new_left'] = null;
            
            if(!empty($data['right'])){
                $res_right = Ibo::whereIn('id', $data['right'])->orderBy('created_at', 'asc')->get();
                
                foreach($res_right as $key => $value){
                    //$data['new_right'][$key]['id'] = $value['attributes']['id'];
                    //$data['new_right'][$key]['created_at'] = $value['attributes']['created_at'];
                    $data['new_right'][] = $value['attributes']['id'];
                }
            }
            else $data['new_right'] = null;

            $model = new Waiting;
            $model->ibo_id = $id;
            $model->left = !empty($data['new_left']) ? implode(',', $data['new_left']) : '';
            $model->right = !empty($data['new_right']) ? implode(',', $data['new_right']) : '';
            $model->save();

            //Logger::log($data);
        }
    }
}
