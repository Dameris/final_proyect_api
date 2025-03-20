<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
	<AppLayout>
		<div class="aboutContainer">
			<!-- Sección Título -->
			<section class="aboutContainer__title">
				<h1>About SkyUrban</h1>
				<p>WHERE STREETWEAR MEETS THE SKY. ELEVATE YOUR STYLE WITH US.</p>
			</section>

			<!-- Sección Historia -->
			<section class="aboutContainer__history">
				<h2>Our Story</h2>
				<p>
					SkyUrban was born from a passion for urban culture and modern fashion. We believe in creating apparel that
					blends comfort, quality, and contemporary style. From city streets to high skies, our designs are made to move
					with you.
				</p>
			</section>

			<!-- Sección Misión y Valores -->
			<section class="aboutContainer__mission">
				<h2>Our Mission</h2>
				<p>
					We aim to redefine urban fashion by offering high-quality, trend-driven clothing that represents
					self-expression and individuality.
				</p>
			</section>

			<!-- Sección Compromiso -->
			<section class="aboutContainer__engagement">
				<h2>Our Engagement</h2>
				<p>
					We focus on sustainability, ethical sourcing, and ensuring that our products not only look good but also feel
					great for the planet.
				</p>
			</section>

			<!-- Imagen o Cita Inspiradora -->
			<section class="aboutContainer__inspiration">
				<blockquote>
					"Fashion is not just about clothing, it's about attitude, confidence, and the way you carry yourself."
				</blockquote>
			</section>
		</div>
	</AppLayout>
</template>

<style scoped>
@import "../../css/pages/aboutPage.css";
</style>
