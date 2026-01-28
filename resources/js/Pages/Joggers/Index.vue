<script>
export default {
	name: "JoggerIndex",
};
</script>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";

defineProps({
	joggers: {
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

const deleteJogger = (id) => {
	if (confirm("Are you sure?")) {
		Inertia.delete(route("joggers.destroy", id));
	}
};
</script>

<template>
	<AppLayout>
		<div v-if="$page.props.user.permissions.includes('createjoggers')">
			<Link :href="route('joggers.create')"> CREATE JOGGER </Link>
		</div>
		<div class="joggerIndex">
			<h2 class="joggerIndex__title">JOGGERS</h2>

			<ul class="joggerIndex__list">
				<li
					class="joggerIndex__list--element"
					v-for="jogger in joggers.data"
					:key="`first-${jogger.id}`"
				>
					<Link
						class="joggerIndex__list--elementLink"
						:href="route('joggers.show', jogger.id)"
					>
						<img
							:src="'storage/img/joggers/' + (hoveredImages[jogger.id] || jogger.jogger_img1)"
							alt="Jogger Image"
							@mouseover="setHoverImage(jogger.id, jogger.jogger_img2)"
							@mouseleave="resetImage(jogger.id, jogger.jogger_img1)"
						/>
						<p>
							{{ jogger.jogger_name.toUpperCase() }} <br />
							{{ jogger.jogger_price }}€
						</p>

						<p v-if="$page.props.user.permissions.includes('updatejoggers')">
							<Link :href="route('joggers.edit', jogger.id)"> Edit </Link>
							<Link
								:href="route('joggers.index')"
								@click="deleteJogger(jogger.id)"
							>
								Delete
							</Link>
						</p>
					</Link>
				</li>

				<!-- Segunda vez mostrando las camisetas -->
				<li
					class="joggerIndex__list--element"
					v-for="jogger in joggers.data"
					:key="`second-${jogger.id}`"
				>
					<Link
						class="joggerIndex__list--elementLink"
						:href="route('joggers.show', jogger.id)"
					>
						<img
							:src="'storage/img/joggers/' + (hoveredImages[jogger.id] || jogger.jogger_img1)"
							alt="Jogger Image"
							@mouseover="setHoverImage(jogger.id, jogger.jogger_img2)"
							@mouseleave="resetImage(jogger.id, jogger.jogger_img1)"
						/>
						<p>
							{{ jogger.jogger_name.toUpperCase() }} <br />
							{{ jogger.jogger_price }}€
						</p>

						<p v-if="$page.props.user.permissions.includes('updatejoggers')">
							<Link :href="route('joggers.edit', jogger.id)"> Edit </Link>
							<Link
								:href="route('joggers.index')"
								@click="deleteJogger(jogger.id)"
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
@import "../../../css/pages/joggers/index.css";
</style>
