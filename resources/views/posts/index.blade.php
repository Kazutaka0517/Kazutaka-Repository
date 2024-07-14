<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Blog</title>
        <!--これコメントタグ下のコードはlinkタグでフォント指定 -->  
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>

    <body>
        <h1>Blog name</h1>
        <a href="/posts.create">create</a>
        <div class='posts'>
            @foreach ($posts as $post)
                <div class='post'>

                    <a href="/posts.{{$post->id}}"><h2 class='title'>{{ $post->title }}</h2></a>

                    <p class='body'>{{$post->body}}</p>
                </div>
            @endforeach
        </div>
        <div class='paginate'> 
            {{$posts->links()}}
        </div>
    </body>
</html>