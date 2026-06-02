<script setup>
import ShopHeader from "@/Components/Shared/ShopHeader.vue";
import ShopFooter from "@/Components/Shared/ShopFooter.vue";
import { onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { usePage } from "@inertiajs/inertia-vue3";
import { Head } from '@inertiajs/vue3'

const auth = useAuthStore()

defineProps({
    title: {
        type: String,
        required: false,
        default: 'SkyUrban'
    }
});
// onMounted(() => {
//   auth.init()
// })
</script>

<template>
	<ShopHeader />

	<main>
		<slot />
	</main>

	<ShopFooter />
</template>
