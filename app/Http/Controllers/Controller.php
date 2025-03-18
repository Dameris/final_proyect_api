<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

abstract class Controller
{
    public function __construct()
    {
        // Compartir la información de autenticación con todos los controladores que extiendan esta clase
        Inertia::share([
            'auth' => fn() => auth()
        ]);
    }
}
