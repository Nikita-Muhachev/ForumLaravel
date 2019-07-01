<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\Welcome;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $token= (isset($_GET['token']))?$_GET['token']:null;
        if ($token==$user->password){
            DB::table('users')->where('id',$user->id)
                ->update(['email_verified_at'=>1]);
        }else{
            if($user->email_verified_at==null) {
                \Mail::to(auth()->user())->send(new Welcome);
            }
        }

        return view('home',['user_y'=>auth()->user()->email_verified_at]);
    }
}
