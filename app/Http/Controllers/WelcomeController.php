<?php

namespace App\Http\Controllers;

use App\Welcome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{

    public function index()
    {
        $category=DB::select("SELECT * FROM category");
        $title = DB::select("SELECT * FROM title");
        return view('welcome',['title'=>$title[0],'category'=>$category]);
    }

    public  function update(Request $request){
        $text = $request->request->all()["text"];

        DB::table('title')->where('id',1)
            ->update(['text'=>$text]);
        $category=DB::select("SELECT * FROM category");
        $title = DB::select("SELECT * FROM title");
        return view('welcome',['title'=>$title[0],'category'=>$category]);
    }
}
