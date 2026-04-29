<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Tshirt;
use App\Models\Event;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Tshirt $tshirt)
    {
        return Inertia::render('Home', [
            'tshirts' => Tshirt::orderBy("id", "asc")->limit(1)->get(),
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
