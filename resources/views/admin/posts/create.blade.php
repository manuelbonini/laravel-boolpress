@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Crea un nuovo Post</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form action="{{ route('admin.posts.store') }}" method="post">
            @csrf
            @method('POST')

            <div class="form-group">
                <label for="title">Titolo del Post</label>
                <input type="email" class="form-control" id="title" name="title" value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label for="content">Contenuto del Post</label>
                <textarea class="form-control" name="content" id="content" cols="30" rows="10">{{ old('title') }}</textarea>
            </div>

            <input type="submit" class="btn btn-success" value="Crea Post">
        
        </form>

    </div>
@endsection