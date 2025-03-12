<?php

namespace App\Http\Controllers;

use App\Http\Requests\TshirtRequest;
use App\Models\Tshirt;
use App\Http\Responses\ApiResponse;
use Inertia\Response;
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
        // try {
        // $f = $request->file("tshirt_img1");
        // $f2 = $request->file("tshirt_img2");

        // $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
        // $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();

        // $f->storeAs("img", $img, "public");
        // $f2->storeAs("img", $img2, "public");

        Tshirt::create([
            "tshirt_name" => $request->tshirt_name,
            "tshirt_composition" => $request->tshirt_composition,
            "tshirt_fit" => $request->tshirt_fit,
            "tshirt_price" => $request->tshirt_price,
            // "tshirt_img1" => $img,
            // "tshirt_img2" => $img2
        ]);

        return redirect()->route('tshirts.index');
        // ApiResponse::success("T-shirt created succesfully", 201);
        // } catch (ValidationException $e) {
        //     $errors = $e->validator->errors()->toArray();
        //     return ApiResponse::error("Validation error", 422, $errors);
        // } catch (Exception $e) {
        //     return ApiResponse::error("Error in database", 500);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $tshirt = Tshirt::where(["id" => $id])->firstOrFail();
            return ApiResponse::success("Showing a t-shirt", 200, $tshirt);
        } catch (EloquentModelNotFoundException $e) {
            return ApiResponse::error("T-shirt not found", 404);
        } catch (Exception $e) {
            return ApiResponse::error("Error in database", 500);
        }
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
        $tshirt->update($request->validated());
        return redirect()->route('tshirts.index');
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
