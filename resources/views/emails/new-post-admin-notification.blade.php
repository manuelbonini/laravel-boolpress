<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <h1>Ciao Admin</h1>

    <p>E' stato creato un nuovo post. Il titolo del post è {{ $new_post->title }}, <a href="{{ route('admin.posts.show', ['post' => $new_post->id]) }}">Clicca qui</a> per vedere il post.</p>

</body>
</html>