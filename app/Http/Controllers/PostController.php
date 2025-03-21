<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function all(Request $request)
    {
        if (! $request->search) {
            $posts = Post::paginate(20);
        } else {
            // SELECT * FROM posts WHERE title LIKE '%hello%';
            $posts = Post::where('title', 'LIKE', "%$request->search%")
                ->orWhere('excerpt', 'LIKE', "%$request->search%")
                ->orWhere('content', 'LIKE', "%$request->search%")
                ->orWhere('author', 'LIKE', "%$request->search%")
                ->orderBy('title', 'desc')
                ->paginate(20);
        }

        return view('blog', [
            'posts' => $posts,
        ]);
    }

    public function find(Post $post)
    {
        return view('singlePost', [
            'post' => $post,
        ]);
    }

    public function addForm()
    {
        return view('addPost');
    }

    public function create(CreatePostRequest $request)
    {
        $newPost = new Post;

        $newPost->title = $request->title;
        $newPost->content = $request->content;
        $newPost->image = $request->image;
        $newPost->excerpt = $request->excerpt;
        $newPost->author = $request->author;
        $newPost->save();

        return redirect('/blog');
    }
}
