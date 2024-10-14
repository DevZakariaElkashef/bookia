<?php

namespace App\Http\Controllers\Api;

use App\Models\Coupon;
use App\Traits\BaseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use BaseApi;

    public function index(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart;

        if (!$cart) {
            $cart = $user->cart()->create();
        }

        $data = [
            'items' => CartItemResource::collection($cart->cartitems),
            'sub_total' => number_format($cart->subTotal(), 2),
            'tax' => number_format($cart->tax(), 2),
            'shipping' => number_format($cart->shipping, 2),
            'discount' => number_format($cart->discount(), 2),
            'total' => number_format($cart->total(), 2),
        ];

        return $this->sendResponse($data, '');
    }

    public function store(Request $request)
    {
        $item = $this->getItem($request);
        $cart = $this->getCart($request->user());

        if ($item) {
            $item->update(['qty' => $item->qty + 1]);
            $message = 'Book Quantaty Increase successfully';
        } else {
            $cart->cartitems()->create([
                'book_id' => $request->book_id,
                'qty' => 1
            ]);
            $message = 'Book Added to cart success';
        }


        return $this->sendResponse('', $message);
    }


    public function decrease(Request $request)
    {
        $item = $this->getItem($request);

        if ($item && $item->qty > 1) {
            $item->update(['qty' => $item->qty - 1]);
            $message = 'Book Quantaty Decrease successfully';
        } else {
            $item->delete();
            $message = 'Book Removed from cart success';
        }


        return $this->sendResponse('', $message);
    }


    public function delete(Request $request)
    {
        $item = $this->getItem($request);
        if (!$item) {
            return $this->sendResponse('', 'Book not found in the cart', 404);
        }
        $item->delete();
        return $this->sendResponse('', 'Book Removed from cart success');
    }

    public function checkCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:coupons,code'
        ]);

        if ($validator->fails()){
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }

        $user = $request->user();
        $coupon = Coupon::where('code', $request->code)->first();

        $cart = $this->getCart($user);
        $cart->update(['coupon_id' => $coupon->id]);

        return $this->sendResponse('', 'Coupon Added Success');
    }

    public function calckShipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required'
        ]);

        if ($validator->fails()){
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }

        $user = $request->user();
        $cart = $user->cart;

        $cart->update(['shipping' => rand(100, 500)]);
        return $this->sendResponse('', 'Shipping Added to cart sumary');
    }


    private function getItem($request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id'
        ]);

        if ($validator->fails()){
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }

        $user = $request->user();
        $cart = $this->getCart($user);

        return $cart->cartitems->where('book_id', $request->book_id)->first();
    }


    private function getCart($user)
    {
        $cart = $user->cart;

        if (!$cart) {
            $cart = $user->cart()->create();
        }

        return $cart;
    }
}
