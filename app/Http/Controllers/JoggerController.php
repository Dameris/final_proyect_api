<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoggerRequest;
use App\Http\Requests\UpdateJoggerStockRequest;
use App\Models\Jogger;
use App\Models\User;
use App\Http\Responses\ApiResponse;
use Inertia\Inertia;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException as EloquentModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JoggerController extends Controller
{
    public const items_per_page = 20;
    /**
     * List of Joggers.
     */
    public function index()
    {
        try {
            $joggers = Jogger::paginate(self::items_per_page);
            return inertia("Joggers/Index", ["joggers" => $joggers]);
        } catch (Exception $e) {
            return ApiResponse::error("Error in database", 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia("Joggers/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JoggerRequest $request)
    {
        try {
            $img = null;
            $img2 = null;

            // Verificar si se subió jogger_img1
            if ($request->hasFile("jogger_img1")) {
                $f = $request->file("jogger_img1");
                $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
                $f->storeAs("img/joggers", $img, "public");
            }

            // Verificar si se subió jogger_img2
            if ($request->hasFile("jogger_img2")) {
                $f2 = $request->file("jogger_img2");
                $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();
                $f2->storeAs("img/joggers", $img2, "public");
            }

            // Guardar en la base de datos
            Jogger::create([
                "jogger_name" => $request->jogger_name,
                "jogger_composition" => $request->jogger_composition,
                "jogger_fit" => $request->jogger_fit,
                "jogger_price" => $request->jogger_price,
                "jogger_img1" => $img,
                "jogger_img2" => $img2,
                "stock"  => 0
            ]);

            return redirect()->route('joggers.index');
        } catch (Exception $e) {
            return back()->withErrors(["error" => "Error in database: " . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Jogger $jogger)
    {
        return Inertia::render('Joggers/Show', [
            'jogger' => $jogger
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jogger $jogger)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return inertia('Joggers/Edit', [
            'jogger' => $jogger,
            'auth' => [
                'user' => $user ? $user->load('roles') : null,
            ],
        ]);
    }

    /**
     * Update a Jogger.
     */
    public function update(JoggerRequest $request, Jogger $jogger)
    {
        $data = $request->validated();

        // Lógica para jogger_img1
        if ($request->hasFile("jogger_img1")) {
            $f = $request->file("jogger_img1");
            $img = uniqid("img_") . "." . $f->getClientOriginalExtension();
            $f->storeAs("img/joggers", $img, "public");
            $data["jogger_img1"] = $img;
        } else {
            // Si no se subió una nueva imagen, MANTIENE LA IMAGEN EXISTENTE
            $data["jogger_img1"] = $jogger->jogger_img1;
        }

        // Lógica para jogger_img2
        if ($request->hasFile("jogger_img2")) {
            $f2 = $request->file("jogger_img2");
            $img2 = uniqid("img_") . "." . $f2->getClientOriginalExtension();
            $f2->storeAs("img/joggers", $img2, "public");
            $data["jogger_img2"] = $img2;
        } else {
            // Si no se subió una nueva imagen, MANTIENE LA IMAGEN EXISTENTE
            $data["jogger_img2"] = $jogger->jogger_img2;
        }

        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->hasRole('admin')) {
            $request->validate([
                'stock' => ['required', 'integer', 'min:0']
            ]);

            $data['stock'] = $request->stock;
        }

        $jogger->update($data);

        return Inertia::location(route('joggers.index'));
    }

    /**
     * Update a Jogger stock
     */
    public function updateStock(UpdateJoggerStockRequest $request, Jogger $jogger)
    {
        $jogger->update([
            'stock' => $request->stock
        ]);

        return back();
    }

    /**
     * Delete a T-shirt.
     */
    public function destroy(Jogger $jogger)
    {
        $jogger->delete();
        return redirect()->route('joggers.index');
    }

    public function getJoggerDetails($id)
    {
        $jogger = Jogger::find($id);

        if ($jogger) {
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
            // Buscar camisetas que coincidan con la consulta
            $results = Jogger::where('jogger_name', 'like', '%' . $query . '%')->get();
            return response()->json($results);
        } catch (Exception $e) {
            // Capturar cualquier error y devolverlo en la respuesta
            return response()->json(['error' => 'Error making the search', 'message' => $e->getMessage()], 500);
        }
    }
}
