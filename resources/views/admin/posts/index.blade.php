@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Gestione dei Post</h1>

        <div class="row">
            @foreach ($posts as $post)
                
            <div class="col-6">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                      <h5 class="card-title">{{ $post->title }}</h5>
                      <a href="{{ route('admin.posts.show', ['post' => $post->id]) }}" class="btn btn-primary">Vai al post</a>
                      <a href="{{ route('admin.posts.edit', ['post' => $post->id]) }}" class="btn btn-success">Modifica Post</a>
                      
                      <form action="{{ route('admin.posts.destroy', ['post' => $post->id]) }}" method="post">
                          @csrf
                          @method('DELETE')

                          <input type="submit" class="btn btn-danger" value="Cancella Post">
                      </form>
                    </div>
                </div>
            </div>

            @endforeach
        </div>

    </div>
@endsection