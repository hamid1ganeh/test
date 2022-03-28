@extends('layouts.layout')
@section('content')
<h1>{{ $post->title }}</h1>

<ul>
    @foreach($comments as $comment)
    <li>{{ $comment->text }}</li>
    @endforeach
</ul>

@auth
    <form action="{{ route('single.comment',$post) }}" method="post">
        <textarea name="text"></textarea>
        <button type="submit" name="register">ثبت</button>
    </form>
@endauth

@endsection
