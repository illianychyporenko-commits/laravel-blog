<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use Illuminate\Http\Request; 
use App\Models\BlogPost; 
use Illuminate\Support\Str;

class PostController extends BaseController
{
    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private BlogCategoryRepository $blogCategoryRepository
    ) {
        // parent::__construct();
    }
    public function index()
    {
        return $this->blogPostRepository->getAllWithPaginate();
    }

    /**
     * Створення нової статті
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (!empty($data['is_published'])) {
            $data['published_at'] = Carbon::now();
        }

        $data['content_html'] = $data['content_raw'];

        $item = BlogPost::create($data);

        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Статтю успішно створено',
                'id' => $item->id
            ], 201);
        } else {
            return response()->json(['message' => 'Помилка створення статті'], 500);
        }
    }

    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) { // якщо ід не знайдено
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all(); // отримаємо масив даних, які надійшли з форми

        $result = $item->update($data); // оновлюємо дані об'єкта і зберігаємо в БД

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }
}