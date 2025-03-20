<?php

namespace App\Http\Controllers;

use App\Http\Requests\TshirtRequest;
use App\Models\Tshirt;
use App\Http\Responses\ApiResponse;
use Inertia\Inertia;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException as EloquentModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TshirtController extends Controller
{
    public const items_per_page = 20;
    /**
     * List of T-shirts.
     */
    public function index()
    {
        try {
            $tshirts = Tshirt::paginate(self::items_per_page);
            return inertia("Tshirts/Index", ["tshirts" => $tshirts]);
        } catch (Exception $e) {
            return ApiResponse::error("Error in database", 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia("Tshirts/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TshirtRequest $request)
    {
        try {
            $img = null;
            $img2 = null;

            // Verificar si se subió tshirt_img1
            if ($request->hasFile("tshirt_img1")) {
                $f = $request->file("tshirt_img1");
                $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
                $f->storeAs("img/tshirts", $img, "public");
            }

            // Verificar si se subió tshirt_img2
            if ($request->hasFile("tshirt_img2")) {
                $f2 = $request->file("tshirt_img2");
                $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();
                $f2->storeAs("img/tshirts", $img2, "public");
            }

            // Guardar en la base de datos
            Tshirt::create([
                "tshirt_name" => $request->tshirt_name,
                "tshirt_composition" => $request->tshirt_composition,
                "tshirt_fit" => $request->tshirt_fit,
                "tshirt_price" => $request->tshirt_price,
                "tshirt_img1" => $img,
                "tshirt_img2" => $img2
            ]);

            return Inertia::location(route('tshirts.index'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Error in database: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tshirt $tshirt)
    {
        return Inertia::render('Tshirts/Show', [
            'tshirt' => $tshirt
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tshirt $tshirt)
    {
        return inertia("Tshirts/Edit", ["tshirt" => $tshirt]);
    }

    /**
     * Update a T-shirt.
     */
    public function update(TshirtRequest $request, Tshirt $tshirt)
    {
        if ($request->hasFile('tshirt_img1')) {
            $f = $request->file('tshirt_img1');
            $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
            $f->storeAs("img/tshirts", $img, "public");
            $data['tshirt_img1'] = $img;
        }

        if ($request->hasFile('tshirt_img2')) {
            $f2 = $request->file('tshirt_img2');
            $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();
            $f2->storeAs("img/tshirts", $img2, "public");
            $data['tshirt_img2'] = $img2;
        }

        $tshirt->update([
            "tshirt_name" => $request->tshirt_name,
            "tshirt_composition" => $request->tshirt_composition,
            "tshirt_fit" => $request->tshirt_fit,
            "tshirt_price" => $request->tshirt_price,
            "tshirt_img1" => $img,
            "tshirt_img2" => $img2
        ]);

        return Inertia::location(route('tshirts.edit', $tshirt->id));
    }

    /**
     * Delete a T-shirt.
     */
    public function destroy(Tshirt $tshirt)
    {
        $tshirt->delete();
        return redirect()->route('tshirts.index');
    }
}
