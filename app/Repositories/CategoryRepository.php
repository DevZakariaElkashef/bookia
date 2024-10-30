<?php

namespace App\Repositories;

use App\Models\Category;
use App\Traits\ImageUploadTrait;

class CategoryRepository
{
    use ImageUploadTrait;
    protected $limit;

    public function __construct()
    {
        $this->limit = 10;
    }

    public function index($request)
    {
        $categories = Category::filter($request)->paginate($request->per_page ?? $this->limit);

        return $categories;
    }

    public function search($request)
    {
        return Category::search($request->search)->paginate($request->per_page ?? $this->limit);
    }

    public function store($request)
    {
        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'categories');
        }
        $data['app'] = 'mall';
        unset($data['_token']);
        return Category::create($data);
    }

    public function update($request, $category)
    {
        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'categories', $category->image);
        }
        unset($data['_token'], $data['_method']);
        $category->update($data);
        return $category;
    }

    public function delete($category)
    {
        $category->delete();
        return true;
    }

    public function deleteSelection($request)
    {
        $ids = explode(',', $request->ids);
        Category::whereIn('id', $ids)->delete();
        return true;
    }
}
