<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'email' => $this->email ?? '',
            'image' => $this->image ? asset($this->image) : '',
            'address' => $this->address ?? '',
            'lat' => $this->lat ?? '',
            'lng' => $this->lng ,
        ];
    }
}
