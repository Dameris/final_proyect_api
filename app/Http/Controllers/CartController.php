<?php

namespace App\Http\Controllers;

use App\Models\Tshirt;
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
            ->with('tshirt')
            ->get();

        return Inertia::render('ShoppingCart', [
            "cart" => $cart,
        ]);
    }

    // Agregar al carrito
    public function addToCart(Request $request, $id)
    {
        $request->validate([
            "size" => "required|string",
        ]);

        $product = Tshirt::find($id);

        if (!$product) {
            return redirect()->back()->with('alert', 'Product not found')->with('alertType', 'error');
        }

        // Verifica si el producto con esa talla ya está en el carrito del usuario
        $cartItem = Cart::where("user_id", Auth::id())
            ->where("tshirt_id", $product->id)
            ->where("size", $request->size)
            ->first();

        if ($cartItem) {
            $cartItem->increment("quantity");
            return redirect()->back()->with('alert', 'Product quantity increased in cart')->with('alertType', 'success');
        } else {
            Cart::create([
                "user_id" => Auth::id(),
                "tshirt_id" => $product->id,
                "size" => $request->size,
                "quantity" => 1,
            ]);
            return redirect()->back()->with('alert', 'Product added to the cart')->with('alertType', 'success');
        }
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

        return redirect()->back()->with("alert", "Cart updated successfully")->with("alertType", "success");
    }

    // Eliminar un producto del carrito
    public function removeFromCart($id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            $cartItem->delete();
            return redirect()->back()->with('alert', 'Item removed from cart')->with('alertType', 'success');
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', 'Failed to remove item')->with('alertType', 'error');
        }
    }

    // Eliminar todos los productos del carrito
    public function removeAllFromCart()
    {
        try {
            Cart::where("user_id", Auth::id())->delete();
            return redirect()->back()->with('alert', 'All items removed from cart')->with('alertType', 'success');
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', 'Failed to remove all items')->with('alertType', 'error');
        }
    }

    public function getCartItems()
    {
        $userId = Auth::id();

        // Obtener los productos del carrito con los detalles de la camiseta
        $cartItems = Cart::where("user_id", $userId)
            ->with("tshirt")
            ->get();

        return response()->json($cartItems);
    }
}
