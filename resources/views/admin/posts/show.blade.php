@extends('layouts.app')

@section('content')
    <div class="container">
        
        <div>
            <a href="{{ route('admin.posts.edit', ['post' => $post->id]) }}" class="btn btn-success">Modifica Post</a>
        </div>

        <h1>{{ $post->title }}</h1>

        <div class="mt-3 mb-3"><strong>Slug:</strong> {{ $post->slug }}</div>

        
        @if ($post_category)
            
        <div class="mt-3 mb-3"><strong>Category:</strong> {{ $post_category->name }}</div>

        @endif

        <div class="mt-3 mb-3">
            <strong>Tags:</strong>

            @foreach ($post_tags as $tag)
                {{ $tag->name }}{{ $loop->last ? '' : ', ' }}
            @endforeach
        </div>

        <p>{{ $post->content }}</p>

    </div>
@endsection