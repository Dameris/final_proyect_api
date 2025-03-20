<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Tshirt;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class HomeController extends Controller
{
    public function index(Tshirt $tshirt)
    {
        return Inertia::render('Home', [
            'tshirts' => Tshirt::orderBy("id", "asc")->limit(1)->get()
        ]);
    }
}
