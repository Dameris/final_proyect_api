<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";

const props = defineProps({
	user: {
		type: Object,
		required: true,
		default: () => ({ permissions: [] })
	},
	errors: Object,
});

// Formulario con los datos del usuario
const form = useForm({
    _method: 'POST',
	first_name: props.user.first_name,
	last_name: props.user.last_name,
	email: props.user.email,
	gender: props.user.gender,
	profile_photo_path: null,
});

const isEditing = ref(false);
const photoPreview = ref(props.user.profile_photo_path || '/img/pfp_image.png');
const selectedFile = ref(null);

const editUserInfo = () => {
	isEditing.value = true;
};

const cancelEdit = () => {
	isEditing.value = false;
	form.reset();
	photoPreview.value = props.user.profile_photo_path || '/img/pfp_image.png';
	selectedFile.value = null;
};

// Muestra la vista previa de la imagen y almacena el archivo
const handleFileChange = (event) => {
	const file = event.target.files[0];
	if (file) {
		selectedFile.value = file; // Guardamos el archivo

		// Vista previa
		const reader = new FileReader();
		reader.onload = (e) => {
			photoPreview.value = e.target.result;
		};
		reader.readAsDataURL(file);
	}
};

// Envía el formulario con datos y la imagen
const submit = () => {
	const formData = new FormData();
	formData.append("first_name", form.first_name);
	formData.append("last_name", form.last_name);
	formData.append("email", form.email);
	formData.append("gender", form.gender);

	// Solo agregar la imagen si el usuario seleccionó una nueva
	if (selectedFile.value) {
		formData.append("profile_photo_path", selectedFile.value);
	}

	form.post(route("profile.update"), {
		preserveScroll: true,
		data: formData,
		onSuccess: () => {
			isEditing.value = false;
			selectedFile.value = null;
		},
	});
};

const logout = () => {
	form.post(route("logout"));
};
</script>

<template>
	<AppLayout>
		<div class="userProfile">
			<!-- Vista del usuario -->
			<p class="userProfile__p" v-if="!isEditing">
				<img :src="photoPreview" alt="Profile Picture" />
				<br />
				<strong class="userProfile__strong"> {{ user.first_name }} </strong>
				<strong class="userProfile__strong"> {{ user.last_name }} </strong>
				<strong class="userProfile__strong"> {{ user.email }} </strong>
				<strong class="userProfile__strong"> {{ user.gender }} </strong>
				<label class="userProfile__changePhoto" @click="editUserInfo">EDIT USER INFO</label>
				<br />
				<p class="userProfile__btn" to="/private/wishlist">WISHLIST</p>
				<br />
				<Link class="userProfile__btn" href="/" @click="logout">LOG OUT</Link>
			</p>
			
			<p class="userProfile__p" v-if="isEditing">
				<UpdateProfileInformationForm />
			</p>

			<!-- Edición de usuario -->
			<!-- <p class="userProfile__p" v-if="isEditing">
				<img :src="photoPreview" alt="Profile Picture" class="profile-preview" />
				<input type="file" accept="image/*" @change="handleFileChange" class="userProfile__input" />
				<br />
				
				<input class="userProfile__strong" v-model="form.first_name" type="text" placeholder="First Name" />
				<input class="userProfile__strong" v-model="form.last_name" type="text" placeholder="Last Name" />
				<input class="userProfile__strong" v-model="form.email" type="email" placeholder="Email" />
				<input class="userProfile__strong" v-model="form.gender" type="text" placeholder="Gender" />
				
				<button class="userProfile__btn" @click="submit">Save</button> <br />
				<button class="userProfile__btn" @click="cancelEdit">Cancel</button>
			</p> -->
		</div>
	</AppLayout>
</template>

<style scoped>
@import "../../css/pages/userProfile.css";

.profile-preview {
	width: 100px;
	height: 100px;
	border-radius: 50%;
	object-fit: cover;
	margin-bottom: 10px;
}

.userProfile__input {
	display: block;
	margin: 10px 0;
}
</style>
