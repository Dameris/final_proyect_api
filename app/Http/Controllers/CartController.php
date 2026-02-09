<?php

namespace App\Http\Controllers;

use App\Models\Tshirt;
use App\Models\Jogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Cart;

class CartController extends Controller
{
    // Muestra el carrito de compras
    public function showShoppingCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        // Recuperar los productos del carrito con sus detalles
        $cart = Cart::where("user_id", $userId)
            ->with('product')
            ->get();

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

        $modelMap = [
            'TSHIRT' => Tshirt::class,
            'JOGGER' => Jogger::class,
        ];

        if (!isset($modelMap[$type])) {
            return back()->with('alert', 'Invalid product type')->with('alertType', 'error');
        }

        $product = $modelMap[$type]::find($id);

        if (!$product) {
            return back()->with('alert', 'Product not found')->with('alertType', 'error');
        }

        if ($product->stock < 1) {
            return back()->with('alert', 'Product out of stock')->with('alertType', 'error');
        }

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

        return back()->with('alert', 'Product added to cart')->with('alertType', 'success');
    }

    // Obtener el carrito
    public function getCart()
    {
        $cart = session()->get("cart", []);
        return response()->json(["cart" => $cart]);
    }

    // Actualizar la cantidad de un producto en el carrito
    public function updateCart(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        // Validar la cantidad
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->noContent();
    }

    // Eliminar un producto del carrito
    public function removeFromCart($id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            $cartItem->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            return response()->noContent();
        }
    }

    // Eliminar todos los productos del carrito
    public function removeAllFromCart()
    {
        try {
            Cart::where("user_id", Auth::id())->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            return response()->noContent();
        }
    }

    public function getCartItems()
    {
        $userId = Auth::id();

        // Obtener los productos del carrito con los detalles del producto
        $cartItems = Cart::where("user_id", $userId)
            ->with("product")
            ->get();

        return response()->json($cartItems);
    }
}
