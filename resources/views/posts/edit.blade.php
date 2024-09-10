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
                Edit
        </x-slot>    
            <body class="antialiased">
                <h1>Blog Name</h1>
               <form action="/posts/{{ $post->id }}" method="POST">
                   @csrf
                   @method('PUT')
                   <div class="title">
                       <h2>Title</h2>
                       <input type="text" name=post[title] placeholder="タイトル" value={{ $post->title}}>
                       <p class="title__error" style="color:red">{{$errors->first('post.title') }}</p>
                    
                   </div>
                 <div class="body">
                     <h2>Body</h2>
                     <textarea name="post[body]"placeholder="今日も一日お疲れ様でした。">{{ $post->body }}</textarea>
                     <p class="body__error" style="color:red">{{$errors->first('post.body') }}</p>
                 </div>
                 <input type="submit" value="update"/>
                </form>
                <div class="footer">
            　　<a href="/posts/{{ $post->id }}">戻る</a>
                </div>
            </body>
    </x-app-layout>        
</html>