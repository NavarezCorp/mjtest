<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Matching;
use Illuminate\Support\Facades\DB;
use App\Commission;

class FlushoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = null;
        $date_ = Carbon::now('Asia/Manila');
        
        $data['date'] = !empty($_GET['date']) ? $_GET['date'] : $date_->format('F j, Y');
        
        $data['selected_date'] = !empty($_GET['date']) ? date('Y-m-d', strtotime($_GET['date'])) : $date_->format('Y-m-d');
        
        $selected_date = new Carbon($data['selected_date']);
        
        $data['matching_bonus_amount'] = ($selected_date->toDateString() <= '2017-06-28') ? 200.00 : Commission::where('name', 'Matching Bonus')->first()->amount;
        
        $data['matchings'] = Matching::
            select(DB::raw('ibo_id, count(ibo_id) as matched_count'))
            ->whereRaw("DATE(datetime_matched) = DATE('" . $data['selected_date'] ."')")
            ->groupBy('ibo_id')
            ->get();
        
        return view('flushout.index', ['data'=>$data]);
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
        $date_ = Carbon::now('Asia/Manila');
        
        $data['ibo_id'] = $id;
        $data['date'] = $_GET['date'];
        $data['selected_date'] = date('Y-m-d', strtotime($_GET['date']));
        
        $selected_date = new Carbon($data['selected_date']);
        $data['matching_bonus_amount'] = ($selected_date->toDateString() <= '2017-06-28') ? 200.00 : Commission::where('name', 'Matching Bonus')->first()->amount;
        
        $data['matchings'] = Matching::
            whereRaw("ibo_id = " . $id . " and DATE(datetime_matched) = DATE('" . $data['selected_date'] ."')")
            ->orderBy('datetime_matched', 'asc')
            ->get();
        
        return view('flushout.details', ['data'=>$data]);
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
}
