<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SingleController extends Controller
{
     public function single(Post $post)
     {
         $comments = $post->comments()->latest()->paginate(15);
         return view('single',compact('post','comments'));
     }

      public function comment(Request $request,Post $post)
     {
         $request->validate([
            'text'=>'required'
         ]);

        $post->comments()->create([
            'user_id' => auth()->user()->id,
            'title'=> $request->input('title'),
            'text'=> $request->input('text')
        ]);

        //return redirect()->route('single',$post);

        return [
            'created'=>true
        ];
     }
}
