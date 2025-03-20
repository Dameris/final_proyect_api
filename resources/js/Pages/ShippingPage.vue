<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
	<AppLayout>
		<div class="shippingContainer">
			<!-- Tittle Section -->
			<div class="shippingContainer__title">
				<h1>Shipping & Delivery</h1>
				<p>Fast, reliable, and secure shipping for all your orders.</p>
			</div>

			<!-- Shipping Information -->
			<div class="shippingContainer__details">
				<!-- Delivery Times -->
				<section class="shippingContainer__details--card">
					<img
						src="../../../public/img/delivery-logo.png"
						alt="Delivery Time Icon"
					/>
					<h2>Delivery Times</h2>
					<ul>
						<li>
							<strong>Standard Shipping</strong> <br />
							5-7 business days
						</li>
						<li>
							<strong>Express Shipping</strong> <br />
							2-3 business days
						</li>
						<li>
							<strong>International Shipping</strong> <br />
							7-14 business days
						</li>
					</ul>
				</section>

				<!-- Shipping Costs -->
				<section class="shippingContainer__details--card">
					<img
						src="../../../public/img/shipping-logo.png"
						alt="Shipping Cost Icon"
					/>
					<h2>Shipping Costs</h2>
					<ul>
						<li>
							<strong>Orders over 50€</strong> <br />
							Free shipping
						</li>
						<li>
							<strong>Standard Shipping</strong> <br />
							4.99€
						</li>
						<li>
							<strong>Express Shipping</strong> <br />
							9.99€
						</li>
						<li>
							<strong>International Shipping</strong> <br />
							14.99€
						</li>
					</ul>
				</section>

				<!-- Order Issues -->
				<section class="shippingContainer__details--card">
					<img
						src="../../../public/img/support-logo.png"
						alt="Support Icon"
					/>
					<h2>Need Help?</h2>
					<p>
						If you have issues with your order, please
						<a href="/contact">contact our support team</a>.
					</p>
				</section>
			</div>
		</div>
	</AppLayout>
</template>

<style scoped>
@import "../../css/pages/shippingPage.css";
</style>
