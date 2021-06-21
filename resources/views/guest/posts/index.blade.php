@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Ultime NEWS</h1>

        <div class="row">
            @foreach ($posts as $post)
                
            <div class="col-6">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                      <h5 class="card-title">{{ $post->title }}</h5>
                      <a href="{{ route('post-page', ['slug' => $post->slug]) }}" class="btn btn-primary">Leggi il post</a>
                    </div>
                </div>
            </div>

            @endforeach
        </div>

    </div>
@endsection