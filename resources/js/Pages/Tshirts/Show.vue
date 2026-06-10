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
		Inertia.visit(route('login'));
		return;
	}

	if (!selectedSize.value) {
        window.emitter.emit('trigger-alert', { 
            message: "Please, select a size.", 
            type: "error" 
        });
        return;
    }

	// Enviar el producto y la talla seleccionada al servidor
	Inertia.post(route('cart.add', { 
		type: 'TSHIRT', 
		id: props.tshirt.id 
	}), {
		size: selectedSize.value
	});
};

// Función para manejar el cambio de talla
const selectSize = (size) => {
	selectedSize.value = size;
};

// Comprueba si la talla en el bucle existe en la colección 'stocks' enviada por Laravel y si tiene unidades
const hasStockForSize = (sizeName) => {
    if (!props.tshirt.stocks) return false;
    const sizeRecord = props.tshirt.stocks.find(s => s.size === sizeName);
    return sizeRecord ? sizeRecord.stock > 0 : false;
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
					<p id="composition">
						<strong>COMPOSITION:</strong> <br />
						{{ tshirt.tshirt_composition.toUpperCase() }}
					</p>
					<p id="fit">
						<strong>FIT:</strong> <br />
						{{ tshirt.tshirt_fit.toUpperCase() }}
					</p>
					<p id="stock">
    					<strong>STOCK BY SIZES:</strong> <br />
    					<span class="stock__list">
        					<template v-if="tshirt.stocks && tshirt.stocks.length > 0">
            					<span 
                					v-for="item in tshirt.stocks" 
                					:key="item.id" 
                					class="stock__item"
                					:class="{ 'outOfStock__text': item.stock === 0 }"
            					>
                					{{ item.size }}: {{ item.stock }}
            					</span>
        					</template>
        					<span v-else class="outOfStock__text">No stock available</span>
    					</span>
					</p>
				</div>

				<div>
					<!-- Tallas como botones -->
					<div class="tshirt__sizes">
    					<span
        					v-for="size in ['XS', 'S', 'M', 'L', 'XL', 'XXL']"
        					:key="size"
        					:class="{
            					'active': selectedSize === size,
            					'out-of-stock-size': !hasStockForSize(size),
            					'disabled': !hasStockForSize(size)
        					}"
        					@click="hasStockForSize(size) ? selectSize(size) : null"
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
