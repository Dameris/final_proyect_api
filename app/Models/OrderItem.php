<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;
    public $table = "order_items";
    protected $fillable = ["order_id", "product_id", "product_type", "quantity", "size", "price"];
    public $timestamps = false;

    // Relación inversa con Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->morphTo();
    }
}
