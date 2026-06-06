<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determina si el usuario puede ver el listado o el detalle.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede crear productos.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('createtshirts') || $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede actualizar el producto.
     */
    public function update(User $user, Product $product): bool
    {
        if ($product->type === 'tshirt') {
            return $user->hasPermissionTo('updatetshirts') || $user->hasRole('admin');
        }

        return $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede borrar el producto.
     */
    public function delete(User $user, Product $product): bool
    {
        if ($product->type === 'tshirt') {
            return $user->hasPermissionTo('updatetshirts') || $user->hasRole('admin');
        }

        return $user->hasRole('admin');
    }
}
