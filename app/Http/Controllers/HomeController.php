<?php

namespace App\Http\Controllers;

use App\Logger;
use Illuminate\Http\Request;
use App\Helper;
use Illuminate\Support\Facades\Auth;
use App\Ibo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Ibo::find(Auth::user()->ibo_id);
        $data['app'] = $model->app;
        $data['agp'] = $model->agp;
        $data['adsc'] = Helper::get_adsc(Auth::user()->ibo_id);
        $data['aisc'] = Helper::get_aisc(Auth::user()->ibo_id);
        $data['amb'] = Helper::get_amb(Auth::user()->ibo_id);

        return view('home', ['data'=>$data]);
    }
}
