<?php

namespace App\Models;

use App\Traits\FilterScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory, FilterScope;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }


    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%")
            ->orWhere('price', 'like', "%{$search}%")
            ->orWhere('offer', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhereHas('category', function($category) use($search) {
                $category->where('name', 'like', "%{$search}%");
            });
    }

    public function finalPrice()
    {
        return $this->offer ?? $this->price;
    }
}
