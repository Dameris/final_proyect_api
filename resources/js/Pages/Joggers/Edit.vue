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
	compositions: {
		type: Array,
		required: true,
	},
	fits: {
		type: Array,
		required: true,
	},
	sizes: {
		type: Array,
		required: true,
	},
});

const initializeStocks = () => {
    const stockMap = {};

    props.sizes.forEach(size => {
        stockMap[size] = 0;
    });
    if (props.jogger.stocks && Array.isArray(props.jogger.stocks)) {
        props.jogger.stocks.forEach(item => {
            stockMap[item.size] = item.stock;
        });
    }

    return stockMap;
};

const form = useForm({
	id: props.jogger.id,
	jogger_name: props.jogger.jogger_name,
	jogger_composition: props.jogger.jogger_composition,
	jogger_fit: props.jogger.jogger_fit,
	jogger_price: props.jogger.jogger_price,
	stock: initializeStocks(), 
});

const isAdmin = computed(() => {
	return props.auth.user?.roles?.some(role => role.name === 'admin') ?? false;
});

const submit = () => {
	// Laravel tiene un bug conocido procesando archivos (FormData) mediante PUT.
	// Al usar "forceFormData: true", se recomienda usar .post() simulando el método PUT con '_method'
	form.transform((data) => ({
		...data,
		_method: 'PUT',
	})).post(route("joggers.update", form.id), {
		preserveScroll: true,
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
				:compositions="props.compositions"
				:fits="props.fits"
				:sizes="props.sizes"
				@submit="submit"
			/>
		</div>
	</AppLayout>
</template>
