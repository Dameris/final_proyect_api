<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    user: {
        type: Object,
        required: true,
        default: () => ({ first_name: '', last_name: '', email: '', gender: ''}),
    },
    errors: Object,
});

// Crear el formulario con los datos del usuario
const form = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    gender: props.user.gender,
    profile_photo_path: null,
});

const photoPreview = ref(props.user.profile_photo_path || '/img/pfp_image.png');

// Función para enviar el formulario
const submit = () => {
    const formData = new FormData();
    formData.append('first_name', form.first_name);
    formData.append('last_name', form.last_name);
    formData.append('email', form.email);
    formData.append('gender', form.gender);

    // Enviar los datos al backend usando Inertia.js
    form.post(route('profile.update'), {
        data: formData,
        onSuccess: () => {
            Inertia.visit(route('profile'));
        },
        onError: () => {
            alert('An error occurred!');
        },
    });
};
</script>

<template>
    <div class="updateForm__container">
        <form @submit.prevent="submit" class="updateForm__profileForm">
            <h2>Update Your Profile</h2>

            <div class="updateForm__profileForm--input">
                <label for="first_name">First Name</label>
                <input v-model="form.first_name" id="first_name" type="text" required placeholder="Enter your first name"/>
            </div>

            <div class="updateForm__profileForm--input">
                <label for="last_name">Last Name</label>
                <input v-model="form.last_name" id="last_name" type="text" required placeholder="Enter your last name"/>
            </div>

            <div class="updateForm__profileForm--input">
                <label for="email">Email</label>
                <input v-model="form.email" id="email" type="email" required placeholder="Enter your email"/>
            </div>

            <div class="updateForm__profileForm--input">
                <label for="gender">Gender</label>
                <select v-model="form.gender" id="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="updateForm__profileForm--input updateForm__profileForm--img">
                <img :src="photoPreview" alt="Profile Image Preview" class="updateForm__profileForm--imgPreview" />
            </div>

            <button type="submit" class="updateForm__profileForm--submit">Save Changes</button>
        </form>
    </div>
</template>

<style scoped>
@import "../../../../css/pages/updateProfileInfoForm.css";
</style>
