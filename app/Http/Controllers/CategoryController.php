<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $this->categoryRepository->index($request);
        return view('categories.index', compact('categories'));
    }

    public function search(Request $request)
    {
        $categories = $this->categoryRepository->search($request);
        return view('categories.table', compact('categories'))->render();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("categories.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $this->categoryRepository->store($request); // store category
        return to_route('categories.index')->with('success', __("created_successffully"));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return to_route('categories.edit');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryRepository->update($request, $category);
        return to_route('categories.index')->with('success', __("updated_successffully"));
    }

    public function toggleStatus(Request $request, Category $category)
    {
        $category->update(['is_active' => $request->is_active]);
        return response()->json([
            'success' => true,
            'message' => __("updated_successffully")
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->categoryRepository->delete($category);
        return to_route('categories.index')->with('success', __("delete_successffully"));
    }

    public function delete(Request $request)
    {
        $this->categoryRepository->deleteSelection($request);
        return to_route('categories.index')->with('success', __("delete_successffully"));
    }
}
