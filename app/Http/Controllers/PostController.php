<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user) {
            $posts = Post::where("user_id", $user->id)->paginate(10);
            return response()->json([
                'message' => 'Posts retrieved successfully!',
                'posts' => $posts,
                'status' => 200,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not authenticated!',
                'status' => 401,
            ], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $user = Auth::user();
        try {
            $new_post = array(
                'user_id' => $user->id,
                'title' => $request->title,
                'body' => $request->body,
            );
            $created_post = Post::create($new_post);
            return response()->json([
                'message' => 'Posts created successfully!',
                'posts' => $created_post,
                'status' => 200,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found or does not belong to the authenticated user.'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        try {
            $posts = Post::where('user_id', $user->id)->findOrFail($id);
            return response()->json([
                'message' => 'Posts retrieved by ID successfully!',
                'posts' => $posts,
                'status' => 200,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found or does not belong to the authenticated user.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {
        $user = Auth::user();
        try {
            $post = Post::where('user_id', $user->id)->findOrFail($id);
            $post->title = $request->title;
            $post->body = $request->body;
            $post->save();
            return response()->json([
                'message' => 'Posts updated successfully!',
                'posts' => $post,
                'status' => 200,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found or does not belong to the authenticated user.'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        try {
            $post = Post::where('user_id', $user->id)->findOrFail($id);
            $post->destroy($id);
            return response()->json([
                'message' => 'Posts deleted successfully!',
                'posts' => $post,
                'status' => 200,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found or does not belong to the authenticated user.'], 404);
        }
    }
}
