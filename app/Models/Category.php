<?php

namespace App\Models;

use App\Traits\FilterScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, FilterScope;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%");
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
