<?php

namespace App\Http\Controllers\Api;

use App\Traits\BaseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use BaseApi;

    public function index(Request $request)
    {
        $user = $request->user();
        $data = OrderResource::collection($user->orders);

        return $this->sendResponse($data, '');
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $order = $user->orders->where('id', $id)->first();

        if (!$order) {
            return $this->sendResponse('', 'Order Not Found', 404);
        }

        $data = new OrderResource($order);
        return $this->sendResponse($data, '');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'lat' => 'required|string',
            'lng' => 'required|string',
            'address' => 'required|string',
            'payment_type' => 'required|boolean',
            'transaction_id' => 'required_if:payment_type,1'
        ]);

        if ($validator->fails()){
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }

        $user = $request->user();
        $cart = $user->cart;

        if (!$cart) {
            return $this->sendResponse('', 'Your Cart Is Empty', 400);
        }

        $order = $user->orders()->create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'address' => $request->address,
            'payment_type' => $request->payment_type,
            'transaction_id' => $request->transaction_id ?? null,
            'status' => 0,

            'coupon_id' => $cart->coupon_id,
            'sub_total' => $cart->subTotal(),
            'tax' => $cart->tax(),
            'discount' => $cart->discount(),
            'shipping' => $cart->shipping,
            'total' => $cart->total()
        ]);


        foreach($cart->cartitems as $item) {
            $order->orderItems()->create([
                'book_id' => $item->book_id,
                'qty' => $item->qty,
                'price' => $item->book->finalPrice(),
            ]);
        }

        $cart->delete();
        return $this->sendResponse('', 'Order Created Successfully');

    }
}
