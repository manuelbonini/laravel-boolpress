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
        <form action="{{ route('admin.posts.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <div class="form-group">
                <label for="title">Titolo del Post</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label for="content">Contenuto del Post</label>
                <textarea class="form-control" name="content" id="content" cols="30" rows="10">{{ old('title') }}</textarea>
            </div>

            <div class="form-group">
                <label for="category_id">Categoria</label>
                <select class="form-control" name="category_id" id="category_id">
                    <option value="">Nessuna Categoria</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' :'' }}>{{ $category->name }}</option>
                    @endforeach

                </select>
            </div>

            <div class="form-group">
                <h5>Tags</h5>
    
                @foreach ($tags as $tag)
                <div class="form-check">
                    <input class="form-check-input" name="tags[]" type="checkbox" value="{{ $tag->id }}" id="Tag-{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="Tag-{{ $tag->id }}">
                        {{$tag->name}}
                    </label>
                </div> 
                @endforeach
            </div>

            {{-- inserimento dei file --}}
            <div class="form-group">
                <label for="cover-image">Scegli immagine di copertina</label>
                <input type="file" class="form-control-file" name="cover-image" id="cover-image">
            </div>

            <input type="submit" class="btn btn-success" value="Crea Post">
        
        </form>

    </div>
@endsection