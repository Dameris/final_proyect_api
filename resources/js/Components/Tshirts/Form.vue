<script>
export default {
	name: "TshirtsForm",
};
</script>

<script setup>
import { router } from "@inertiajs/vue3";
import FormSection from "../FormSection.vue";
import InputError from "../InputError.vue";
import InputLabel from "../InputLabel.vue";
import PrimaryButton from "../PrimaryButton.vue";
import TextInput from "../TextInput.vue";

const props = defineProps({
	form: {
		type: Object,
		required: true,
	},
	updating: {
		type: Boolean,
		required: false,
		default: false,
	},
});

const emit = defineEmits(["submit"]);

const handleFileChange = (field, event) => {
	const file = event.target.files[0];
	if (file) {
		props.form[field] = file;
	}
};

const handleSubmit = () => {
    if (props.updating) {
        props.form._method = 'PUT';
        router.post(route("tshirts.update", { tshirt: props.form.id }), props.form, {
            preserveScroll: true,
            forceFormData: true,
        });
    } else {
        router.post(route("tshirts.store"), props.form, {
            preserveScroll: true,
            forceFormData: true,
        });
    }
};
</script>

<template>
	<FormSection>
		<template #title>
			{{ updating ? "Update Tshirt" : "Create Tshirt" }}
		</template>

		<template #description>
			{{ updating ? "Update selected Tshirt" : "Create new Tshirt" }}
		</template>

		<template #form>
			<div>
				<InputLabel
					for="tshirt_name"
					value="Tshirt name"
				/>
				<TextInput
					id="tshirt_name"
					v-model="form.tshirt_name"
					type="text"
					autocomplete="tshirt_name"
				/>
				<InputError :message="form.errors.tshirt_name" />
			</div>
			<div>
				<InputLabel
					for="tshirt_composition"
					value="Tshirt composition"
				/>
				<TextInput
					id="tshirt_composition"
					v-model="form.tshirt_composition"
					type="text"
					autocomplete="tshirt_composition"
				/>
				<InputError :message="form.errors.tshirt_composition" />
			</div>
			<div>
				<InputLabel
					for="tshirt_fit"
					value="Tshirt fit"
				/>
				<TextInput
					id="tshirt_fit"
					v-model="form.tshirt_fit"
					type="text"
					autocomplete="tshirt_fit"
				/>
				<InputError :message="form.errors.tshirt_fit" />
			</div>
			<div>
				<InputLabel
					for="tshirt_price"
					value="Tshirt price"
				/>
				<input
					id="tshirt_price"
					v-model="form.tshirt_price"
					type="number"
					autocomplete="tshirt_price"
				/>
				<InputError :message="form.errors.tshirt_price" />
			</div>
			<div>
				<InputLabel
					for="tshirt_img1"
					value="Tshirt img1"
				/>
				<input
					id="tshirt_img1"
					type="file"
					@input="form.tshirt_img1 = $event.target.files[0]"
					accept="image/*"
				/>
				<InputError :message="form.errors.tshirt_img1" />
			</div>
			<div>
				<InputLabel
					for="tshirt_img2"
					value="Tshirt img2"
				/>
				<input
					id="tshirt_img2"
					type="file"
					@input="form.tshirt_img2 = $event.target.files[0]"
					accept="image/*"
				/>
				<InputError :message="form.errors.tshirt_img2" />
			</div>
		</template>

		<template #actions>
			<PrimaryButton @click.prevent="handleSubmit">
				{{ updating ? "Update" : "Create" }}
			</PrimaryButton>
		</template>
	</FormSection>
</template>
