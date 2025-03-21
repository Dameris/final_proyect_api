<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;
    public $table = "orders";
    protected $fillable = ["user_id", "total_price", "status"];
    public $timestamps = false;

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
