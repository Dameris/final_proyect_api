<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $request->validate([
                'min_price' => 'nullable|numeric|min:0',
                'max_price' => 'nullable|numeric|min:0',
            ]);

            $query = strtolower(trim($request->query('query')));
            $category = $request->query('category');
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
                $keywords = array_filter(preg_split('/[\s,]+/', $query));

                foreach ($keywords as $word) {
                    $productsQuery->where(function ($q) use ($word) {
                        $q->where('name', 'like', "%{$word}%")
                            ->orWhere('composition', 'like', "%{$word}%")
                            ->orWhere('fit', 'like', "%{$word}%")
                            ->orWhere('type', 'like', "%{$word}%");
                    });
                }
            }

            if ($minPrice !== null) {
                $productsQuery->where('price', '>=', $minPrice);
            }

            if ($maxPrice !== null) {
                $productsQuery->where('price', '<=', $maxPrice);
            }

            $products = $productsQuery->get();

            $results = $products->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'price' => $item->price,
                    'image' => $item->img1,
                    'type'  => $item->type
                ];
            });

            return response()->json($results);
        } catch (ValidationException $e) {
            return response()->json([
                'error'   => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Search failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
