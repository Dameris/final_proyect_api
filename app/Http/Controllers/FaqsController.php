<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use Inertia\Inertia;

class FaqsController extends Controller
{
    public function show()
    {
        return Inertia::render('FaqsPage', [
            'faqs' => Faq::orderBy('created_at', 'desc')->get()
        ]);
    }
}