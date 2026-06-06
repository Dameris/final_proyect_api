<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoggerRequest;
use App\Http\Requests\UpdateJoggerStockRequest;
use App\Models\Product;
use App\Models\ProductStock;
use App\Http\Responses\ApiResponse;
use Inertia\Inertia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JoggerController extends Controller
{
    public const items_per_page = 20;

    public function index()
    {
        try {
            $paginator = Product::where('type', 'jogger')->paginate(self::items_per_page);

            $paginator->getCollection()->each(function ($product) {
                $product->append(['jogger_name', 'jogger_composition', 'jogger_fit', 'jogger_price', 'jogger_img1', 'jogger_img2', 'stock']);
            });

            return inertia("Joggers/Index", ["joggers" => $paginator]);
        } catch (Exception $e) {
            return ApiResponse::error("Error in database", 500);
        }
    }

    public function create()
    {
        return inertia("Joggers/Create", [
            'compositions' => JoggerRequest::COMPOSITIONS,
            'fits' => JoggerRequest::FITS,
            'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
        ]);
    }

    public function store(JoggerRequest $request)
    {
        try {
            $img = null;
            $img2 = null;

            if ($request->hasFile("jogger_img1")) {
                $f = $request->file("jogger_img1");
                $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
                $f->storeAs("img/joggers", $img, "public");
            }

            if ($request->hasFile("jogger_img2")) {
                $f2 = $request->file("jogger_img2");
                $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();
                $f2->storeAs("img/joggers", $img2, "public");
            }

            $product = Product::create([
                "name" => $request->jogger_name,
                "type" => "jogger",
                "composition" => $request->jogger_composition,
                "fit" => $request->jogger_fit,
                "price" => $request->jogger_price,
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

            return redirect()->route('joggers.index');
        } catch (Exception $e) {
            return back()->withErrors(["error" => "Error in database: " . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $jogger = Product::where('type', 'jogger')->with('stocks')->findOrFail($id);
        $jogger->append(['jogger_name', 'jogger_composition', 'jogger_fit', 'jogger_price', 'jogger_img1', 'jogger_img2', 'stock']);

        return Inertia::render('Joggers/Show', [
            'jogger' => $jogger
        ]);
    }

    public function edit($id)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $jogger = Product::where('type', 'jogger')->findOrFail($id);
        $jogger->append(['jogger_name', 'jogger_composition', 'jogger_fit', 'jogger_price', 'jogger_img1', 'jogger_img2', 'stock']);

        return inertia('Joggers/Edit', [
            'jogger' => $jogger,
            'auth' => [
                'user' => $user ? $user->load('roles') : null,
            ],
            'compositions' => JoggerRequest::COMPOSITIONS,
            'fits' => JoggerRequest::FITS,
            'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
        ]);
    }

    public function update(JoggerRequest $request, $id)
    {
        $jogger = Product::where('type', 'jogger')->findOrFail($id);
        $request->validated();

        $data = [
            "name" => $request->input("jogger_name", $jogger->name),
            "composition" => $request->input("jogger_composition", $jogger->composition),
            "fit" => $request->input("jogger_fit", $jogger->fit),
            "price" => $request->input("jogger_price", $jogger->price),
        ];

        if ($request->hasFile("jogger_img1")) {
            $f = $request->file("jogger_img1");
            $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
            $f->storeAs("img/joggers", $img, "public");
            $data["img1"] = $img;
        }

        if ($request->hasFile("jogger_img2")) {
            $f2 = $request->file("jogger_img2");
            $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();
            $f2->storeAs("img/joggers", $img2, "public");
            $data["img2"] = $img2;
        }

        $jogger->update($data);

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->hasRole('admin') && $request->has('stock')) {
            $request->validate(['stock' => 'required|array']);
            foreach ($request->stock as $size => $quantity) {
                ProductStock::updateOrCreate(
                    ['product_id' => $jogger->id, 'size' => $size],
                    ['stock' => max(0, intval($quantity))]
                );
            }
        }

        return Inertia::location(route('joggers.index'));
    }

    /**
     * Actualiza el stock por tallas individuales
     */
    public function updateStock(UpdateJoggerStockRequest $request, $id)
    {
        $jogger = Product::where('type', 'jogger')->findOrFail($id);

        // Se espera un request con estructura: 'size' => 'Oversize', 'stock' => 15
        $request->validate([
            'size' => 'required|string',
            'stock' => 'required|integer|min:0'
        ]);

        ProductStock::updateOrCreate(
            ['product_id' => $jogger->id, 'size' => $request->size],
            ['stock' => $request->stock]
        );

        return back();
    }

    public function destroy($id)
    {
        $jogger = Product::where('type', 'jogger')->findOrFail($id);
        $jogger->delete();

        return redirect()->route('joggers.index');
    }

    public function getJoggerDetails($id)
    {
        $jogger = Product::where('type', 'jogger')->find($id);

        if ($jogger) {
            $jogger->append(['jogger_name', 'jogger_composition', 'jogger_fit', 'jogger_price', 'jogger_img1', 'jogger_img2', 'stock']);
            return response()->json($jogger);
        }

        return response()->json(["error" => "Jogger not found"], 404);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }

        try {
            $results = Product::where('type', 'jogger')
                ->where('name', 'like', '%' . $query . '%')
                ->get()
                ->each(function ($item) {
                    $item->append(['jogger_name', 'jogger_composition', 'jogger_fit', 'jogger_price', 'jogger_img1', 'jogger_img2', 'stock']);
                });

            return response()->json($results);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error making the search', 'message' => $e->getMessage()], 500);
        }
    }
}
