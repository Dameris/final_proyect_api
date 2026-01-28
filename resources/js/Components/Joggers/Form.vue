<script>
export default {
	name: "JoggersForm",
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
	isAdmin: {
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
        router.post(route("joggers.update", { jogger: props.form.id }), props.form, {
            preserveScroll: true,
            forceFormData: true,
        });
    } else {
		props.form.stock = 1;

        router.post(route("joggers.store"), props.form, {
            preserveScroll: true,
            forceFormData: true,
        });
    }
};
</script>

<template>
	<FormSection>
		<template #title>
			{{ updating ? "Update Jogger" : "Create Jogger" }}
		</template>

		<template #description>
			{{ updating ? "Update selected Jogger" : "Create new Jogger" }}
		</template>

		<template #form>
			<div>
				<InputLabel
					for="jogger_name"
					value="Jogger name"
				/>
				<TextInput
					id="jogger_name"
					v-model="form.jogger_name"
					type="text"
					autocomplete="jogger_name"
				/>
				<InputError :message="form.errors.jogger_name" />
			</div>
			<div>
				<InputLabel
					for="jogger_composition"
					value="Jogger composition"
				/>
				<TextInput
					id="jogger_composition"
					v-model="form.jogger_composition"
					type="text"
					autocomplete="jogger_composition"
				/>
				<InputError :message="form.errors.jogger_composition" />
			</div>
			<div>
				<InputLabel
					for="jogger_fit"
					value="Jogger fit"
				/>
				<TextInput
					id="jogger_fit"
					v-model="form.jogger_fit"
					type="text"
					autocomplete="jogger_fit"
				/>
				<InputError :message="form.errors.jogger_fit" />
			</div>
			<div v-if="isAdmin">
				<InputLabel
					for="stock" 
					value="Stock"
				/>
				<TextInput	
					id="stock"
					type="number"
					min="0"
					v-model.number="form.stock"
				/>
				<InputError :message="form.errors.stock" />
			</div>
			<div>
				<InputLabel
					for="jogger_price"
					value="Jogger price"
				/>
				<input
					id="jogger_price"
					v-model="form.jogger_price"
					type="number"
					autocomplete="jogger_price"
				/>
				<InputError :message="form.errors.jogger_price" />
			</div>
			<div>
				<InputLabel
					for="jogger_img1"
					value="Jogger img1"
				/>
				<input
					id="jogger_img1"
					type="file"
					@input="form.jogger_img1 = $event.target.files[0]"
					accept="image/*"
				/>
				<InputError :message="form.errors.jogger_img1" />
			</div>
			<div>
				<InputLabel
					for="jogger_img2"
					value="Jogger img2"
				/>
				<input
					id="jogger_img2"
					type="file"
					@input="form.jogger_img2 = $event.target.files[0]"
					accept="image/*"
				/>
				<InputError :message="form.errors.jogger_img2" />
			</div>
		</template>

		<template #actions>
			<PrimaryButton @click.prevent="handleSubmit">
				{{ updating ? "Update" : "Create" }}
			</PrimaryButton>
		</template>
	</FormSection>
</template>
