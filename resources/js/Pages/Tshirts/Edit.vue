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

/**
 * Convertimos el array de relaciones 'stocks' que viene de Laravel
 * en un objeto clave-valor para Vue.
 * Ejemplo: { 'Oversize': 12, 'Regular': 0, 'Slim': 5 }
 */
const initializeStocks = () => {
    const stockMap = {};

    props.sizes.forEach(size => {
        stockMap[size] = 0;
    });
    if (props.tshirt.stocks && Array.isArray(props.tshirt.stocks)) {
        props.tshirt.stocks.forEach(item => {
            stockMap[item.size] = item.stock;
        });
    }

    return stockMap;
};

const form = useForm({
	id: props.tshirt.id,
	tshirt_name: props.tshirt.tshirt_name,
	tshirt_composition: props.tshirt.tshirt_composition,
	tshirt_fit: props.tshirt.tshirt_fit,
	tshirt_price: props.tshirt.tshirt_price,
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
	})).post(route("tshirts.update", form.id), {
		preserveScroll: true,
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
				:compositions="props.compositions"
				:fits="props.fits"
				:sizes="props.sizes"
				@submit="submit"
			/>
		</div>
	</AppLayout>
</template>