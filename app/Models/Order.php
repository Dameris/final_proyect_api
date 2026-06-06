<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property float $total_price
 * @property string $status
 * @property string|null $shipping_name
 * @property string|null $shipping_address
 * @property string|null $shipping_city
 * @property string|null $shipping_zip
 * @property string|null $shipping_phone
 */
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;
    public $table = "orders";

    // Habilitamos los nuevos campos para la asignación masiva
    protected $fillable = [
        "user_id",
        "total_price",
        "status",
        "shipping_name",
        "shipping_address",
        "shipping_city",
        "shipping_zip",
        "shipping_phone"
    ];

    public $timestamps = false;

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
