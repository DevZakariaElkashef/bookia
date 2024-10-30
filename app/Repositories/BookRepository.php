<?php

namespace App\Repositories;

use App\Models\Book;
use App\Traits\ImageUploadTrait;

class BookRepository
{
    use ImageUploadTrait;
    protected $limit;

    public function __construct()
    {
        $this->limit = 10;
    }

    public function index($request)
    {
        $books = Book::filter($request)->paginate($request->per_page ?? $this->limit);

        return $books;
    }

    public function search($request)
    {
        return Book::search($request->search)->paginate($request->per_page ?? $this->limit);
    }

    public function store($request)
    {
        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'books');
        }
        $data['app'] = 'mall';
        unset($data['_token']);
        return Book::create($data);
    }

    public function update($request, $book)
    {
        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'books', $book->image);
        }
        unset($data['_token'], $data['_method']);
        $book->update($data);
        return $book;
    }

    public function delete($book)
    {
        $book->delete();
        return true;
    }

    public function deleteSelection($request)
    {
        $ids = explode(',', $request->ids);
        Book::whereIn('id', $ids)->delete();
        return true;
    }
}
