<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import SuccessAlert from "@/Components/Shared/SuccessAlert.vue";
import ErrorAlert from "@/Components/Shared/ErrorAlert.vue";

const form = useForm({
    email: "",
});

const emailError = ref(false);

const showSuccessAlert = ref(false);
const showErrorAlert = ref(false);
const successMessage = ref("");
const errorMessage = ref("");

const validateEmail = () => {
    const pattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    emailError.value = !pattern.test(form.email);
};

const submit = () => {
    validateEmail();

    if (emailError.value) {
        showErrorAlert.value = true;
        errorMessage.value = "Enter a valid email.";
        return;
    }

    form.post(route("password.email"), {
        onSuccess: () => {
            showSuccessAlert.value = true;
            successMessage.value = "CHECK YOUR EMAIL 📩";

            setTimeout(() => {
                showSuccessAlert.value = false;
            }, 2500);
        },
        onError: () => {
            showErrorAlert.value = true;
            errorMessage.value = "Something went wrong.";
        },
    });
};
</script>

<template>
    <AppLayout>
        <div class="formBox">
            <p class="formBox__title">RESET PASSWORD</p>

            <form @submit.prevent="submit">
                <input
					class="formBox-form__input"
					type="email"
					title="email"
					name="email"
					placeholder="EMAIL"
					required
					v-model="form.email"
					@input="validateEmail"
				/>
                <span
					v-if="emailError"
					class="formBox-form--errorMessage"
				>
					Please enter a valid email address.
				</span>

                <button
					class="formBox-form__btn"
					type="submit"
				>
                    SEND RESET LINK
                </button>
            </form>

            <SuccessAlert v-if="showSuccessAlert" :message="successMessage" />
            <ErrorAlert v-if="showErrorAlert" :message="errorMessage" />
        </div>
    </AppLayout>
</template>

<style scoped>
@import "../../../css/auth/forgotPassword.css";
</style>
