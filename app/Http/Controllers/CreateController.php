<?php

namespace App\Http\Controllers;

use App\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\Welcome;


class CreateController extends Controller
{
    public function dashboard2() {
        if ($user_id = auth()->user()!=null) {
            $category = DB::select("SELECT * FROM category");
            $user_y=auth()->user()->email_verified_at;
            if(auth()->user()->email_verified_at==null) {
                \Mail::to(auth()->user())->send(new Welcome);
            }
            return view('thread.create',['category'=>$category,'user_y'=>$user_y]);
        } else return view('auth.login');
    }

    public function create(){
        return view('thread.create',[
            'thread'=>[]
        ]);
    }

    public function store(Request $request)
    {

        $del= (isset($_GET['delete']))?$_GET['delete']:null;
        if ($del!=null){
            $id=$_GET['id'];
            $replies=DB::select("SELECT file_name FROM replies WHERE thread_id = $id");

            foreach ($replies as $file_name) {
                if ($file_name->file_name != null) {
                    unlink('img/' . $file_name->file_name);
                }
            }
            DB::delete("DELETE FROM threads WHERE id = $id");
            DB::delete("DELETE FROM replies WHERE thread_id = $id");
            return redirect()->route('thread');
        }else {
            $text = $request->request->all()["text"];
            $title = $request->request->all()["title"];
            $category = $request->request->all()["category"];
            $user_id = auth()->user()->id;
            DB::table('threads')->insertGetId(
                array('title' => $title, 'text' => $text, 'creator_id' => $user_id,'slug'=>$category)
            );
            return redirect()->route('thread');
        }
    }
}
