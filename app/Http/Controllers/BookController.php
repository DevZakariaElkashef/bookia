<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\bookRequest;
use App\Repositories\BookRepository;

class BookController extends Controller
{
    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $books = $this->bookRepository->index($request);
        return view('books.index', compact('books'));
    }

    public function search(Request $request)
    {
        $books = $this->bookRepository->search($request);
        return view('books.table', compact('books'))->render();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view("books.create", compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(bookRequest $request)
    {
        $this->bookRepository->store($request); // store book
        return to_route('books.index')->with('success', __("created_successffully"));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return to_route('books.edit');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(book $book)
    {
        $categories = Category::active()->get();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(bookRequest $request, book $book)
    {
        $this->bookRepository->update($request, $book);
        return to_route('books.index')->with('success', __("updated_successffully"));
    }

    public function toggleStatus(Request $request, book $book)
    {
        $book->update(['is_active' => $request->is_active]);
        return response()->json([
            'success' => true,
            'message' => __("updated_successffully")
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(book $book)
    {
        $this->bookRepository->delete($book);
        return to_route('books.index')->with('success', __("delete_successffully"));
    }

    public function delete(Request $request)
    {
        $this->bookRepository->deleteSelection($request);
        return to_route('books.index')->with('success', __("delete_successffully"));
    }
}
