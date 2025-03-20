<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";

// Lista de preguntas y respuestas
const faqs = ref([
	{
		question: "What is SkyUrban?",
		answer:
			"SkyUrban is a fashion brand that blends urban style with comfort, offering high-quality apparel for everyday wear.",
		open: false,
	},
	{
		question: "What payment methods do you accept?",
		answer: "We accept Visa, Mastercard, PayPal, Apple Pay, and Google Pay.",
		open: false,
	},
	{
		question: "How long does shipping take?",
		answer: "Shipping usually takes 3-7 business days, depending on your location.",
		open: false,
	},
	{
		question: "Can I return an item?",
		answer:
			"Yes, we accept returns within 30 days of purchase as long as the item is unworn and in its original packaging.",
		open: false,
	},
	{
		question: "How do I track my order?",
		answer: "Once your order is shipped, you’ll receive an email with a tracking number.",
		open: false,
	},
]);

// Función para alternar la visibilidad de la respuesta
const toggleFAQ = (index) => {
	faqs.value[index].open = !faqs.value[index].open;
};
</script>

<template>
	<AppLayout>
		<div class="faqsContainer">
			<h1>Frequently Asked Questions</h1>
			<div class="faqsContainer__list">
				<div
					v-for="(faq, index) in faqs"
					:key="index"
					class="faqsContainer__list--item"
				>
					<!-- Pregunta -->
					<div
						class="faqsContainer__list--question"
						@click="toggleFAQ(index)"
					>
						{{ faq.question }}
						<span>{{ faq.open ? "▲" : "▼" }}</span>
					</div>

					<!-- Respuesta -->
					<p
						v-if="faq.open"
						class="faqsContainer__list--answer"
					>
						{{ faq.answer }}
					</p>
				</div>
			</div>
		</div>
	</AppLayout>
</template>

<style scoped>
@import "../../css/pages/faqsPage.css";
</style>
