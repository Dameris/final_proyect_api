<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ShippingController extends Controller
{
    public function show()
    {
        return Inertia::render('ShippingPage');
    }
}
