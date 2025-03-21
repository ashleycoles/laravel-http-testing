<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

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

    public function create(CreatePostRequest $request)
    {
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
