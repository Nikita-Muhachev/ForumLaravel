<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{

    public function  item(){
        $id=$_GET['id'];
        $item = DB::select("SELECT * FROM threads WHERE id=$id ");
        $replies = DB::select("SELECT * FROM replies WHERE thread_id=$id ORDER BY id DESC");
        $user_id = auth()->user()->id;
        return view('thread.item',['item'=>$item,"user_id"=>$user_id,"replies"=>$replies]);
    }

    public function create(Request $request)
    {
        $id=$_GET['id'];
        $del= (isset($_GET['delete']))?$_GET['delete']:null;
        $user_id = auth()->user()->id;
        if ($del==null){
            $text = $request->request->all()["text"];
            $params=['text' => $text, 'creator_id' => $user_id, 'thread_id' => $id];
            if($_FILES["file"]["name"]!=null) {
               if ($_FILES['file']['error']!=0){
                   return redirect()->route('item',['id'=>$id,'error'=>2]);
               }
                $uploaddir = '/home/a0298844/domains/muhachev.site/public/img/';
                $file_name = (count(scandir($uploaddir))-2).$_FILES["file"]["name"];
                $uploadfile = $uploaddir . basename($file_name);
                move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
                $uploadfile=explode(".",$file_name);
                $file_tipe=array_pop ($uploadfile);
                $params['file_name' ] =  $file_name;
                $params['file_path'] = $file_tipe;
            }
            if ($text==null && $_FILES["file"]["name"]==null)
            {
                return redirect()->route('item',['id'=>$id,'error'=>1]);
            }
            DB::table('replies')->insertGetId(
                $params
            );
            return redirect()->route('item',['id'=>$id]);
        } else {
            $item = DB::select("SELECT * FROM threads WHERE id=(SELECT thread_id FROM replies WHERE id=$id)")[0];
            $file_name=DB::select("SELECT file_name FROM replies WHERE id=$id")[0];
            if ($file_name->file_name!=null) {
                unlink('img/' . $file_name->file_name);
            }
            DB::delete("DELETE FROM replies WHERE id = $id");
            return redirect()->route('item',['id'=> $item->id]);
        }
    }
}
