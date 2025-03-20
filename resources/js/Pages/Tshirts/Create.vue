<!-- @format -->

<script>
export default {
	name: "TshirtsCreate",
};
</script>

<script setup>
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import TshirtsForm from "@/Components/Tshirts/Form.vue";

const form = useForm({
	tshirt_name: "",
	tshirt_composition: "",
	tshirt_fit: "",
	tshirt_price: 0,
	tshirt_img1: null,
	tshirt_img2: null,
});
</script>

<template>
	<AppLayout title="Create Tshirt">
		<div>
			<h1>Create Tshirt</h1>

			<TshirtsForm :form="form" />
		</div>
	</AppLayout>
</template>
