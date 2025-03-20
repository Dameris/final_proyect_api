<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tshirt extends Model
{
    /** @use HasFactory<\Database\Factories\TshirtFactory> */
    use HasFactory;
    public $table = "tshirts";
    protected $fillable = ["tshirt_name", "tshirt_composition", "tshirt_fit", "tshirt_price", "tshirt_img1", "tshirt_img2"];
    public $timestamps = false;
}
