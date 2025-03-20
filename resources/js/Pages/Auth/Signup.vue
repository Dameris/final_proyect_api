<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import SuccessAlert from "@/Components/Shared/SuccessAlert.vue";
import ErrorAlert from "@/Components/Shared/ErrorAlert.vue";

// Variables reactivas
const pageTitle = "Sign Up SkyUrban";
const showContent = ref(false);

// Manejo del formulario
const form = useForm({
	email: "",
	password: "",
	first_name: "",
	last_name: "",
	gender: "",
});

// Variables de validación de errores
const emailError = ref(false);
const passwordError = ref(false);
const first_nameError = ref(false);
const last_nameError = ref(false);

// Variables de control de alertas
const showSuccessAlert = ref(false);
const showErrorAlert = ref(false);
const successMessage = ref("");
const errorMessage = ref("");

onMounted(() => {
	setTimeout(() => {
		showContent.value = true;
	}, 2000);
});

// Método para validar el correo electrónico
const validateEmail = () => {
	const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}$/;
	emailError.value = !emailPattern.test(form.email);
};

// Método para validar la contraseña
const validatePassword = () => {
	const lengthRegex = /.{8,}/;
	const upperCaseRegex = /[A-Z]/;
	const specialCharRegex = /[*, +, -, ., @, #, %, &, _, ~, ^, /, <, >]/;

	const hasLength = lengthRegex.test(form.password);
	const hasUpperCase = upperCaseRegex.test(form.password);
	const hasSpecialChar = specialCharRegex.test(form.password);

	passwordError.value = !hasLength || !hasUpperCase || !hasSpecialChar;
};

// Método para validar el nombre
const validatefirst_name = () => {
	const namePattern = /^[A-Za-zÀ-ÿ\s]{1,}$/;
	first_nameError.value = !namePattern.test(form.first_name);
};

// Método para validar el apellido
const validatelast_name = () => {
	const namePattern = /^[A-Za-zÀ-ÿ\s]{1,}$/;
	last_nameError.value = !namePattern.test(form.last_name);
};

// Método para seleccionar el género
const selectGender = (gender) => {
	form.gender = form.gender !== gender ? gender : "";
};

const submit = () => {
	validateEmail();
	validatePassword();
	validatefirst_name();
	validatelast_name();

	if (emailError.value || passwordError.value || first_nameError.value || last_nameError.value) {
		showErrorAlert.value = true;
		errorMessage.value = "All input fields must contain valid information.";

		// Limpiar el mensaje de error después de 2 segundos
		setTimeout(() => {
			showErrorAlert.value = false;
			errorMessage.value = "";
		}, 2000);
		return;
	}

	// Añadir el usuario a la base de datos
	form.post(route("signup"), {
		onSuccess: () => {
			showSuccessAlert.value = true;
			successMessage.value = "SIGN UP SUCCESSFUL!";

			// Restablecer los datos del formulario
			form.reset("email", "password", "first_name", "last_name", "gender");

			setTimeout(() => {
				showSuccessAlert.value = false;
			}, 2000);
		},

		onError: () => {
			showErrorAlert.value = true;
			errorMessage.value = "Error signing up. Please try again.";
		},
	});
};
</script>

<template>
	<AppLayout>
		<div class="formBox">
			<p class="formBox__title">SIGN UP</p>
			<p class="formBox__label">
				UNLOCK THE <strong>SKY</strong>: <br />
				YOUR GATEWAY TO <br />
				<strong>URBAN</strong> EXPLORATION
			</p>

			<!-- Formulario sign up -->
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
					Please enter a valid password (8+ characters, 1+ uppercase and 1+ special character).
				</span>
				<input
					class="formBox-form__input"
					type="text"
					title="first_name"
					name="first_name"
					placeholder="FIRST NAME"
					spellcheck="true"
					required
					v-model="form.first_name"
					@input="validatefirst_name"
				/>
				<span
					v-if="first_nameError"
					class="formBox-form--errorMessage"
				>
					Please enter a valid first name.
				</span>
				<input
					class="formBox-form__input"
					type="text"
					title="last_name"
					name="last_name"
					placeholder="LAST NAME"
					spellcheck="true"
					required
					v-model="form.last_name"
					@input="validatelast_name"
				/>
				<span
					v-if="last_nameError"
					class="formBox-form--errorMessage"
				>
					Please enter a valid last name.
				</span>
				<article class="signUp__genderSelection">
					<button
						class="signUp__genderSelection-btn"
						type="button"
						title="male"
						id="male"
						name="gender"
						required
						value="Male"
						:class="{ signUp__genderSelection_selected: form.gender === 'Male' }"
						@click="selectGender('Male')"
					>
						MALE
					</button>
					<button
						class="signUp__genderSelection-btn"
						type="button"
						title="female"
						name="gender"
						required
						value="Female"
						:class="{ signUp__genderSelection_selected: form.gender === 'Female' }"
						@click="selectGender('Female')"
					>
						FEMALE
					</button>
				</article>
				<article class="signUp__privacy-terms">
					<input
						class="signUp__privacy-terms-checkbox"
						type="checkbox"
						title="privacy-terms"
						name="privacy-terms"
						required
					/>
					<label for="privacy-terms">I agree to Sky Urban's Privacy Policy and Terms of Use.</label>
				</article>
				<button
					class="formBox-form__btn"
					type="submit"
					title="sign_up"
					name="sign_up"
				>
					SIGN UP
				</button>
			</form>
			<p class="signUp__link-logIn">
				ALREADY A MEMBER?
				<Link
					href="login"
					id="link"
				>
					LOG IN
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
@import "../../../css/auth/sign-up.css";
</style>
