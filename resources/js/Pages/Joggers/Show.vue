<script>
export default {
	name: "JoggerShow",
};
</script>

<script setup>
import { ref } from 'vue';
import { Inertia } from "@inertiajs/inertia";
import { Link } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const selectedSize = ref('');
const props = defineProps({
	jogger: {
		type: Object,
		required: true,
	},

	auth: {
        type: Object,
        required: true,
    },
});

// Función para agregar al carrito
const addToCart = () => {
	console.log("Intentando añadir al carrito...");
	if (!props.auth.user) {
		return;
	}

	if (!selectedSize.value) {
		alert("Please select a size.");
		return;
	}

	// Enviar el producto y la talla seleccionada al servidor
	Inertia.post(`/cart/${props.jogger.id}`, {
		size: selectedSize.value
	});
};

// Función para manejar el cambio de talla
const selectSize = (size) => {
	selectedSize.value = size;
};
</script>

<template>
	<AppLayout>
		<div class="joggerShow">
			<!-- Imágenes a la izquierda -->
			<div class="joggerShow__imagesBox">
				<img
					v-if="jogger.jogger_img1"
					:src="'/storage/img/joggers/' + jogger.jogger_img1"
					alt="Front view"
				/>
				<img
					v-if="jogger.jogger_img2"
					:src="'/storage/img/joggers/' + jogger.jogger_img2"
					alt="Back view"
				/>
			</div>

			<!-- Información a la derecha -->
			<div class="joggerShow__info">
				<div>
					<h2 class="jogger__title">{{ jogger.jogger_name.toUpperCase() }}</h2>
					<p class="jogger__price">{{ jogger.jogger_price }}€</p>
				</div>

				<div class="joggerShow__details">
					<h3>DETAILS</h3>
					<p>
						<strong>COMPOSITION:</strong> <br />
						{{ jogger.jogger_composition.toUpperCase() }}
					</p>
					<p id="fit">
						<strong>FIT:</strong> <br />
						{{ jogger.jogger_fit.toUpperCase() }}
					</p>
					<p id="fit">
						<strong>STOCK:</strong> <br />
						{{ jogger.stock }}
					</p>
				</div>

				<div>
					<!-- Tallas como botones -->
					<div class="jogger__sizes">
						<span
							v-for="size in ['XS', 'S', 'M', 'L', 'XL', 'XXL']"
							:key="size"
							:class="{'active': selectedSize === size}"
							@click="selectSize(size)"
						>
							{{ size }}
						</span>
					</div>

					<!-- Botón de compra -->
					<button class="joggerShow_addToCart" @click="addToCart" :disabled="jogger.stock < 1">{{ jogger.stock < 1 ? 'OUT OF STOCK' : 'ADD TO CART' }}</button>
				</div>
			</div>
		</div>
		<div class="joggerShow__backToList">
			<Link
				class="joggerShow__backToList--link"
				:href="route('joggers.index')"
			>
				BACK
			</Link>
		</div>
	</AppLayout>
</template>

<style scoped>
@import "../../../css/pages/joggers/show.css";
</style>
