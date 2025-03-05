<?php

namespace App\Http\Controllers;

use App\Models\Tshirt;
use App\Http\Responses\ApiResponse;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException as EloquentModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TshirtController extends Controller
{
    /**
     * List of T-shirts.
     */
    public function index()
    {
        try {
            $tshirts = Tshirt::all();
            return ApiResponse::success("List of T-shirts", 200, $tshirts);
        } catch (Exception $e) {
            return ApiResponse::error("Error in data base", 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "tshirt_name" => "required|unique:tshirt",
                "tshirt_composition" => "required",
                "tshirt_fit" => "required",
                "tshirt_price" => "required|numeric",
                "tshirt_img1" => "required|file|mimes:jpg,jpeg,png,gif",
                "tshirt_img2" => "required|file|mimes:jpg,jpeg,png,gif",
            ]);

            $f = $request->file("tshirt_img1");
            $f2 = $request->file("tshirt_img2");

            $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
            $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();

            $f->move(public_path("img"), $img);
            $f2->move(public_path("img"), $img2);

            Tshirt::create([
                "tshirt_name" => $request->tshirt_name,
                "tshirt_composition" => $request->tshirt_composition,
                "tshirt_fit" => $request->tshirt_fit,
                "tshirt_price" => $request->tshirt_price,
                "tshirt_img1" => $img,
                "tshirt_img2" => $img2
            ]);

            return ApiResponse::success("T-shirt created succesfully", 201);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
            return ApiResponse::error("Validation error", 422, $errors);
        } catch (Exception $e) {
            return ApiResponse::error("Error in data base", 500);
        }
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
            return ApiResponse::error("Error in data base", 500);
        }
    }

    /**
     * Update a T-shirt.
     */
    public function update(Request $request, $id)
    {
        try {
            $tshirt = Tshirt::where(["id" => $id])->firstOrFail();
            $img = $tshirt->tshirt_img1;
            $img2 = $tshirt->tshirt_img2;

            $request->validate([
                "tshirt_name" => ["required", Rule::unique("tshirt")->ignore($tshirt)],
                "tshirt_composition" => "required",
                "tshirt_fit" => "required",
                "tshirt_price" => "required|numeric",
                "tshirt_img1" => "required|file|mimes:jpg,jpeg,png,gif",
                "tshirt_img2" => "required|file|mimes:jpg,jpeg,png,gif",
            ]);

            if ($img)
                @unlink(public_path("img") . "/" . $img);

            if ($img2)
                @unlink(public_path("img") . "/" . $img2);

            $f = $request->file("tshirt_img1");
            $f2 = $request->file("tshirt_img2");

            $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
            $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();

            $f->move(public_path("img"), $img);
            $f2->move(public_path("img"), $img2);

            $tshirt->tshirt_name = $request->tshirt_name;
            $tshirt->tshirt_composition = $request->tshirt_composition;
            $tshirt->tshirt_fit = $request->tshirt_fit;
            $tshirt->tshirt_price = $request->tshirt_price;
            $tshirt->tshirt_img1 = $img;
            $tshirt->tshirt_img2 = $img2;
            $tshirt->update();

            return ApiResponse::success("T-shirt update succesfully", 202);
        } catch (EloquentModelNotFoundException $e) {
            return ApiResponse::error("T-shirt not found", 404);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
            return ApiResponse::error("Validation error", 422, $errors);
        } catch (Exception $e) {
            return ApiResponse::error("Error in data base", 500);
        }
    }

    /**
     * Delete a T-shirt.
     */
    public function destroy($id)
    {
        try {
            $tshirt = Tshirt::where(["id" => $id])->firstOrFail();
            $tshirt->delete();

            return ApiResponse::success("Tshirt eliminated", 200);
        } catch (EloquentModelNotFoundException $e) {
            return ApiResponse::error("T-shirt not found", 404);
        } catch (Exception $e) {
            return ApiResponse::error("Error in data base", 500);
        }
    }
}
