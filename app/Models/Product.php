<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'type', 'composition', 'fit', 'price', 'img1', 'img2'];

    // Cargamos siempre la relación de stocks para evitar consultas duplicadas (N+1)
    protected $with = ['stocks'];

    public function stocks()
    {
        return $this->hasMany(ProductStock::class)
                    ->orderByRaw("FIELD(size, 'XS', 'S', 'M', 'L', 'XL', 'XXL')");
    }

    // Busca el stock de una talla específica de manera directa
    public function stockForSize($size)
    {
        return $this->stocks->where('size', $size)->first()?->stock ?? 0;
    }

    public function getTshirtNameAttribute()
    {
        return $this->name;
    }

    public function getJoggerNameAttribute()
    {
        return $this->name;
    }

    public function getTshirtCompositionAttribute()
    {
        return $this->composition;
    }

    public function getJoggerCompositionAttribute()
    {
        return $this->composition;
    }

    public function getTshirtFitAttribute()
    {
        return $this->fit;
    }

    public function getJoggerFitAttribute()
    {
        return $this->fit;
    }

    public function getTshirtPriceAttribute()
    {
        return $this->price;
    }

    public function getJoggerPriceAttribute()
    {
        return $this->price;
    }

    public function getTshirtImg1Attribute()
    {
        return $this->img1 ? asset('img/tshirts/' . $this->img1) : null;
    }

    public function getJoggerImg1Attribute()
    {
        return $this->img1 ? asset('img/joggers/' . $this->img1) : null;
    }

    public function getTshirtImg2Attribute()
    {
        return $this->img2 ? asset('img/tshirts/' . $this->img2) : null;
    }

    public function getJoggerImg2Attribute()
    {
        return $this->img2 ? asset('img/joggers/' . $this->img2) : null;
    }

    /**
     * Calcula el stock total dinámicamente sumando todas las tallas
     */
    public function getStockAttribute()
    {
        return $this->stocks->sum('stock');
    }
}
