<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = strtolower(trim($request->query('query')));
            $category = $request->query('category'); // tshirt | jogger | null
            $minPrice = $request->query('min_price');
            $maxPrice = $request->query('max_price');

            // Inicializamos la consulta unificada
            $productsQuery = Product::query();

            // Filtrar por categoría (tipo de producto) si viene definida
            if ($category && in_array(strtolower($category), ['tshirt', 'jogger'])) {
                $productsQuery->where('type', strtolower($category));
            }

            // Búsqueda por texto unificada en las columnas comunes
            if ($query) {
                $productsQuery->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('composition', 'like', "%{$query}%")
                        ->orWhere('fit', 'like', "%{$query}%");
                });
            }

            // Rangos de precio aplicados sobre la columna única 'price'
            if ($minPrice) {
                $productsQuery->where('price', '>=', $minPrice);
            }

            if ($maxPrice) {
                $productsQuery->where('price', '<=', $maxPrice);
            }

            $products = $productsQuery->get();
            $results = collect();

            // Estructuramos la respuesta mapeando las claves exactas que el frontend espera recibir
            foreach ($products as $item) {
                $results->push([
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'price' => $item->price,
                    'image' => $item->img1,
                    'type'  => $item->type // 'tshirt' o 'jogger'
                ]);
            }

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Search failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
