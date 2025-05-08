<script setup>
import { computed, ref } from "vue";
import { usePage, Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

const { props } = usePage();
const user = computed(() => props.auth?.user);
const canLogin = computed(() => props.canLogin);

console.log("User:", user.value);
console.log("Can Login:", canLogin.value);
console.log("All props:", props);

// Estado para el menú de la tienda y la búsqueda
const isShopOpen = ref(false);
const isSearchOpen = ref(false);
const searchQuery = ref("");
const searchResults = ref([]);

// Función para toggle del menú de la tienda
const toggleShopSlide = () => {
	isShopOpen.value = !isShopOpen.value;
};

// Función para toggle de la búsqueda
const toggleSearch = () => {
	isSearchOpen.value = !isSearchOpen.value;
};

// Función para realizar búsqueda
const search = async () => {
	if (searchQuery.value.length >= 3) {
		try {
			const response = await axios.get("/search", {
				params: {
					query: searchQuery.value,
				},
			});
			searchResults.value = response.data;
		} catch (error) {
			console.error("Error making the search", error);
		}
	} else {
		searchResults.value = [];
	}
};

// Función para logout
const logout = () => {
	Inertia.post(
		route("logout"),
		{},
		{
			onSuccess: () => {
				Inertia.reload();
			},
		},
	);
};
</script>

<template>
	<header>
		<!-- Sección superior del encabezado -->
		<div class="headerTop">
			<!-- Botón (foto perfil) que lleva al perfil o al login -->
			<ul class="header__list">
				<li>
					<Link
						class="header__btn--logIn"
						:href="route('profile')"
						id="user-profile_link"
					>
						<img
							:src="user?.profile_photo_path || '/img/pfp_image.png'"
							alt="Profile Picture"
							class="header__profile--img"
						/>
						<p v-if="!canLogin">{{ user?.first_name && user?.last_name }}</p>
					</Link>
				</li>
			</ul>

			<ul class="header__list" v-if="canLogin">
				<li>
					<Link
						class="header__btn--logIn"
						:href="route('login')"
					>
						LOG IN
					</Link>
				</li>
				<li>
					<Link
						class="header__btn--signUp"
						:href="route('signup')"
					>
						SIGN UP
					</Link>
				</li>
			</ul>
			<ul class="header__list" v-else>
				<li>
					<Link
						class="header__btn--logIn"
						href="/"
						@click="logout"
					>
						LOG OUT
					</Link>
				</li>
			</ul>
			<p>{{ canLogin ? 'Login available' : 'Login not available' }}</p>
		</div>

		<!-- Sección inferior del encabezado -->
		<div>
			<nav class="headerBottom">
				<!-- Botón para abrir el menú desplegable -->
				<button
					class="btn__open--slideMenu"
					@click="toggleShopSlide"
				>
					OPEN
				</button>

				<!-- Menú desplegable -->
				<div
					class="header__slideMenu"
					:class="{ visible: isShopOpen }"
				>
					<button
						class="btn__close--slideMenu"
						@click="toggleShopSlide"
					>
						CLOSE
					</button>
					<ul class="header__list--slide">
						<li>
							<Link
								class="header__btn--logIn"
								:href="route('login')"
							>
								LOG IN
							</Link>
						</li>
						<li>
							<Link
								class="header__btn--signUp"
								:href="route('signup')"
							>
								SIGN UP
							</Link>
						</li>
						<li>
							<Link
								class="header__btn--logIn"
								:href="route('profile')"
								id="user-profile_link"
							>
								<img
									:src="user?.profile_photo_path || '/img/pfp_image.png'"
									alt="Profile Picture"
									class="header__profile--img"
								/>
							</Link>
						</li>
						<li>
							<Link
								class="header__btn"
								:href="route('tshirts.index')"
							>
								<strong>SHOP</strong>
							</Link>
						</li>
						<li>
							<Link
								class="header__link"
								:href="route('about')"
							>
								ABOUT US
							</Link>
						</li>
						<li>
							<button
								class="header__search--btn"
								@click="toggleSearch"
							>
								SEARCH
							</button>

							<div
								v-if="isSearchOpen"
								class="header__fullscreenSearch"
							>
								<div class="header__fullscreenSearch--bar">
									<button
										class="header__fullscreen--closeSearch"
										@click="toggleSearch"
									>
										✖
									</button>
									<input
										type="text"
										placeholder="Type to search..."
										v-model="searchQuery"
										@input="search"
									/>
								</div>
								<div
									v-if="Array.isArray(searchResults) && searchResults.length > 0"
									class="header__fullscreenSearch--results"
								>
									<ul>
										<li
											v-for="result in searchResults"
											:key="result.id"
										>
											<Link
												:href="route('tshirts.show', { tshirt: result.id })"
												class="header__link"
											>
												<img
													:src="'/storage/img/tshirts/' + result.tshirt_img1"
													alt="Tshirt Image"
												/>
												<br />
												{{ result.tshirt_name }} - {{ result.tshirt_price }}€
											</Link>
										</li>
									</ul>
								</div>
							</div>
						</li>
						<li>
							<Link
								class="header__btn"
								:href="route('shoppingCart')"
								>CART</Link
							>
						</li>
					</ul>
				</div>

				<!-- Lista de elementos del menú principal -->
				<ul class="header__list">
					<li>
						<Link
							class="header__btn"
							:href="route('tshirts.index')"
						>
							<strong>SHOP</strong>
						</Link>
					</li>
					<li>
						<Link
							class="header__link"
							:href="route('about')"
						>
							ABOUT US
						</Link>
					</li>
				</ul>

				<!-- Logo y enlace al inicio -->
				<Link href="/">
					<img
						src="../../../../resources/img/logo/SkyUrban-logo-blueS.png"
						alt="SKYURBAN"
						class="header__logo"
					/>
				</Link>

				<!-- Campo de búsqueda -->
				<div class="header__searchContainer header__list">
					<button
						class="header__search--btn"
						@click="toggleSearch"
					>
						SEARCH
					</button>

					<div
						v-if="isSearchOpen"
						class="header__fullscreenSearch"
					>
						<div class="header__fullscreenSearch--bar">
							<button
								class="header__fullscreen--closeSearch"
								@click="toggleSearch"
							>
								✖
							</button>
							<input
								type="text"
								placeholder="Type to search..."
								v-model="searchQuery"
								@input="search"
							/>
						</div>
						<div
							class="header__fullscreenSearch--results"
							v-if="searchResults.length > 0"
						>
							<ul>
								<li
									v-for="result in searchResults"
									:key="result.id"
								>
									<Link
										:href="route('tshirts.show', { tshirt: result.id })"
										class="header__link"
									>
										<img
											:src="'/storage/img/tshirts/' + result.tshirt_img1"
											alt="Tshirt Image"
										/>
										<br />
										{{ result.tshirt_name }} - {{ result.tshirt_price }}€
									</Link>
								</li>
							</ul>
						</div>
					</div>

					<!-- Carrito de compra -->
					<Link
						class="header__btn"
						:href="route('shoppingCart')"
						>CART</Link
					>
				</div>
			</nav>
		</div>
	</header>
</template>

<style scoped>
@import "../../../css/shared/shopHeader.css";
</style>
