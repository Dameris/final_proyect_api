<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Event;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $featuredTshirts = Product::where('type', 'tshirt')
            ->orderBy("id", "asc")
            ->limit(1)
            ->get()
            ->each(function ($tshirt) {
                $tshirt->append(['tshirt_name', 'tshirt_composition', 'tshirt_fit', 'tshirt_price', 'tshirt_img1', 'tshirt_img2', 'stock']);
            });

        return Inertia::render('Home', [
            'tshirts' => $featuredTshirts,
            'events' => Event::where('end_date', '>=', Carbon::now())
                ->orWhere(function ($query) {
                    $query->whereNull('end_date')
                        ->where('start_date', '>=', Carbon::now());
                })
                ->orderBy('start_date')
                ->take(3)
                ->get(),
        ]);
    }
}
