<?php

namespace App;

use App\Ibo;
use App\CommissionRecord;
use App\Commission;
use Illuminate\Support\Facades\Auth;

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
}
