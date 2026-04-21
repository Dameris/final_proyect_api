<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import SuccessAlert from "@/Components/Shared/SuccessAlert.vue";
import ErrorAlert from "@/Components/Shared/ErrorAlert.vue";

const props = usePage().props;

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const passwordError = ref(false);

const showSuccessAlert = ref(false);
const showErrorAlert = ref(false);
const successMessage = ref("");
const errorMessage = ref("");

const validatePassword = () => {
	const lengthRegex = /.{8,}/;
	const upperCaseRegex = /[A-Z]/;
	const specialCharRegex = /[*, +, -, ., @, #, %, &, _, ~, ^, /, <, >]/;

	const hasLength = lengthRegex.test(form.password);
	const hasUpperCase = upperCaseRegex.test(form.password);
	const hasSpecialChar = specialCharRegex.test(form.password);

	passwordError.value = !hasLength || !hasUpperCase || !hasSpecialChar;
};

const submit = () => {
    validatePassword();

    if (passwordError.value) {
        showErrorAlert.value = true;
        errorMessage.value = "Invalid password format.";
        return;
    }

    form.post(route("password.store"), {
        onSuccess: () => {
            showSuccessAlert.value = true;
            successMessage.value = "PASSWORD UPDATED ✅";

            setTimeout(() => {
                window.location.href = "/login";
            }, 2000);
        },
        onError: () => {
            showErrorAlert.value = true;
            errorMessage.value = "Reset failed.";
        },
    });
};
</script>

<template>
    <AppLayout>
        <div class="formBox">
            <p class="formBox__title">NEW PASSWORD</p>

            <form
				class="formBox-form"
				@submit.prevent="submit"
			>
                <input
					class="formBox-form__input"
					type="password"
					placeholder="PASSWORD"
					required
					v-model="form.password"
					@input="validatePassword"
				/>

                <input
					class="formBox-form__input"
                    type="password"
                    placeholder="CONFIRM PASSWORD"
					required
                    v-model="form.password_confirmation"
					@input="validatePassword"
                />

                <button
					class="formBox-form__btn"
					type="submit"
				>
                    RESET PASSWORD
                </button>
            </form>

            <SuccessAlert v-if="showSuccessAlert" :message="successMessage" />
            <ErrorAlert v-if="showErrorAlert" :message="errorMessage" />
        </div>
    </AppLayout>
</template>

<style scoped>
@import "../../../css/auth/formBox.css";
</style>