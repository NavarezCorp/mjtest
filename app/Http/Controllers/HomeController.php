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
        Logger::log($data);
        return view('home', ['data'=>$data]);
    }
}
