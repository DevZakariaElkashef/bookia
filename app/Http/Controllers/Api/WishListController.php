<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Traits\BaseApi;
use Illuminate\Http\Request;

class WishListController extends Controller
{
    use BaseApi;

    public function index(Request $request)
    {
        $user = $request->user();

        $wishlists = [];

        if ($user->wishlists()) {
            $wishlists = $user->wishlists()->pluck('book_id')->toArray();
        }

        $books = Book::whereIn('id', $wishlists)->get();

        $data = BookResource::collection($books);

        return $this->sendResponse($data, '');
    }

    public function toggle(Request $request, $id)
    {
        $user = $request->user();
        $book = Book::find($id);

        if (!$book) {
            return $this->sendResponse('', 'Book Not Found', 404);
        }


        $wishList = $user->wishlists->where('book_id', $id)->first();

        if ($wishList) {
            $wishList->delete();
            $message = 'Book removed from wishlist';
        } else {
            $user->wishlists()->create([
                'book_id' => $id
            ]);
            $message = 'Book added to wishlist';
        }

        return $this->sendResponse('', $message);
    }
}
