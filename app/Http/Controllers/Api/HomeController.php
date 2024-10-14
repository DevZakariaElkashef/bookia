<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SliderResource;
use App\Models\Book;
use App\Models\Category;
use App\Models\Slider;
use App\Traits\BaseApi;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    use BaseApi;

    public function index()
    {
        $sliders = SliderResource::collection(Slider::all());
        $categories = CategoryResource::collection(Category::all());
        $books = BookResource::collection(Book::all());


        $data = [
            'sliders' => $sliders,
            'categories' => $categories,
            'books' => $books
        ];


        return $this->sendResponse($data, '');
    }


    public function search(Request $request)
    {
        $books = Book::whereHas('category', function ($category) use ($request) {
            $category->where('name', 'like', "%$request->input%");
        })->orWhere('name', 'like', "%$request->input%")
            ->orWhere('price', 'like', "%$request->input%")
            ->orWhere('offer', 'like', "%$request->input%")
            ->orWhere('offer', 'like', "%$request->input%")->get();


        $data = BookResource::collection($books);
        return $this->sendResponse($data, '');
    }


    public function getByCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->sendResponse('', 'Category Not Found!', 404);
        }

        $data = BookResource::collection($category->books);
        return $this->sendResponse($data, '');
    }
}
