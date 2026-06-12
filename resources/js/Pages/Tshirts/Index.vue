<script>
export default {
	name: "TshirtIndex",
};
</script>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
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
    window.emitter.emit('trigger-confirm', {
        message: "Are you sure you want to delete this tshirt?",
        onConfirm: () => {
            router.delete(route("tshirts.destroy", id), {
                preserveScroll: true,
                onSuccess: () => {
                    window.emitter.emit('trigger-alert', { 
                        message: "The tshirt has been successfully deleted.", 
                        type: "success" 
                    });
                }
            });
        }
    });
};
</script>

<template>
	<AppLayout>
		<div v-if="$page.props.user.permissions.includes('createtshirts')">
			<Link :href="route('tshirts.create')" class="tshirts__create"> CREATE TSHIRT </Link>
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
                            :src="hoveredImages[tshirt.id] || tshirt.tshirt_img1"
                            alt="Tshirt Image"
                            @mouseover="setHoverImage(tshirt.id, tshirt.tshirt_img2)"
                            @mouseleave="resetImage(tshirt.id, tshirt.tshirt_img1)"
                        />
						<p>
							{{ tshirt.tshirt_name.toUpperCase() }} <br />
							{{ tshirt.tshirt_price }}€
						</p>

						<p v-if="$page.props.user.permissions.includes('updatetshirts')">
							<Link :href="route('tshirts.edit', tshirt.id)" class="tshirts__edit"> Edit </Link>
							<button
								:href="route('tshirts.index')"
								@click.prevent.stop="deleteTshirt(tshirt.id)"
								class="tshirts__delete"
							>
								Delete
							</button>
						</p>
					</Link>
				</li>

				<!-- Segunda vez mostrando las camisetas -->
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
                            :src="hoveredImages[tshirt.id] || tshirt.tshirt_img1"
                            alt="Tshirt Image"
                            @mouseover="setHoverImage(tshirt.id, tshirt.tshirt_img2)"
                            @mouseleave="resetImage(tshirt.id, tshirt.tshirt_img1)"
                        />
						<p>
							{{ tshirt.tshirt_name.toUpperCase() }} <br />
							{{ tshirt.tshirt_price }}€
						</p>

						<p v-if="$page.props.user.permissions.includes('updatetshirts')">
							<Link :href="route('tshirts.edit', tshirt.id)" class="tshirts__edit"> Edit </Link>
							<button
								:href="route('tshirts.index')"
								@click.prevent.stop="deleteTshirt(tshirt.id)"
								class="tshirts__delete"
							>
								Delete
							</button>
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
