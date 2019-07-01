<?php

namespace App\Http\Controllers;

use App\Threads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ThreadsController extends Controller
{
    public function dashboard() {
        return view('support');
    }



    public function index(){
        if ($user_id = auth()->user()!=null) {
            $search_t= (isset($_GET['text']))?$_GET['text']:null;
            $search_c=(isset($_GET['category']))?$_GET['category']:null;
            if ( $search_t==null && $search_c==null){
                $threads = DB::select("SELECT * FROM threads ORDER BY id DESC");
            }else{
                if ($search_t!=null) {
                    $threads = DB::table('threads')
                        ->where('title', 'like', '%' . $search_t . '%')
                        ->orderByDesc('id')
                        ->get();
                }else{
                    $threads = DB::table('threads')
                        ->where('slug', 'like', '%' . $search_c . '%')
                        ->orderByDesc('id')
                        ->get();
                }
               // $threads = DB::select("SELECT * FROM threads WHERE title LIKE '%$search%' ORDER BY id DESC");
            }
            $user_id = auth()->user()->id;
            return view('thread.thread', ['threads' => $threads, 'user_id' => $user_id,'search'=>$search_t.$search_c]);
        }
        else return view('auth.login');
    }
    

}
