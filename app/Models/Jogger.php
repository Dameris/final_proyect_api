<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $jogger_name
 * @property float $jogger_price
 * @property int $stock
 */

class Jogger extends Model
{
    /** @use HasFactory<\Database\Factories\JoggerFactory> */
    use HasFactory;
    public $table = "joggers";
    protected $fillable = ["jogger_name", "jogger_composition", "jogger_fit", "jogger_price", "jogger_img1", "jogger_img2", "stock"];
    public $timestamps = false;
}
