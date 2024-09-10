<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Blog</title>
                <!--これコメントタグ下のコードはlinkタグでフォント指定 -->  
                <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
            </head>
    <x-app-layout> 
        <x-slot name="header">
                Show 
        </x-slot>  
            <body>
                <h1 class='title'>
                    {{$post->title}}
                </h1>
                <div class='content'>
                    <div class='content_post'>
                         <h3>本文</h3>
                         <p class='body'>{{ $post->body }}</p>
                     </div>
                <div class='edit'>
                    <a href="/posts/{{ $post->id }}/edit">edit</a>
                </div>
                <a href="/categories/{{ $post->category->id }}">{{ $post->category->name }}</a>
                <div class='footer'>
                    <a href="/posts">戻る</a>
                </div>     
            </body>
    </x-app-layout>     
</html>