<!-- @format -->

<script>
export default {
	name: "ShopFooter",
};
</script>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
</script>

<template>
	<footer>
		<!-- Sección superior del pie de página -->
		<div class="footerTop">
			<!-- Logotipo y redes sociales -->
			<div class="footer__logoSocial">
				<img
					src="../../../../resources/img/logo/SkyUrban-logo-blueS.png"
					alt="SkyUrban Logo"
				/>
				<p>
					<a href="mailto:help@sky-urban.com">help@sky-urban.com</a>
					<!-- Iconos de redes sociales -->
					<span class="footer__socialMedia">
						<img
							src="../../../../resources/img/social_logos/Instagram.png"
							alt="Instagram Logo"
						/>
						<img
							src="../../../../resources/img/social_logos/Twitter.png"
							alt="Twitter Logo"
						/>
						<img
							src="../../../../resources/img/social_logos/YouTube.png"
							alt="YouTube Logo"
						/>
						<img
							src="../../../../resources/img/social_logos/TikTok.png"
							alt="TikTok Logo"
						/>
					</span>
				</p>
			</div>

			<!-- Cuadro de información: "BORING STUFF" -->
			<div class="footer__infoBox">
				<h5>BORING STUFF</h5>
				<p class="footer__infoContent">
					PRIVACY POLICY <br />
					TERMS <br />
					LEGAL NOTICE
				</p>
			</div>

			<!-- Cuadro de información: "WEB SITE" -->
			<div class="footer__infoBox">
				<h5>WEB SITE</h5>
				<p class="footer__infoContent">
					SHOP <br />
					<Link
						class="footer__link"
						:href="route('about')"
					>
						ABOUT US
					</Link>
					<br />
					<Link
						class="footer__link"
						:href="route('home')"
					>
						HOME
					</Link>
				</p>
			</div>

			<!-- Cuadro de información: "SUPPORT" -->
			<div class="footer__infoBox">
				<h5>SUPPORT</h5>
				<p class="footer__infoContent">
					<Link
						class="footer__link"
						:href="route('contact')"
					>
						CONTACT
					</Link>
					<br />
					SHIPPING AND DELIVERY <br />
					<Link
						class="footer__link"
						:href="route('faqs')"
					>
						FAQs
					</Link>
				</p>
			</div>

			<!-- Registro para la "Newsletter" -->
			<div class="footer__infoBox">
				<h5>JOIN THE SKY</h5>
				<p class="footer__infoContent">
					WANT A 10% OFF IN YOUR FIRST ORDER? <br />
					SIGN UP TO OUR EMAIL LIST <br />
				</p>
				<div class="footer__emailBox">
					<input
						type="email"
						placeholder="EMAIL"
						class="footer__emailInput"
					/>
					<button class="footer__joinBtn"><strong>JOIN</strong></button>
				</div>
			</div>
		</div>
		<!-- Sección inferior del pie de página -->
		<div class="footerBottom">
			<h4>SKYURBAN</h4>
		</div>
	</footer>
</template>

<style scoped>
@import "../../../css/shared/shopFooter.css";
</style>
