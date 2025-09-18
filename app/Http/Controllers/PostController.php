<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    /**
     * Display a listing of posts with eager loading and sorting.
     * 
     * This demonstrates:
     * - Eager loading to prevent N+1 queries
     * - Sorting by latest posts
     * - Pagination
     */
    public function index(): JsonResponse
    {
        // Eager load relationships to prevent N+1 queries
        $posts = Post::with(['user', 'comments'])
            ->published()
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Posts retrieved successfully with eager loading'
        ]);
    }

    /**
     * Display a specific post with all relationships.
     */
    public function show(Post $post): JsonResponse
    {
        // Eager load all relationships for a single post
        $post->load(['user', 'comments.user']);

        return response()->json([
            'success' => true,
            'data' => $post,
            'message' => 'Post retrieved successfully with all relationships'
        ]);
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'published' => 'boolean',
            ]);

            $post = Post::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'user_id' => auth()->id(),
                'published' => $validated['published'] ?? false,
            ]);

            // Load relationships for the response
            $post->load(['user', 'comments']);

            return response()->json([
                'success' => true,
                'data' => $post,
                'message' => 'Post created successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Get posts with advanced eager loading example.
     * 
     * This demonstrates complex eager loading scenarios:
     * - Multiple levels of relationships
     * - Conditional loading
     * - Custom sorting
     */
    public function advancedExample(): JsonResponse
    {
        // Example 1: Load posts with user and comments, ordered by latest
        $postsWithComments = Post::with(['user', 'comments'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Example 2: Load posts with user and comment count
        $postsWithCounts = Post::with(['user'])
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->get();

        // Example 3: Load posts with latest comment
        $postsWithLatestComment = Post::with(['user', 'comments' => function ($query) {
            $query->latest()->limit(1);
        }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'posts_with_comments' => $postsWithComments,
                'posts_with_counts' => $postsWithCounts,
                'posts_with_latest_comment' => $postsWithLatestComment,
            ],
            'message' => 'Advanced eager loading examples retrieved successfully',
            'explanation' => [
                'posts_with_comments' => 'Posts with user and all comments, ordered by latest',
                'posts_with_counts' => 'Posts with user and comment count, ordered by comment count',
                'posts_with_latest_comment' => 'Posts with user and only the latest comment'
            ]
        ]);
    }
}
