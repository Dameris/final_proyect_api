<script>
export default {
	name: "TshirtIndex",
};
</script>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";

defineProps({
	tshirts: {
		type: Object,
		required: true,
	},
});

// Objeto reactivo para controlar la imagen mostrada en cada camiseta
const hoveredImages = ref({});

const setHoverImage = (id, img2) => {
	hoveredImages.value[id] = img2;
};

const resetImage = (id, img1) => {
	hoveredImages.value[id] = img1;
};

const deleteTshirt = (id) => {
	if (confirm("Are you sure?")) {
		Inertia.delete(route("tshirts.destroy", id));
	}
};
</script>

<template>
	<AppLayout>
		<div v-if="$page.props.user.permissions.includes('createtshirts')">
			<Link :href="route('tshirts.create')"> CREATE TSHIRT </Link>
		</div>
		<div class="tshirtIndex">
			<h2 class="tshirtIndex__title">T-SHIRTS</h2>

			<ul class="tshirtIndex__list">
				<li
					class="tshirtIndex__list--element"
					v-for="tshirt in tshirts.data"
					:key="`first-${tshirt.id}`"
				>
					<Link
						class="tshirtIndex__list--elementLink"
						:href="route('tshirts.show', tshirt.id)"
					>
						<img
							:src="'storage/img/tshirts/' + (hoveredImages[tshirt.id] || tshirt.tshirt_img1)"
							alt="Tshirt Image"
							@mouseover="setHoverImage(tshirt.id, tshirt.tshirt_img2)"
							@mouseleave="resetImage(tshirt.id, tshirt.tshirt_img1)"
						/>
						<p>
							{{ tshirt.tshirt_name.toUpperCase() }} <br />
							{{ tshirt.tshirt_price }}€
						</p>

						<p v-if="$page.props.user.permissions.includes('updatetshirts')">
							<Link :href="route('tshirts.edit', tshirt.id)"> Edit </Link>
							<Link
								:href="route('tshirts.index')"
								@click="deleteTshirt(tshirt.id)"
							>
								Delete
							</Link>
						</p>
					</Link>
				</li>

				<!-- Segunda vez mostrando las camisetas -->
				<li
					class="tshirtIndex__list--element"
					v-for="tshirt in tshirts.data"
					:key="`second-${tshirt.id}`"
				>
					<Link
						class="tshirtIndex__list--elementLink"
						:href="route('tshirts.show', tshirt.id)"
					>
						<img
							:src="'storage/img/tshirts/' + (hoveredImages[tshirt.id] || tshirt.tshirt_img1)"
							alt="Tshirt Image"
							@mouseover="setHoverImage(tshirt.id, tshirt.tshirt_img2)"
							@mouseleave="resetImage(tshirt.id, tshirt.tshirt_img1)"
						/>
						<p>
							{{ tshirt.tshirt_name.toUpperCase() }} <br />
							{{ tshirt.tshirt_price }}€
						</p>

						<p v-if="$page.props.user.permissions.includes('updatetshirts')">
							<Link :href="route('tshirts.edit', tshirt.id)"> Edit </Link>
							<Link
								:href="route('tshirts.index')"
								@click="deleteTshirt(tshirt.id)"
							>
								Delete
							</Link>
						</p>
					</Link>
				</li>
			</ul>
		</div>
	</AppLayout>
</template>

<style scoped>
@import "../../../css/pages/tshirts/index.css";
</style>
