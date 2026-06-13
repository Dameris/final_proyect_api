<script>
export default {
	name: "joggerShow",
};
</script>

<script setup>
import { ref, computed } from 'vue';
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

const orderedStocks = computed(() => {
    if (!props.tshirt.stocks) return [];
    
    const sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    
    return [...props.tshirt.stocks].sort((a, b) => {
        return sizeOrder.indexOf(a.size) - sizeOrder.indexOf(b.size);
    });
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
		type: 'JOGGER', 
		id: props.jogger.id 	
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
    if (!props.jogger.stocks) return false;
    const sizeRecord = props.jogger.stocks.find(s => s.size === sizeName);
    return sizeRecord ? sizeRecord.stock > 0 : false;
};
</script>

<template>
	<AppLayout>
		<div class="joggerShow">
			<!-- Imágenes a la izquierda -->
			<div class="joggerShow__imagesBox">
    			<div class="joggerShow__image-card">
        			<img :src="`/img/joggers/${jogger.jogger_img1}`" alt="Front view" />
    			</div>
    			<div class="joggerShow__image-card">
        			<img :src="`/img/joggers/${jogger.jogger_img2}`" alt="Back view" />
    			</div>
			</div>

			<!-- Información a la derecha -->
			<div class="joggerShow__info">
				<div>
					<h2 class="jogger__title">{{ jogger.jogger_name.toUpperCase() }}</h2>
					<p class="jogger__price">{{ jogger.jogger_price }}€</p>
				</div>

				<div class="joggerShow__details">
					<h3>DETAILS</h3>
					<p id="composition">
						<strong>COMPOSITION:</strong> <br />
						{{ jogger.jogger_composition.toUpperCase() }}
					</p>
					<p id="fit">
						<strong>FIT:</strong> <br />
						{{ jogger.jogger_fit.toUpperCase() }}
					</p>
					<p id="stock">
    					<strong>STOCK BY SIZES:</strong> <br />
    					<span class="stock__list">
        					<template v-if="jogger.stocks && jogger.stocks.length > 0">
            					<span 
                					v-for="item in orderedStocks"
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
					<div class="jogger__sizes">
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
