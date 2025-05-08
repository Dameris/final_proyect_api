<script setup>
import ShopHeader from "@/Components/Shared/ShopHeader.vue";
import ShopFooter from "@/Components/Shared/ShopFooter.vue";
</script>

<template>
	<ShopHeader />

	<main>
		<slot />
	</main>

	<ShopFooter />
</template>
