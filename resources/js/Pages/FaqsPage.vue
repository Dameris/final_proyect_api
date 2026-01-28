<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";

const props = defineProps({
	faqs: {
        type: Array,
        default: () => []
    }
});

// Copia local
const localFaqs = ref(
	props.faqs.map(faq => ({
		...faq,
		open: false
	}))
);

// Función para alternar la visibilidad de la respuesta
const toggleFAQ = (index) => {
	localFaqs.value[index].open = !localFaqs.value[index].open;
};
</script>

<template>
	<AppLayout>
		<div class="faqsContainer">
			<h1>Frequently Asked Questions</h1>
			<div class="faqsContainer__list">
				<div
					v-for="(faq, index) in localFaqs"
					:key="faq.id"
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
