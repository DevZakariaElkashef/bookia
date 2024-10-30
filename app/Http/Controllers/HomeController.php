<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $categoryCount = Category::count();
        $bookCount = Book::count();
        $orderCount = Order::count();

        return view('index', compact('userCount', 'categoryCount', 'bookCount', 'orderCount'));
    }
}
