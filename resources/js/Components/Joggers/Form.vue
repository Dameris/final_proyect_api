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
import { onMounted } from "vue";

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
		default: () => [],
	},
});

const emit = defineEmits(["submit"]);

onMounted(() => {
    if (!props.updating) {
        props.form.stock = {};
        
        if (props.sizes && Array.isArray(props.sizes)) {
            props.sizes.forEach(size => {
                props.form.stock[size] = 0;
            });
        }
    }
});

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
        router.post(route("joggers.store"), props.form, {
            preserveScroll: true,
            forceFormData: true,
        });
    }
};
</script>

<template>
	<FormSection class="formSection">
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
				<select
					id="jogger_composition"
					v-model="form.jogger_composition"
					class="input"
				>
					<option disabled value="">Select composition</option>
					<option
						v-for="comp in compositions"
						:key="comp"
						:value="comp"
					>
						{{ comp }}
					</option>
				</select>
				<InputError :message="form.errors.jogger_composition" />
			</div>
			<div>
				<InputLabel
					for="jogger_fit"
					value="Jogger fit"
				/>
				<select
					id="jogger_fit"
					v-model="form.jogger_fit"
					class="input"
				>
					<option disabled value="">Select fit</option>
					<option
						v-for="fit in fits"
						:key="fit"
						:value="fit"
					>
						{{ fit }}
					</option>
				</select>
				<InputError :message="form.errors.jogger_fit" />
			</div>
			<div v-if="isAdmin && form.stock">
    			<h3>Stock by sizes</h3>
    
    			<div v-for="size in sizes" :key="size" class="form-group">
        			<label :for="'stock_' + size">Stock Size: {{ size }}</label>
        			<input 
            			:id="'stock_' + size"
            			type="number" 
            			min="0"
            			v-model.number="form.stock[size]"
            			class="form-control"
        			/>
    			</div>
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

<style scoped>
@import "../../../css/pages/form.css";
</style>