<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";
import { useAuthStore } from "../../js/stores/auth";
import { storeToRefs } from "pinia";

const auth = useAuthStore();
const { user } = storeToRefs(auth);

// Formulario con los datos del usuario
const form = useForm({
    _method: 'POST',
	first_name: user.first_name,
	last_name: user.last_name,
	email: user.email,
	gender: user.gender,
	profile_photo_path: null,
});

const isEditing = ref(false);
const photoPreview = ref(user.profile_photo_path || '/img/pfp_image.png');

const editUserInfo = () => {
	isEditing.value = true;
};

const cancelEdit = () => {
	isEditing.value = false;
	form.reset();
	photoPreview.value = user.profile_photo_path || '/img/pfp_image.png';
	selectedFile.value = null;
};

// Función para logout
const logout = async () => {
	await auth.logout();
	Inertia.visit("/");
};
</script>

<template>
	<AppLayout>
		<div class="userProfile">
			<!-- Vista del usuario -->
			<p class="userProfile__p" v-if="!isEditing">
				<img :src="photoPreview" alt="Profile Picture" />
				<br />
				<strong class="userProfile__strong"> {{ user?.first_name }} </strong>
				<strong class="userProfile__strong"> {{ user?.last_name }} </strong>
				<strong class="userProfile__strong"> {{ user?.email }} </strong>
				<strong class="userProfile__strong"> {{ user?.gender }} </strong>
				<label class="userProfile__changePhoto" @click="editUserInfo">EDIT USER INFO</label>
				<br />
				<!-- Cambiar WISHLIST por ORDER HISTORY -->
				<Link class="userProfile__btn" href="/private/orders-history">ORDER HISTORY</Link>
				<br />
				<Link class="userProfile__btn" href="/" @click="logout">LOG OUT</Link>
			</p>
			
			<p class="userProfile__p" v-if="isEditing">
				<UpdateProfileInformationForm :user="user" :errors="errors" />
			</p>
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
