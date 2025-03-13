<!-- @format -->

<script>
export default {
	data() {
		return {
			pageTitle: "Contact SkyUrban",
			formData: {
				email: "",
				first_name: "",
				last_name: "",
				message: "",
			},
			emailError: false,
			first_nameError: false,
			last_nameError: false,
			messageError: false,
		};
	},

	methods: {
		// Método para validar el formulario antes de enviarlo
		checkForm() {
			if (this.emailError || this.first_nameError || this.last_nameError || this.messageError) {
				alert("All input fields must contain valid information.");
			} else {
				this.sendData();
			}
		},

		// Método para enviar los datos al backend
		sendData() {
			this.$inertia.post(route("contact"), this.formData, {
				onSuccess: () => {
					alert("Form has been sent successfully.");
					this.formData.email = "";
					this.formData.first_name = "";
					this.formData.last_name = "";
					this.formData.message = "";
				},
				onError: (errors) => {
					console.error("Error submitting form:", errors);
					alert("Error");
				},
			});
		},

		// Métodos para validar los campos
		validateEmail() {
			const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}$/;
			this.emailError = !emailPattern.test(this.formData.email);
		},

		validatefirst_name() {
			const namePattern = /^[A-Za-zÀ-ÿ\s]{1,}$/;
			this.first_nameError = !namePattern.test(this.formData.first_name);
		},

		validatelast_name() {
			const namePattern = /^[A-Za-zÀ-ÿ\s]{1,}$/;
			this.last_nameError = !namePattern.test(this.formData.last_name);
		},

		validateMessage() {
			this.messageError = this.formData.message.length < 20;
		},
	},
};
</script>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
	<AppLayout>
		<div class="formBox">
			<p class="formBox__title">CONTACT</p>
			<p class="formBox__label">
				CONTACT WITH <br />
				<strong>SKYURBAN</strong>
			</p>
			<!-- Formulario de contacto -->
			<form
				class="formBox-form"
				id="formContact"
				@submit.prevent="checkForm"
			>
				<input
					class="formBox-form__input"
					type="email"
					title="email"
					name="email"
					placeholder="EMAIL"
					required
					v-model="formData.email"
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
					type="text"
					title="first_name"
					name="first_name"
					placeholder="FIRST NAME"
					spellcheck="true"
					required
					v-model="formData.first_name"
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
					v-model="formData.last_name"
					@input="validatelast_name"
				/>
				<span
					v-if="last_nameError"
					class="formBox-form--errorMessage"
				>
					Please enter a valid last name.
				</span>
				<textarea
					class="form__textarea"
					title="message"
					name="message"
					placeholder="YOUR MESSAGE"
					spellcheck="true"
					required
					v-model="formData.message"
					@input="validateMessage"
				></textarea>
				<span
					v-if="messageError"
					class="formBox-form--errorMessage"
				>
					Message must have at least 20 characters.
				</span>
				<button
					class="formBox-form__btn"
					type="submit"
					title="send"
					name="send"
				>
					SEND
				</button>
			</form>
		</div>
	</AppLayout>
</template>

<style scoped>
@import "../../css/pages/contactPage.css";
</style>
