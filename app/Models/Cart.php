<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function cartitems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function subTotal()
    {
        $sum = 0;

        foreach ($this->cartitems as $item) {
            $book = Book::find($item->book_id);
            $sum += ($item->qty * $book->finalPrice());
        }

        return $sum;
    }

    public function tax()
    {
        return $this->subTotal() * 0.15;
    }

    public function discount()
    {
        $discount = 0;
        $total = $this->subTotal() + $this->tax() + $this->shipping;

        if ($this->coupon) {
            $discount = $total * ($this->coupon->discount / 100);
        }

        return $discount;
    }

    public function total()
    {
        return ($this->subTotal() + $this->tax() + $this->shipping) - $this->discount();
    }
}
