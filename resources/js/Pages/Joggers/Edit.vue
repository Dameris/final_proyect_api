<!-- @format -->

<script>
export default {
	name: "JoggersEdit",
};
</script>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JoggersForm from "@/Components/Joggers/Form.vue";

const props = defineProps({
	jogger: {
		type: Object,
		required: true,
	},
	auth: {
		type: Object,
		required: true,
	},
});

const form = useForm({
	id: props.jogger.id,
	jogger_name: props.jogger.jogger_name,
	jogger_composition: props.jogger.jogger_composition,
	jogger_fit: props.jogger.jogger_fit,
	jogger_price: props.jogger.jogger_price,
	stock: props.jogger.stock,
});

const isAdmin = computed(() => {
	return props.auth.user?.roles?.some(role => role.name === 'admin') ?? false;
});

const submit = () => {
	form.put(route("joggers.update", form.id), {
		preserveScroll: true,
		forceFormData: true,
	});
};
</script>

<template>
	<AppLayout title="Edit Jogger">
		<div>
			<h1>Edit Jogger</h1>

			<JoggersForm
				:updating="true"
				:form="form"
				:is-admin="isAdmin"
				@submit="submit"
			/>
		</div>
	</AppLayout>
</template>
