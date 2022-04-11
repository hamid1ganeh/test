<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()->paginate(15);
        return view('admin.tag.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tag.create');
    }


    public function store(TagRequest $request)
    {
        Tag::create([
            'name' => $request->input('name')
        ]);

        return redirect(route('tag.index'))
            ->with('message', 'new tag has been created');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tag.edit', compact('tag'));
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update([
            'name' => $request->input('name')
        ]);

        return redirect(route('tag.index'))
            ->with('message', 'the tag has been updated');
    }


    public function destroy(Tag $tag)
    {
        $tag->posts()->detach();

        $tag->delete();

        return redirect(route('tag.index'))
            ->with('message', 'the tag has been deleted');
    }
}
