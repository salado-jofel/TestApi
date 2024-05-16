<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $posts = Post::all();
    return response()->json([
        'message' => 'Posts retrieved successfully!',
        'posts' => $posts,
        'status' => 200,
    ], 200);  
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    try {
        $validatedData = $request->validate(
        [
            'title' => 'required|string|max:10',
            'body' => 'required|string',
        ], 
        [
            'title.required'=> 'Title is required!',
            'title.string'=> 'Title must be a string!',
            'title.max'=> 'Title should not exceed 10 characters!',
            'body.required'=> 'Body is required!',
            'body.string'=> 'Body must be a string!',
        ],
    );
        $post = Post::create($validatedData);
        return response()->json([
            'message' => 'Post created successfully!',
            'post' => $post,
            'status' => 200,
        ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors(),
            'status' => 422,
        ], 422);
    }
}

    /**
     * Display the specified resource.
     */
   public function show(string $id)
{
    try {
        $post = Post::findOrFail($id);
        return response()->json([
            'message' => 'Post retrieved successfully!',
            'post' => $post,
            'status' => 200,
        ], 200);  // 200 OK status code

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Return a JSON response if the post is not found
        return response()->json([
            'message' => 'Post not found',
            'status' => 404,
        ], 404);  // 404 Not Found status code
    }
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    try {
       $validatedData = $request->validate(
        [
            'title' => 'required|string|max:10',
            'body' => 'required|string',
        ], 
        [
            'title.required'=> 'Title is required!',
            'title.string'=> 'Title must be a string!',
            'title.max'=> 'Title should not exceed 10 characters!',
            'body.required'=> 'Body is required!',
            'body.string'=> 'Body must be a string!',
        ],
    );
        $post = Post::findOrFail($id);
        $post->update($validatedData);
        return response()->json([
            'message' => 'Post updated successfully!',
            'post' => $post,
            'status' => 200,
        ], 200); 
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors(),
            'status' => 422,
        ], 422);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Post not found',
            'status' => 404,
        ], 404); 
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    try {
        $post = Post::findOrFail($id);
        $post = Post::destroy($id);
        return response()->json([
            'message' => 'Post deleted successfully!',
            'status' => 200,
        ], 200); 
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Post not found',
            'status' => 404,
        ], 404); 
    }
    }
}
