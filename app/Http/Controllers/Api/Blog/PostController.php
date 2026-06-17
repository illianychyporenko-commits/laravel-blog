<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $items = BlogPost::with(['user', 'category'])->get(); // ← додав with()

        return response()->json(['data' => $items]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'required|unique:blog_posts|max:200',
            'slug'        => 'max:200',
            'content_raw' => 'required|string',
            'category_id' => 'required|integer',
        ]);

        $item = BlogPost::create($data);

        if ($item) {
            BlogPostAfterCreateJob::dispatch($item);
        }

        return response()->json($item, 201);
    }

    public function show(BlogPost $post): JsonResponse // ← BlogPost, не Post
    {
        $post->load(['user', 'category']);

        return response()->json(['data' => $post]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        //
        return response()->json(['success' => true]);
    }

    public function destroy(string $id): JsonResponse
    {
        $item = BlogPost::findOrFail($id);
        $item->delete();

        BlogPostAfterDeleteJob::dispatch($item);

        return response()->json(['success' => true]);
    }
}