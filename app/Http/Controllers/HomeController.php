<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Tshirt;
use App\Models\Event;

class HomeController extends Controller
{
    public function index(Tshirt $tshirt)
    {
        return Inertia::render('Home', [
            'tshirts' => Tshirt::orderBy("id", "asc")->limit(1)->get(),
            'events' => Event::orderBy('start_date')->take(3)->get(),
        ]);
    }
}
