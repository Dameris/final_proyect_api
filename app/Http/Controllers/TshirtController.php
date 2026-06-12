<?php

namespace App\Http\Controllers;

use App\Http\Requests\TshirtRequest;
use App\Http\Requests\UpdateTshirtStockRequest;
use App\Models\Product;
use App\Models\ProductStock;
use App\Http\Responses\ApiResponse;
use Inertia\Inertia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TshirtController extends Controller
{
    public const items_per_page = 20;

    public function index()
    {
        try {
            $paginator = Product::where('type', 'tshirt')->paginate(self::items_per_page);

            $paginator->getCollection()->each(function ($product) {
                $product->append(['tshirt_name', 'tshirt_composition', 'tshirt_fit', 'tshirt_price', 'tshirt_img1', 'tshirt_img2', 'stock']);
            });

            return inertia("Tshirts/Index", ["tshirts" => $paginator]);
        } catch (Exception $e) {
            return ApiResponse::error("Error in database", 500);
        }
    }

    public function create()
    {
        return inertia("Tshirts/Create", [
            'compositions' => TshirtRequest::COMPOSITIONS,
            'fits' => TshirtRequest::FITS,
            'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
        ]);
    }

    public function store(TshirtRequest $request)
    {
        try {
            $img = null;
            $img2 = null;

            // Guardar directamente en public/img/tshirts
            if ($request->hasFile("tshirt_img1")) {
                $f = $request->file("tshirt_img1");
                $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
                $f->move(public_path("img/tshirts"), $img);
            }

            if ($request->hasFile("tshirt_img2")) {
                $f2 = $request->file("tshirt_img2");
                $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();
                $f2->move(public_path("img/tshirts"), $img2);
            }

            $product = Product::create([
                "name" => $request->tshirt_name,
                "type" => "tshirt",
                "composition" => $request->tshirt_composition,
                "fit" => $request->tshirt_fit,
                "price" => $request->tshirt_price,
                "img1" => $img,
                "img2" => $img2,
            ]);

            $validSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

            // Inicializamos el stock por tallas
            foreach ($validSizes as $size) {
                $quantity = 0;
                if ($request->has('stock') && isset($request->stock[$size])) {
                    $quantity = max(0, intval($request->stock[$size]));
                }

                ProductStock::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'stock' => $quantity
                ]);
            }

            return redirect()->route('tshirts.index');
        } catch (Exception $e) {
            return back()->withErrors(["error" => "Error in database: " . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $tshirt = Product::where('type', 'tshirt')->with('stocks')->findOrFail($id);
        $tshirt->append(['tshirt_name', 'tshirt_composition', 'tshirt_fit', 'tshirt_price', 'tshirt_img1', 'tshirt_img2', 'stock']);

        return Inertia::render('Tshirts/Show', [
            'tshirt' => $tshirt
        ]);
    }

    public function edit($id)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $tshirt = Product::where('type', 'tshirt')->findOrFail($id);
        $tshirt->append(['tshirt_name', 'tshirt_composition', 'tshirt_fit', 'tshirt_price', 'tshirt_img1', 'tshirt_img2', 'stock']);

        return inertia('Tshirts/Edit', [
            'tshirt' => $tshirt,
            'auth' => [
                'user' => $user ? $user->load('roles') : null,
            ],
            'compositions' => TshirtRequest::COMPOSITIONS,
            'fits' => TshirtRequest::FITS,
            'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
        ]);
    }

    public function update(TshirtRequest $request, $id)
    {
        $tshirt = Product::where('type', 'tshirt')->findOrFail($id);
        $request->validated();

        $data = [
            "name" => $request->input("tshirt_name", $tshirt->name),
            "composition" => $request->input("tshirt_composition", $tshirt->composition),
            "fit" => $request->input("tshirt_fit", $tshirt->fit),
            "price" => $request->input("tshirt_price", $tshirt->price),
        ];

        if ($request->hasFile("tshirt_img1")) {
            $f = $request->file("tshirt_img1");
            $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
            $f->move(public_path("img/tshirts"), $img);
            $data["img1"] = $img;
        }

        if ($request->hasFile("tshirt_img2")) {
            $f2 = $request->file("tshirt_img2");
            $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();
            $f2->move(public_path("img/tshirts"), $img2);
            $data["img2"] = $img2;
        }

        $tshirt->update($data);

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->hasRole('admin') && $request->has('stock')) {
            $request->validate(['stock' => 'required|array']);
            foreach ($request->stock as $size => $quantity) {
                ProductStock::updateOrCreate(
                    ['product_id' => $tshirt->id, 'size' => $size],
                    ['stock' => max(0, intval($quantity))]
                );
            }
        }

        return Inertia::location(route('tshirts.index'));
    }

    /**
     * Actualiza el stock por tallas individuales
     */
    public function updateStock(UpdateTshirtStockRequest $request, $id)
    {
        $tshirt = Product::where('type', 'tshirt')->findOrFail($id);

        $request->validate([
            'size' => 'required|string',
            'stock' => 'required|integer|min:0'
        ]);

        ProductStock::updateOrCreate(
            ['product_id' => $tshirt->id, 'size' => $request->size],
            ['stock' => $request->stock]
        );

        return back();
    }

    public function destroy($id)
    {
        $tshirt = Product::where('type', 'tshirt')->findOrFail($id);
        $tshirt->delete();

        return redirect()->route('tshirts.index');
    }

    public function getTshirtDetails($id)
    {
        $tshirt = Product::where('type', 'tshirt')->find($id);

        if ($tshirt) {
            $tshirt->append(['tshirt_name', 'tshirt_composition', 'tshirt_fit', 'tshirt_price', 'tshirt_img1', 'tshirt_img2', 'stock']);
            return response()->json($tshirt);
        }

        return response()->json(["error" => "T-shirt not found"], 404);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }

        try {
            $results = Product::where('type', 'tshirt')
                ->where('name', 'like', '%' . $query . '%')
                ->get()
                ->each(function ($item) {
                    $item->append(['tshirt_name', 'tshirt_composition', 'tshirt_fit', 'tshirt_price', 'tshirt_img1', 'tshirt_img2', 'stock']);
                });

            return response()->json($results);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error making the search', 'message' => $e->getMessage()], 500);
        }
    }
}
