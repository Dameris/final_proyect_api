<script>
export default {
	name: "TshirtShow",
};
</script>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
	tshirt: {
		type: Object,
		required: true,
	},
});
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
				</div>

				<div>
					<!-- Tallas -->
					<div class="tshirt__sizes">
						<span>XS</span>
						<span>S</span>
						<span>M</span>
						<span>L</span>
						<span>XL</span>
						<span>XXL</span>
					</div>

					<!-- Botón de compra -->
					<button class="tshirtShow_addToCart">ADD TO CART</button>
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
