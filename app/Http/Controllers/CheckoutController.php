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
    public function processOrder(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

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
                    $product = Product::find($item->product_id);

                    if (!$product) {
                        throw new \Exception("Product with ID {$item->product_id} not found in database.");
                    }

                    $sizeStock = ProductStock::lockForUpdate()
                        ->where('product_id', $product->id)
                        ->where('size', $item->size)
                        ->first();

                    if (!$sizeStock) {
                        throw new \Exception("Size '{$item->size}' does not exist for product '{$product->id}'");
                    }

                    if ($sizeStock->stock < 0) {
                        throw new \Exception("Not enough stock for size '{$item->size}'");
                    }

                    $type = strtolower($product->type);
                    $price = $product->{"{$type}_price"} ?? $product->price ?? 0;

                    $total += $price * $item->quantity;

                    $item->calculated_price = $price;
                    $item->resolved_stock_model = $sizeStock;
                }

                $shipping = $request->input('shipping_details', []);

                $order = Order::create([
                    'user_id' => $userId,
                    'total_price' => $total,
                    'status' => 'Processed',
                    'shipping_name'    => $shipping['fullName'] ?? null,
                    'shipping_address' => $shipping['address'] ?? null,
                    'shipping_city'    => $shipping['city'] ?? null,
                    'shipping_zip'     => $shipping['zipCode'] ?? null,
                    'shipping_phone'   => $shipping['phone'] ?? null,
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

            return redirect()->route('orders.history')->with('alert', 'Order placed successfully!')->with('alertType', 'success');
        } catch (\Exception $e) {
            abort(422, $e->getMessage());
        }
    }
}
