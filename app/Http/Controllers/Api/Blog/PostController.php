<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = BlogPost::all();

        return $items;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd('МЕТОД СТОР ПРАЦЮЄ!');
        $data = $request->validate([
            'title' => 'required|unique:blog_posts|max:200',
            'slug' => 'max:200',
            'content_raw' => 'required|string',
            'category_id' => 'required|integer',
        ]);

        $item = BlogPost::create($data);

        if ($item) {
            BlogPostAfterCreateJob::dispatch($item);
        }

        return response()->json($item, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = BlogPost::findOrFail($id);

        $item->delete();

        BlogPostAfterDeleteJob::dispatch($item);

        return response()->json(['success' => true]);
    }
}
