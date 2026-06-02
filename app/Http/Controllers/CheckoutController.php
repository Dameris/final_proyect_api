<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;

class CheckoutController extends Controller
{
    public function processOrder()
    {
        try {
            DB::transaction(function () {

                $userId = Auth::id();
                if (!$userId) {
                    throw new \Exception('You must be logged in');
                }

                $cartItems = Cart::where('user_id', $userId)->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception('The cart is empty');
                }

                $total = 0;

                foreach ($cartItems as $item) {
                    $type = match (strtoupper($item->product_type)) {
                        'TSHIRT', 'PRODUCT' => 'tshirt',
                        'JOGGER' => 'jogger',
                        default => throw new \Exception('Invalid product type'),
                    };

                    $product = Product::where('type', $type)->find($item->product_id);

                    if (!$product) {
                        throw new \Exception('Product not found');
                    }

                    // BLOQUEO POR TALLA: Buscamos y bloqueamos la talla específica requerida
                    $sizeStock = ProductStock::lockForUpdate()
                        ->where('product_id', $product->id)
                        ->where('size', $item->size)
                        ->first();

                    // Verificamos si hay stock suficiente en esa talla concreta
                    if (!$sizeStock || $sizeStock->stock < $item->quantity) {
                        throw new \Exception("Not enough stock for product '{$product->name}' in size '{$item->size}'");
                    }

                    $price = $product->price;
                    $total += $price * $item->quantity;

                    // Adjuntamos las referencias procesadas al objeto en memoria
                    $item->calculated_price = $price;
                    $item->resolved_stock_model = $sizeStock;
                }

                $order = Order::create([
                    'user_id' => $userId,
                    'total_price' => $total,
                    'status' => 'Processed',
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_type' => $item->product_type,
                        'size' => $item->size,
                        'quantity' => $item->quantity,
                        'price' => $item->calculated_price,
                    ]);

                    // Decrementamos el inventario únicamente en la talla comprada
                    $item->resolved_stock_model->decrement('stock', $item->quantity);
                }

                Cart::where('user_id', $userId)->delete();
            });

            return redirect()->route('orders.history');
        } catch (\Exception $e) {
            abort(422, $e->getMessage());
        }
    }
}
