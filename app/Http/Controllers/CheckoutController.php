<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tshirt;

class CheckoutController extends Controller
{
    public function processOrder(Request $request)
    {
        $cartItems = $request->input('cart');

        if (!$cartItems || \count($cartItems) === 0) {
            return redirect()->back()
                ->with('alert', 'The cart is empty')
                ->with('alertType', 'error');
        }

        try {
            $order = DB::transaction(function () use ($cartItems) {

                $total = 0;

                /** @var int $userId */
                $userId = Auth::id();

                if (!$userId) {
                    throw new \Exception('You must be logged in to place an order');
                }

                foreach ($cartItems as $item) {
                    $tshirt = Tshirt::where('id', $item['tshirt_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$tshirt || $tshirt->stock < $item['quantity']) {
                        throw new \Exception('Not enough stock for ' . ($tshirt->tshirt_name ?? 'a product'));
                    }

                    $total += $tshirt->tshirt_price * $item['quantity'];
                }

                $order = Order::create([
                    'user_id' => $userId,
                    'total_price' => $total,
                    'status' => 'Processed',
                ]);

                foreach ($cartItems as $item) {
                    $tshirt = Tshirt::where('id', $item['tshirt_id'])
                        ->lockForUpdate()
                        ->first();

                    OrderItem::create([
                        'order_id' => $order->id,
                        'tshirt_id' => $tshirt->id,
                        'quantity' => $item['quantity'],
                        'price' => $tshirt->tshirt_price,
                    ]);

                    $tshirt->decrement('stock', $item['quantity']);
                }

                Cart::where('user_id', $userId)->delete();

                return $order;
            });

            return redirect()->back()
                ->with('alert', 'Shop made successfully')
                ->with('alertType', 'success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('alert', $e->getMessage())
                ->with('alertType', 'error');
        }
    }
}
