<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import ShopHeader from "@/Components/Shared/ShopHeader.vue";
import ShopFooter from "@/Components/Shared/ShopFooter.vue";

defineProps({
	title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
	router.put(
		route("current-team.update"),
		{
			team_id: team.id,
		},
		{
			preserveState: false,
		},
	);
};

const logout = () => {
	router.post(route("logout"));
};
</script>

<template>
	<ShopHeader />

	<main>
		<slot />
	</main>

	<ShopFooter />
</template>
