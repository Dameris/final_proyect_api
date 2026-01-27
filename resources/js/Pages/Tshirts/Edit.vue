<!-- @format -->

<script>
export default {
	name: "TshirtsEdit",
};
</script>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import TshirtsForm from "@/Components/Tshirts/Form.vue";

const props = defineProps({
	tshirt: {
		type: Object,
		required: true,
	},
	auth: {
		type: Object,
		required: true,
	},
});

const form = useForm({
	id: props.tshirt.id,
	tshirt_name: props.tshirt.tshirt_name,
	tshirt_composition: props.tshirt.tshirt_composition,
	tshirt_fit: props.tshirt.tshirt_fit,
	tshirt_price: props.tshirt.tshirt_price,
	stock: props.tshirt.stock,
});

const isAdmin = computed(() => {
	return props.auth.user?.roles?.some(role => role.name === 'admin') ?? false;
});

const submit = () => {
	form.put(route("tshirts.update", form.id), {
		preserveScroll: true,
		forceFormData: true,
	});
};
</script>

<template>
	<AppLayout title="Edit Tshirt">
		<div>
			<h1>Edit Tshirt</h1>

			<TshirtsForm
				:updating="true"
				:form="form"
				:is-admin="isAdmin"
				@submit="submit"
			/>
		</div>
	</AppLayout>
</template>
