<?php

namespace App\Http\Controllers;

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

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:2|max:70',
            'content' => 'required|string|min:50',
            'image' => 'nullable|string|url|max:255',
            'excerpt' => 'required|string|min:10|max:300',
            'author' => 'required|string|min:2|max:255',
        ]);

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
