<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;
    public $table = "carts";
    protected $fillable = ["user_id", "tshirt_id", "size", "quantity"];
    public $timestamps = false;

    public function tshirt()
    {
        return $this->belongsTo(Tshirt::class, 'tshirt_id');
    }
}
