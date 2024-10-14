<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'book_id' => $this->book_id,
            'image' => $this->book->image ? asset($this->book->image) : "",
            'price' => (string) $this->book->price,
            'offer' => (string) $this->book->offer,
            'name' => $this->book->name ?? '',
            'qty' => $this->qty
        ];
    }
}
