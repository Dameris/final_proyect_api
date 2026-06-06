<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CartController extends Controller
{
    // Muestra el carrito de compras
    public function showShoppingCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        $cart = Cart::where("user_id", $userId)
            ->with(['product.stocks'])
            ->get()
            ->each(function ($item) {
                if ($item->product) {
                    $type = strtolower($item->product->type);
                    $item->product->append([
                        "{$type}_name",
                        "{$type}_composition",
                        "{$type}_fit",
                        "{$type}_price",
                        "{$type}_img1",
                        "{$type}_img2",
                        "stock"
                    ]);
                }
            });

        return Inertia::render('ShoppingCart', [
            "cart" => $cart,
        ]);
    }

    // Agregar al carrito
    public function addToCart(Request $request, $type, $id)
    {
        $request->validate([
            "size" => "required|string",
        ]);

        $normalizedType = strtoupper($type);
        $allowedTypes = ['TSHIRT', 'JOGGER'];

        if (!in_array($normalizedType, $allowedTypes)) {
            return back()->with('alert', 'Invalid product type')->with('alertType', 'error');
        }

        $product = Product::where('type', strtolower($normalizedType))->find($id);

        if (!$product) {
            return back()->with('alert', 'Product not found')->with('alertType', 'error');
        }

        $stockRecord = $product->stocks()->where('size', $request->size)->first();

        if (!$stockRecord || $stockRecord->stock < 1) {
            return back()->with('alert', "Sorry, size {$request->size} is out of stock")->with('alertType', 'error');
        }

        DB::transaction(function () use ($product, $request, $stockRecord) {
            $stockRecord->decrement('stock');
            $cartItem = Cart::where("user_id", Auth::id())
                ->where("product_id", $product->id)
                ->where("product_type", $product->getMorphClass())
                ->where("size", $request->size)
                ->first();

            if ($cartItem) {
                $cartItem->increment("quantity");
            } else {
                Cart::create([
                    "user_id" => Auth::id(),
                    "product_id" => $product->id,
                    "product_type" => $product->getMorphClass(),
                    "size" => $request->size,
                    "quantity" => 1,
                ]);
            }
        });

        return back()->with('alert', 'Product added to cart')->with('alertType', 'success');
    }

    // Obtener el carrito
    public function getCart()
    {
        $cart = session()->get("cart", []);
        return response()->json(["cart" => $cart]);
    }

    // Actualizar la cantidad de un producto en el carrito (Controlando límites)
    public function updateCart(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$cartItem) {
            return back()->with('alert', 'Action unauthorized or item not found')->with('alertType', 'error');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $newQuantity = (int) $request->quantity;
        $oldQuantity = $cartItem->quantity;

        // Calculamos la diferencia
        // Ejemplo: Quiere 5, tenía 3 -> diferencia = 2 (hay que restar 2 del stock)
        // Ejemplo: Quiere 1, tenía 4 -> diferencia = -3 (hay que devolver 3 al stock)
        $difference = $newQuantity - $oldQuantity;

        if ($difference === 0) {
            return redirect()->back();
        }

        $product = $cartItem->product;
        $stockRecord = $product->stocks()->where('size', $cartItem->size)->first();

        if ($difference > 0 && (!$stockRecord || $stockRecord->stock < $difference)) {
            return back()->with('alert', "You cannot add more units. Only {$stockRecord->stock} left in stock.")
                ->with('alertType', 'error');
        }

        DB::transaction(function () use ($cartItem, $stockRecord, $difference, $newQuantity) {
            $stockRecord->decrement('stock', $difference);
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        });

        return redirect()->back()->with('alert', 'Quantity updated')->with('alertType', 'success');
    }

    // Eliminar un producto del carrito (Devolviendo stock)
    public function removeFromCart($id)
    {
        try {
            $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

            DB::transaction(function () use ($cartItem) {
                $stockRecord = $cartItem->product->stocks()->where('size', $cartItem->size)->first();

                if ($stockRecord) {
                    $stockRecord->increment('stock', $cartItem->quantity);
                }

                $cartItem->delete();
            });

            return redirect()->back()->with('alert', 'Item removed from cart')->with('alertType', 'success');
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', 'Error removing item')->with('alertType', 'error');
        }
    }

    // Vaciar por completo el carrito (Devolviendo todos los stocks de golpe)
    public function removeAllFromCart()
    {
        try {
            $cartItems = Cart::where("user_id", Auth::id())->get();

            DB::transaction(function () use ($cartItems) {
                foreach ($cartItems as $cartItem) {
                    $stockRecord = $cartItem->product->stocks()->where('size', $cartItem->size)->first();
                    if ($stockRecord) {
                        $stockRecord->increment('stock', $cartItem->quantity);
                    }
                    $cartItem->delete();
                }
            });

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function getCartItems()
    {
        $userId = Auth::id();

        $cartItems = Cart::where("user_id", $userId)
            ->with("product.stocks")
            ->get()
            ->each(function ($item) {
                if ($item->product) {
                    $type = strtolower($item->product->type);
                    $item->product->append([
                        "{$type}_name",
                        "{$type}_price",
                        "{$type}_img1",
                        "stock"
                    ]);
                }
            });

        return response()->json($cartItems);
    }
}
