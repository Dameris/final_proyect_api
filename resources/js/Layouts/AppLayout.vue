<script setup>
import ShopHeader from "@/Components/Shared/ShopHeader.vue";
import ShopFooter from "@/Components/Shared/ShopFooter.vue";
import { onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { usePage } from "@inertiajs/inertia-vue3";

const auth = useAuthStore()

onMounted(() => {
  auth.init()
})
</script>

<template>
	<ShopHeader />

	<main>
		<slot />
	</main>

	<ShopFooter />
</template>
