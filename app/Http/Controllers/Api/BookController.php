<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Traits\BaseApi;
use Illuminate\Http\Request;

class BookController extends Controller
{
    use BaseApi;

    public function show(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->sendResponse('', 'Book Not Found!', 404);
        }


        $data = new BookResource($book);
        return $this->sendResponse($data, '');
    }
}
