<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PostRequest;
use Illuminate\Support\Facades\Redirect;

class PostController extends Controller
{

    public function index()
    {
         $posts = Post::latest()->paginate(15);
          return view('admin.post.index',compact('posts'));
    }


    public function create()
    {
        $tags = Tag::latest()->get();
        return view('admin.post.create',compact('tags'));
    }


    public function store(PostRequest $request)
    {
        $post = auth()->user()->posts()->create([
            'title'=> $request->title,
            'description'=> $request->description,
            'image'=> $request->image,
        ]);

        $post->tags()->attach($request->input('tags'));

        return redirect(route('post.index'))
        ->with('message','new post has been created');
    }


    public function show(Post $post)
    {
        //
    }


    public function edit(Post $post)
    {
        $tags = Tag::latest()->get();
        return view('admin.post.edit',compact('post','tags'));
    }


    public function update(PostRequest $request, Post $post)
    {
        $post->update([
            'title'=> $request->title,
            'description'=> $request->description,
            'image'=> $request->image,
        ]);

       $post->tags()->sync($request->input('tags'));

        return redirect(route('post.index'))
        ->with('message','The post has been updated');
    }


    public function destroy(Post $post)
    {
        $post->tags()->detach();
        $post->comments()->delete();
        $post->delete();
        return redirect(route('post.index'))
        ->with('message','The post has been deleted.');
    }
}
