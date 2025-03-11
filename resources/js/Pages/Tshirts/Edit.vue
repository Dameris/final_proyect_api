<!-- @format -->

<script>
export default {
	name: "TshirtsEdit",
};
</script>

<script setup>
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import TshirtsForm from "@/Components/Tshirts/Form.vue";

const props = defineProps({
	tshirt: {
		type: Object,
		required: true,
	},
});

const form = useForm({
	tshirt_name: props.tshirt.tshirt_name,
	tshirt_composition: props.tshirt.tshirt_composition,
	tshirt_fit: props.tshirt.tshirt_fit,
	tshirt_price: props.tshirt.tshirt_price,
});
</script>

<template>
	<AppLayout title="Edit Tshirt">
		<template #header>
			<h1>Edit Tshirt</h1>
		</template>

		<div>
			<div>
				<div>
					<div>
						<div>
							<TshirtsForm
								:updating="true"
								:form="form"
								@submit="form.put(route('tshirts.update', tshirt.id))"
							/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</AppLayout>
</template>
