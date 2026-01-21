<script>
export default {
	name: "TshirtShow",
};
</script>

<script setup>
import { ref } from 'vue';
import { Inertia } from "@inertiajs/inertia";
import { Link } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const selectedSize = ref('');
const props = defineProps({
	tshirt: {
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
	Inertia.post(`/cart/${props.tshirt.id}`, {
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
		<div class="tshirtShow">
			<!-- Imágenes a la izquierda -->
			<div class="tshirtShow__imagesBox">
				<img
					v-if="tshirt.tshirt_img1"
					:src="'/storage/img/tshirts/' + tshirt.tshirt_img1"
					alt="Front view"
				/>
				<img
					v-if="tshirt.tshirt_img2"
					:src="'/storage/img/tshirts/' + tshirt.tshirt_img2"
					alt="Back view"
				/>
			</div>

			<!-- Información a la derecha -->
			<div class="tshirtShow__info">
				<div>
					<h2 class="tshirt__title">{{ tshirt.tshirt_name.toUpperCase() }}</h2>
					<p class="tshirt__price">{{ tshirt.tshirt_price }}€</p>
				</div>

				<div class="tshirtShow__details">
					<h3>DETAILS</h3>
					<p>
						<strong>COMPOSITION:</strong> <br />
						{{ tshirt.tshirt_composition.toUpperCase() }}
					</p>
					<p id="fit">
						<strong>FIT:</strong> <br />
						{{ tshirt.tshirt_fit.toUpperCase() }}
					</p>
					<p id="fit">
						<strong>STOCK:</strong> <br />
						{{ tshirt.stock }}
					</p>
				</div>

				<div>
					<!-- Tallas como botones -->
					<div class="tshirt__sizes">
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
					<button class="tshirtShow_addToCart" @click="addToCart" :disabled="tshirt.stock < 1">{{ tshirt.stock < 1 ? 'OUT OF STOCK' : 'ADD TO CART' }}</button>
				</div>
			</div>
		</div>
		<div class="tshirtShow__backToList">
			<Link
				class="tshirtShow__backToList--link"
				:href="route('tshirts.index')"
			>
				BACK
			</Link>
		</div>
	</AppLayout>
</template>

<style scoped>
@import "../../../css/pages/tshirts/show.css";
</style>
