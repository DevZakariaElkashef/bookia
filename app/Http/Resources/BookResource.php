<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isInMyWishList = false;
        $user = $request->user('sanctum');

        if ($user) {
            $isInMyWishList = $user->wishlists()->where('id', $this->id)->exists();
        }

        return [
            'book_id' => $this->id,
            'category_id' => $this->category_id,
            'category_name' => $this->category ? $this->category->name : '',
            'image' => asset($this->image),
            'name' => $this->name,
            'description' => $this->description ?? '',
            'price' => $this->price,
            'offer' => $this->offer,
            'is_in_my_wishlist' => $isInMyWishList
        ];
    }
}
