<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;
    public $table = "order_items";
    protected $fillable = ["order_id", "tshirt_id", "quantity", "price"];
    public $timestamps = false;

    // Relación inversa con Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación con Tshirt (suponiendo que tienes este modelo)
    public function tshirt()
    {
        return $this->belongsTo(Tshirt::class);
    }
}
