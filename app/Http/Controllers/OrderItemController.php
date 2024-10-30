<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\OrderItem;
use App\Repositories\OrderItemRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderItemController extends Controller
{
    protected $orderItemRepository;

    public function __construct(OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    public function store(Request $request)
    {
        $book = Book::findOrFail($request->book_id);

        // Create a new order item
        $orderItem = $this->orderItemRepository->createOrderItem([
            'order_id' => $request->id,
            'book_id' => $request->book_id,
            'qty' => $request->qty,
            'price' => $book->offer ?? $book->price,
        ]);

        // Get the associated order
        $order = $orderItem->order;

        // Recalculate and update order totals
        $this->orderItemRepository->calculateOrderTotals($order);

        return back()->with('success', __('main.added_successfully'));
    }

    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);

        // Update the order item
        $this->orderItemRepository->updateOrderItem($orderItem, $request->all());

        // Get the associated order
        $order = $orderItem->order;

        // Recalculate and update order totals
        $this->orderItemRepository->calculateOrderTotals($order);

        return back()->with('success', __('main.updated_successfully'));
    }

    public function destroy($id)
    {
        $orderItem = OrderItem::findOrFail($id);

        // Get the associated order
        $order = $orderItem->order;

        // Delete the order item
        $this->orderItemRepository->deleteOrderItem($orderItem);

        // Recalculate and update order totals
        $this->orderItemRepository->calculateOrderTotals($order);

        return back()->with('success', __('main.deleted_successfully'));
    }
}
