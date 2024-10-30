<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Order;
use App\Models\Store;
use App\Models\Book;
use App\Models\Section;
use App\Models\Category;
use App\Models\OrderStatus;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\OrderRepository;
use App\Http\Requests\Web\OrderRequest;

class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = $this->orderRepository->index($request);
        return view('orders.index', compact('orders'));
    }

    public function search(Request $request)
    {
        $orders = $this->orderRepository->search($request);
        return view('orders.table', compact('orders'))->render();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("orders.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $books = Book::active()->get();
        $orderStatuses = [
            [
                'id' => 0,
                'name' => __("pending")
            ],
            [
                'id' => 1,
                'name' => __("paid")
            ],
            [
                'id' => 2,
                'name' => __("faild")
            ]
        ];

        $paymentMethods = [
            [
                'id' => 0,
                'name' => __('online')
            ],
            [
                'id' => 1,
                'name' => __('wallet')
            ]
        ];

        return view('orders.show', compact('order', 'orderStatuses', 'books', 'paymentMethods'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function toggleStatus(Request $request, Order $order)
    {
        $order->update(['is_active' => $request->is_active]);
        return response()->json([
            'success' => true,
            'message' => __("updated_successffully")
        ]);
    }

    public function updateStatus(Request $request)
    {
        Order::findOrFail($request->id)->update(['status' => $request->status_id]);

        return back()->with('success', __("updated_successffully"));
    }

    public function updatePayment(Request $request)
    {
        Order::findOrFail($request->id)->update([
            'payment_type' => $request->payment_type,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', __("updated_successffully"));
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $this->orderRepository->delete($order);
        return to_route('orders.index')->with('success', __("delete_successffully"));
    }

    public function delete(Request $request)
    {
        $this->orderRepository->deleteSelection($request);
        return to_route('orders.index')->with('success', __("delete_successffully"));
    }
}
