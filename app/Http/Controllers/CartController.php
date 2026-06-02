<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Recuperar los productos del carrito agregando los accesores esperados por la UI
        $cart = Cart::where("user_id", $userId)
            ->with('product')
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

        // Buscamos el producto en la tabla unificada asegurando su tipo
        $product = Product::where('type', strtolower($normalizedType))->find($id);

        if (!$product) {
            return back()->with('alert', 'Product not found')->with('alertType', 'error');
        }

        if ($product->stock < 1) {
            return back()->with('alert', 'Product out of stock')->with('alertType', 'error');
        }

        $cartItem = Cart::where("user_id", Auth::id())
            ->where("product_id", $product->id)
            ->where("product_type", $product->getMorphClass()) // Resolverá dinámicamente según el proveedor
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

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->back();
    }

    // Eliminar un producto del carrito
    public function removeFromCart($id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            $cartItem->delete();
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    // Eliminar todos los productos del carrito
    public function removeAllFromCart()
    {
        try {
            Cart::where("user_id", Auth::id())->delete();
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function getCartItems()
    {
        $userId = Auth::id();

        $cartItems = Cart::where("user_id", $userId)
            ->with("product")
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
