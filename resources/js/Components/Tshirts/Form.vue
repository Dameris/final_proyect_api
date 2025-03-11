<!-- @format -->

<script>
export default {
	name: "TshirtsForm",
};
</script>

<script setup>
import FormSection from "../FormSection.vue";
import InputError from "../InputError.vue";
import InputLabel from "../InputLabel.vue";
import PrimaryButton from "../PrimaryButton.vue";
import TextInput from "../TextInput.vue";

defineProps({
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

defineEmits(["submit"]);

// const handleFileChange = (field, event) => {
// 	try {
// 		const file = event.target.files[0];
// 		if (file) {
// 			form[field] = file;
// 		}
// 	} catch (error) {
// 		console.error("Error while handling file change:", error);
// 	}
// };
</script>

<template>
	<FormSection @submitted="$emit('submit')">
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
				<TextInput
					id="tshirt_price"
					v-model="form.tshirt_price"
					type="text"
					autocomplete="tshirt_price"
				/>
				<InputError :message="form.errors.tshirt_price" />
			</div>
			<!-- <div>
				<InputLabel
					for="tshirt_img1"
					value="Tshirt img1"
				/>
				<input
					id="tshirt_img1"
					type="file"
					@change="handleFileChange('tshirt_img1', $event)"
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
					@change="handleFileChange('tshirt_img2', $event)"
				/>
				<InputError :message="form.errors.tshirt_img2" />
			</div> -->
		</template>

		<template #actions>
			<PrimaryButton>
				{{ updating ? "Update" : "Create" }}
			</PrimaryButton>
		</template>
	</FormSection>
</template>
