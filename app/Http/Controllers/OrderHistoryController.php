<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;

class OrderHistoryController extends Controller
{
    /**
     * Mostrar el historial de órdenes del usuario autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener las órdenes del usuario autenticado
        $orders = Order::where('user_id', auth()->id())->with('orderItems.tshirt')->get();

        // Retornar la vista con las órdenes
        return Inertia::render('OrdersHistory', [
            'orders' => $orders
        ]);
    }
}
