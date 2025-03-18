<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqsController extends Controller
{
    public function show()
    {
        return Inertia::render('FaqsPage');
    }
}
