<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";

const cart = ref([]);

// Obtener el carrito desde el servidor usando axios
const fetchCart = async () => {
	try {
		const response = await axios.get("/api/cart");
		cart.value = response.data || [];

		// Para cada producto en el carrito, obtener los detalles de la camiseta
		for (let item of cart.value) {
			await fetchTshirtDetails(item.tshirt_id, item);
		}
	} catch (error) {
		console.error("Error fetching cart:", error);
	}
};

// Obtener los detalles de una camiseta por ID
const fetchTshirtDetails = async (tshirtId, item) => {
	try {
		const response = await axios.get(`/api/tshirt/${tshirtId}`);
		if (response.data) {
			item.tshirtDetails = response.data;
		}
	} catch (error) {
		console.error("Error fetching tshirt details:", error);
	}
};

const updateQuantity = async (itemId, quantity) => {
	try {
		if (quantity < 1) return;

		Inertia.put(`/cart/${itemId}`, { quantity });
	} catch (error) {
		console.error("Error updating the quantity", error);
		alert("Error updating the quantity. Try again.");
	}
};

const removeFromCart = async (itemId) => {
	try {
		Inertia.delete(`/cart/${itemId}`);
	} catch (error) {
		console.error("Error deleting an item", error);
		alert("Error deleting an item. Try again");
	}
};

const removeAllFromCart = async () => {
	try {
		Inertia.delete("/cart");
	} catch (error) {
		console.error("Error deleting all items", error);
		alert("Error deleting all items. Try again");
	}
};

const checkout = async () => {
	try {
		Inertia.post("/checkout", {
			cart: cart.value,
		});
		alert("Shop made successfully. Thanks for your purchase");
	} catch (error) {
		console.error("Error processing the checkout", error);
		alert("Error with the checkout. Try again.");
	}
};

// Obtener el carrito cuando el componente se monta
onMounted(() => {
	fetchCart();
});
</script>

<template>
	<AppLayout>
		<div
			v-if="cart.length > 0"
			class="shoppingCart__container"
		>
			<h2>Your Cart</h2>
            <button
				@click="removeAllFromCart"
				class="shoppingCart__removeAll"
			>
				EMPTY CART
			</button>
			<ul class="shoppingCart__list">
				<li
					v-for="item in cart"
					:key="item.id"
					class="shoppingCart__list--item"
				>
					<div class="shoppingCart__list--itemDetails">
						<span class="shoppingCart__list--itemName">
							{{ item.tshirtDetails?.tshirt_name || item.tshirt.tshirt_name }}
						</span>
						-
						<span class="shoppingCart__list--itemPrice">
							€{{ item.tshirtDetails?.tshirt_price || item.tshirt.tshirt_price }}
						</span>
						x {{ item.quantity }} (Size: {{ item.size }})
					</div>
					<div class="shoppingCart__list--itemAction">
						<button
							@click="removeFromCart(item.id)"
							class="shoppingCart__list--itemAction__remove"
						>
							Remove
						</button>
						<input
							type="number"
							v-model="item.quantity"
							@change="updateQuantity(item.id, item.quantity)"
							min="1"
							class="shoppingCart__list--itemAction__quantity"
						/>
					</div>
				</li>
			</ul>
			<div class="shoppingCart__totalPrice">
				<strong
					>Total:
					{{
						cart.reduce(
							(sum, item) => sum + (item.tshirtDetails?.tshirt_price || item.tshirt.tshirt_price) * item.quantity,
							0,
						)
					}}€</strong
				>
			</div>
			<button
				@click="checkout"
				class="shoppingCart__checkout"
			>
				CHECK OUT
			</button>
		</div>
		<div
			v-else
			class="shoppingCart__emptyCart"
		>
			<p>YOUR CART IS EMPTY.</p>
		</div>
	</AppLayout>
</template>

<style scoped>
@import "../../css/pages/shoppingCart.css";
</style>
