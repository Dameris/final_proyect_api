<?php

namespace App\Http\Controllers;

use App\Models\Tshirt;
use App\Models\Jogger;
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

            $results = collect();

            if (!$category || $category === 'tshirt') {

                $tshirtsQuery = Tshirt::query();

                if ($query) {
                    $tshirtsQuery->where(function ($q) use ($query) {
                        $q->where('tshirt_name', 'like', "%{$query}%")
                            ->orWhere('tshirt_composition', 'like', "%{$query}%")
                            ->orWhere('tshirt_fit', 'like', "%{$query}%");
                    });
                }

                if ($minPrice) {
                    $tshirtsQuery->where('tshirt_price', '>=', $minPrice);
                }

                if ($maxPrice) {
                    $tshirtsQuery->where('tshirt_price', '<=', $maxPrice);
                }

                $tshirts = $tshirtsQuery->get();

                foreach ($tshirts as $item) {
                    $results->push([
                        'id' => $item->id,
                        'name' => $item->tshirt_name,
                        'price' => $item->tshirt_price,
                        'image' => $item->tshirt_img1,
                        'type' => 'tshirt'
                    ]);
                }
            }

            if (!$category || $category === 'jogger') {

                $joggersQuery = Jogger::query();

                if ($query) {
                    $joggersQuery->where(function ($q) use ($query) {
                        $q->where('jogger_name', 'like', "%{$query}%")
                            ->orWhere('jogger_composition', 'like', "%{$query}%")
                            ->orWhere('jogger_fit', 'like', "%{$query}%");
                    });
                }

                if ($minPrice) {
                    $joggersQuery->where('jogger_price', '>=', $minPrice);
                }

                if ($maxPrice) {
                    $joggersQuery->where('jogger_price', '<=', $maxPrice);
                }

                $joggers = $joggersQuery->get();

                foreach ($joggers as $item) {
                    $results->push([
                        'id' => $item->id,
                        'name' => $item->jogger_name,
                        'price' => $item->jogger_price,
                        'image' => $item->jogger_img1,
                        'type' => 'jogger'
                    ]);
                }
            }

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Search failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
