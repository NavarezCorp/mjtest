<?php

namespace App;

use App\Ibo;
use App\CommissionRecord;
use App\Commission;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Logger;
use App\Waiting;
use App\Matching;
use DB;

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
}
