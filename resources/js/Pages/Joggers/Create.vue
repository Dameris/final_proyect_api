<!-- @format -->

<script>
export default {
	name: "JoggersCreate",
};
</script>

<script setup>
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import JoggersForm from "@/Components/Joggers/Form.vue";

const props = defineProps({
	compositions: {
		type: Array,
		required: true,
	},
	fits: {
		type: Array,
		required: true,
	},
})

const form = useForm({
	jogger_name: "",
	jogger_composition: "",
	jogger_fit: "",
	jogger_price: 0,
	jogger_img1: null,
	jogger_img2: null,
});
</script>

<template>
	<AppLayout title="Create Jogger">
		<div>
			<h1>Create Jogger</h1>

			<JoggersForm
				:form="form"
				:compositions="props.compositions"
				:fits="props.fits"
			/>
		</div>
	</AppLayout>
</template>
