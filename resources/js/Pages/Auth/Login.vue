<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import SuccessAlert from "@/Components/Shared/SuccessAlert.vue";
import ErrorAlert from "@/Components/Shared/ErrorAlert.vue";
import { Inertia } from "@inertiajs/inertia";
import { onMounted } from "vue";

// Variables reactivas
const pageTitle = "Log In SkyUrban";
const showContent = ref(false);

// Formulario con Inertia
const form = useForm({
	email: "",
	password: "",
	remember: false,
});

// Errores de validación
const emailError = ref(false);
const passwordError = ref(false);

// Control de alertas
const showSuccessAlert = ref(false);
const showErrorAlert = ref(false);
const successMessage = ref("");
const errorMessage = ref("");

// Mostrar contenido después de 2 segundos
onMounted(() => {
	setTimeout(() => {
		showContent.value = true;
	}, 2000);
});

// Validación del correo electrónico
const validateEmail = () => {
	const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}$/;
	emailError.value = !emailPattern.test(form.email);
};

// Validación de la contraseña
const validatePassword = () => {
	const lengthRegex = /.{8,}/;
	const upperCaseRegex = /[A-Z]/;
	const specialCharRegex = /[*, +, -, ., @, #, %, &, _, ~, ^, /, <, >]/;

	const hasLength = lengthRegex.test(form.password);
	const hasUpperCase = upperCaseRegex.test(form.password);
	const hasSpecialChar = specialCharRegex.test(form.password);

	passwordError.value = !hasLength || !hasUpperCase || !hasSpecialChar;
};

// Envío del formulario
const submit = () => {
	validateEmail();
	validatePassword();

	if (emailError.value || passwordError.value) {
		showErrorAlert.value = true;
		errorMessage.value = "All input fields must contain valid information.";
		setTimeout(() => {
			showErrorAlert.value = false;
			errorMessage.value = "";
		}, 2000);
		return;
	}

	form
		.transform((data) => ({
			...data,
			remember: form.remember ? "on" : "",
		}))
		.post(route("login"), {
			onSuccess: () => {
				showSuccessAlert.value = true;
				successMessage.value = "LOG IN SUCCESSFUL!";
				form.reset("password");
				Inertia.reload({ only: ["auth"] });
				setTimeout(() => {
					showSuccessAlert.value = false;
				}, 2000);
			},
			onError: (errors) => {
				showErrorAlert.value = true;
				errorMessage.value = errors.email || "Invalid email or password. Please try again.";
				setTimeout(() => {
					showErrorAlert.value = false;
					errorMessage.value = "";
				}, 2000);
			},
		});
};
</script>

<template>
	<AppLayout>
		<div class="formBox">
			<p class="formBox__title">LOG IN</p>
			<p class="formBox__label">
				WELCOME BACK TO <br />
				THE <strong>SKY</strong>: <br />
				LET THE <strong>URBAN</strong> <br />
				ADVENTURE RESUME
			</p>

			<!-- Formulario log in -->
			<form
				class="formBox-form"
				id="formContact"
				@submit.prevent="submit"
			>
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
				<input
					class="formBox-form__input"
					type="password"
					title="password"
					name="password"
					placeholder="PASSWORD"
					required
					v-model="form.password"
					@input="validatePassword"
				/>
				<span
					v-if="passwordError"
					class="formBox-form--errorMessage"
				>
					Please enter a valid password (8+ characters, 1+ uppercase, 1+ special character).
				</span>

				<button
					class="formBox-form__btn"
					type="submit"
					title="log_in"
					name="log_in"
				>
					LOG IN
				</button>
			</form>
			<p class="logIn__link-signUp">
				NOT A MEMBER?
				<Link
					href="signup"
					id="link"
				>
					SIGN UP
				</Link>
			</p>

			<SuccessAlert
				v-if="showSuccessAlert"
				:message="successMessage"
			/>
			<ErrorAlert
				v-if="showErrorAlert"
				:message="errorMessage"
			/>
		</div>
	</AppLayout>
</template>

<style scoped>
@import "../../../css/auth/log-in.css";
</style>
