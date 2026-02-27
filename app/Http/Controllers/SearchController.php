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

            if (!$query || strlen($query) < 2) {
                return response()->json([]);
            }

            $results = collect();

            $searchTshirts = str_contains($query, 'tshirt')
                || str_contains($query, 'shirt');

            $searchJoggers = str_contains($query, 'jogger')
                || str_contains($query, 'pants');

            if ($searchTshirts || (!$searchTshirts && !$searchJoggers)) {

                $tshirts = Tshirt::where('tshirt_name', 'like', "%{$query}%")
                    ->orWhere('tshirt_composition', 'like', "%{$query}%")
                    ->orWhere('tshirt_fit', 'like', "%{$query}%")
                    ->get();

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

            if ($searchJoggers || (!$searchTshirts && !$searchJoggers)) {

                $joggers = Jogger::where('jogger_name', 'like', "%{$query}%")
                    ->orWhere('jogger_composition', 'like', "%{$query}%")
                    ->orWhere('jogger_fit', 'like', "%{$query}%")
                    ->get();

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
