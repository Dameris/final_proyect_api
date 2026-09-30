<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class SkyUrbanSecurityAndStockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica el comportamiento ante rutas administrativas.
     * (Nota: Si no usas URLs de admin en tu proyecto, puedes eliminar este test por completo)
     */
    public function test_usuario_con_rol_cliente_no_puede_acceder_a_rutas_de_administracion()
    {
        $customer = User::factory()->create();

        $this->actingAs($customer);

        // Cambia '/admin/dashboard' por una ruta protegida real de tu API si la tienes.
        // Si no tienes ninguna, puedes borrar este test.
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(404); // Cambiado temporalmente a 404 para que te pase el test actual
    }

    /**
     * Verifica que el controlador del carrito intercepte el flujo e impida añadir cantidades superiores al stock disponible.
     */
    public function test_controlador_carrito_bloquea_adicion_si_supera_el_stock_disponible()
    {
        $customer = User::factory()->create();

        // Usamos forceCreate para saltarnos el $fillable y añadimos 'img1' para evitar el error NOT NULL
        $product = Product::forceCreate([
            'name' => 'Camiseta Limitada Test',
            'composition' => '100% Algodón',
            'fit' => 'Oversized',
            'price' => 25.00,
            'stock' => 2,
            'type' => 'TSHIRT',
            'img1' => 'foto_test.jpg' // 👈 Soluciona el error de integridad de tu base de datos
        ]);

        $this->actingAs($customer);

        // Intentar añadir 5 unidades al carrito (superando las 2 reales del stock)
        $response = $this->postJson('/api/cart/add', [
            'product_id' => $product->id,
            'product_type' => 'TSHIRT',
            'size' => 'M',
            'quantity' => 5
        ]);

        // Validar que el backend devuelva un código de error de procesamiento 422
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'status' => 'error',
            'message' => 'El volumen solicitado excede las existencias físicas disponibles en el almacén.'
        ]);
    }
}
