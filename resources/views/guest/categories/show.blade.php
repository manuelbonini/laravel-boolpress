@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Ricette categoria: {{ $category->name }}</h1>

        <ul>
            @foreach($related_posts as $post)
                <li>
                    <a href="{{ route('post-page', ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection