<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    public function all(): JsonResponse
    {
        $posts = Post::all()->makeHidden(['content', 'updated_at', 'created_at']);

        // Using response()->json() allows us to specify the format of our result
        return response()->json([
            'message' => 'Found posts successfully',
            'data' => $posts,
        ]);
    }

    public function find(Post $post): JsonResponse
    {
        return response()->json([
            'message' => 'Found post successfully',
            'data' => $post,
        ]);
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

        return response()->json([
            'message' => 'Post created',
        ], 201);
    }
}
