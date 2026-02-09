<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tshirt;
use App\Models\Jogger;

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
                    $model = match ($item->product_type) {
                        'TSHIRT' => Tshirt::class,
                        'JOGGER' => Jogger::class,
                        default => throw new \Exception('Invalid product type'),
                    };

                    $product = $model::lockForUpdate()->find($item->product_id);

                    if (!$product || $product->stock < $item->quantity) {
                        throw new \Exception('Not enough stock');
                    }

                    $price = $product->tshirt_price ?? $product->jogger_price;
                    $total += $price * $item->quantity;
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
                        'price' => $item->product->tshirt_price
                            ?? $item->product->jogger_price,
                    ]);

                    $item->product->decrement('stock', $item->quantity);
                }

                Cart::where('user_id', $userId)->delete();
            });

            return redirect()->route('orders.history');
        } catch (\Exception $e) {
            abort(422, $e->getMessage());
        }
    }
}
