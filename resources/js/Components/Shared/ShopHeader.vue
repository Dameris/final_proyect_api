<script setup>
import { computed, ref, watch } from "vue";
import { Link, usePage } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

// Obtener la página y las propiedades
const page = usePage();
const isAuthenticated = computed(() => !!page.props.isAuthenticated);
const user = computed(() => page.props.isAuthenticated || null);

console.log(isAuthenticated.value);

// Estado reactivo
const isSearchOpen = ref(false);
const isShopOpen = ref(false);
const searchQuery = ref("");
const searchResults = ref([]);

// Métodos
const toggleSearch = () => {
	isSearchOpen.value = !isSearchOpen.value;
};

const toggleShopSlide = () => {
	isShopOpen.value = !isShopOpen.value;
};

const search = () => {
	if (searchQuery.value.trim() === "") {
		searchResults.value = [];
		return;
	}
	searchResults.value = searchData.filter((item) => item.name.toLowerCase().includes(searchQuery.value.toLowerCase()));
};

const logout = () => {
	Inertia.post(
		route("logout"),
		{},
		{
			onSuccess: () => {
				Inertia.reload({ only: ["auth"] });
			},
		},
	);
};

// Observador para verificar cambios en la autenticación
watch(
	() => page.props.isAuthenticated,
	(newUser) => {
		console.log("Auth changed:", newUser ? "Logged In" : "Logged Out");
	},
);
</script>

<template>
	<header>
		<!-- Sección superior del encabezado -->
		<div class="headerTop">
			<!-- Botón (foto perfil) que lleva al perfil o al login -->
			<ul class="header__list">
				<li v-if="!isAuthenticated">
					<Link
						class="header__btn--logIn"
						href="user-profile"
					>
						PROFILE
					</Link>
				</li>
				<li v-if="isAuthenticated">
					<Link
						class="header__btn--logIn"
						href="user-profile"
						id="user-profile_link"
					>
						<img
							:src="user?.profile_photo_path || '/img/pfp_image.png'"
							alt="Profile Picture"
							class="header__profile--img"
						/>
						{{ user?.first_name }} {{ user?.last_name }}
					</Link>
				</li>
			</ul>

			<!-- Menú dependiendo del estado de autenticación -->
			<ul class="header__list">
				<li v-if="!isAuthenticated">
					<Link
						class="header__btn--logIn"
						:href="route('login')"
					>
						LOG IN
					</Link>
				</li>
				<li v-if="!isAuthenticated">
					<Link
						class="header__btn--signUp"
						:href="route('signup')"
					>
						SIGN UP
					</Link>
				</li>
				<li v-if="isAuthenticated">
					<Link
						class="header__btn--signUp"
						href="/"
						@click.prevent="logout"
					>
						LOG OUT
					</Link>
				</li>
			</ul>
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
						<li v-if="!isAuthenticated">
							<Link
								class="header__btn--logIn"
								:href="route('login')"
							>
								LOG IN
							</Link>
						</li>
						<li v-if="!isAuthenticated">
							<Link
								class="header__btn--signUp"
								:href="route('signup')"
							>
								SIGN UP
							</Link>
						</li>
						<li v-if="isAuthenticated">
							<Link
								class="header__btn--logIn"
								href="user-profile"
							>
								PROFILE
							</Link>
						</li>
						<li v-if="isAuthenticated">
							<Link
								class="header__btn--signUp"
								href="/"
								@click.prevent="logout"
							>
								LOG OUT
							</Link>
						</li>
						<li>
							<button class="header__btn"><strong>SHOP</strong></button>
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
						</li>
						<li>
							<button class="header__btn">CART (0)</button>
						</li>
					</ul>
				</div>

				<!-- Lista de elementos del menú principal -->
				<ul class="header__list">
					<li>
						<button class="header__btn"><strong>SHOP</strong></button>
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
									{{ result.name }}
								</li>
							</ul>
						</div>
					</div>

					<!-- Carrito de compra -->
					<button class="header__btn">CART (0)</button>
				</div>
			</nav>
		</div>
	</header>
</template>

<style scoped>
@import "../../../css/shared/shopHeader.css";
</style>
