<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function processOrder(Request $request)
    {
        $cartItems = $request->cart;

        if (!$cartItems || count($cartItems) === 0) {
            return redirect()->back()->with('alert', 'The cart is empty')->with('alertType', 'error');
        }

        // Simular la creación de una orden
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => collect($cartItems)->sum(fn($item) => $item['tshirtDetails']['tshirt_price'] * $item['quantity']),
            'status' => 'Processed',
        ]);

        // Agregar los productos a la orden
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'tshirt_id' => $item['tshirt_id'],
                'quantity' => $item['quantity'],
                'price' => $item['tshirtDetails']['tshirt_price'],
            ]);
        }

        // Vaciar el carrito después de la compra
        Cart::where('user_id', auth()->id())->delete();

        return redirect()->back()->with('alert', 'Shop made successfully')->with('alertType', 'success');
    }
}
