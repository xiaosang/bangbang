<?php

namespace App\Http\Controllers\Admin\Forum;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Admin\Note;

class NoteController extends Controller{


    public function get_list(Request $request){
        $page_size = $request->page_size;
        return responseToJson(0, 'success', Note::get_list($page_size));
        
    }

    public function get_comment(Request $request){
        $page_size = $request->page_size;
        return responseToJson(0, 'success', Note::get_comment($page_size,$request->note_id));
        
    }
    public function comment_delete(Request $request){
        
        $id = $request->id;
        $note_id = $request->note_id;
        $res = Note::comment_delete($id,$note_id);
        if($res){
            return responseToJson(0,"删除成功");
        }else{
            return responseToJson(1, "删除失败");
        }

    }
    
    public function note_delete(Request $request){
        
        $id = $request->id;
        $res = Note::comment_delete($id);
        if($res){
            return responseToJson(0,"删除成功");
        }else{
            return responseToJson(1, "删除失败");
        }

    }
}