<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slider_id' => $this->id,
            'title' => $this->title,
            'image' => asset($this->image),
            'book_id' => $this->book_id
        ];
    }
}
