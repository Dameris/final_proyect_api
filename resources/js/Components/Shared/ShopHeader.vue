<script setup>
import { computed, ref, onMounted } from "vue";
import { usePage, Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { useAuthStore } from "../../stores/auth";
import { storeToRefs } from "pinia";
import axios from "axios";
import { watch } from "vue";

const auth = useAuthStore();
const { user } = storeToRefs(auth);

// Estado para el menú de la tienda y la búsqueda
const isShopOpen = ref(false);
const isSearchOpen = ref(false);
const searchQuery = ref("");
const searchResults = ref([]);
const category = ref("");
const minPrice = ref("");
const maxPrice = ref("");

onMounted(() => {
	auth.fetchUser();
});

// Función para toggle del menú de la tienda
const toggleShopSlide = () => {
	isShopOpen.value = !isShopOpen.value;
};

// Función para toggle de la búsqueda
const toggleSearch = () => {
	isSearchOpen.value = !isSearchOpen.value;
};

// Función para realizar búsqueda
let timeout = null;

watch([searchQuery, category, minPrice, maxPrice], ([query]) => {
	clearTimeout(timeout);

	// si no hay nada escrito y no hay filtros → limpiar resultados
	if (
		query.length < 3 &&
		!category.value &&
		!minPrice.value &&
		!maxPrice.value
	) {
		searchResults.value = [];
		return;
	}

	timeout = setTimeout(async () => {
		try {
			const response = await axios.get("/search", {
				params: {
					query: query,
					category: category.value,
					min_price: minPrice.value,
					max_price: maxPrice.value,
				},
			});

			searchResults.value = response.data;
		} catch (error) {
			console.error("Search error:", error);
		}
	}, 400);
});

// Función para logout
const logout = async () => {
	await auth.logout();
	Inertia.visit("/");
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
						<p v-if="user">{{ user?.first_name }} {{ user?.last_name }}</p>
					</Link>
				</li>
			</ul>

			<ul
				class="header__list"
				v-if="!user"
			>
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
			<ul
				class="header__list"
				v-else
			>
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
						<li v-if="user">
							<Link
								class="header__btn--logIn"
								href="/"
								@click="logout"
							>
								LOG OUT
							</Link>
						</li>
						<li v-if="!user">
							<Link
								class="header__btn--logIn"
								:href="route('login')"
							>
								LOG IN
							</Link>
						</li>
						<li v-if="!user">
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
								<p v-if="user">{{ user?.first_name }} {{ user?.last_name }}</p>
							</Link>
						</li>
						<li>
							<Link
								class="header__btn"
								:href="route('shop.index')"
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
								v-show="isSearchOpen"
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
									/>
									<div class="header__filters">
										<select v-model="category">
											<option value="">All</option>
											<option value="tshirt">Tshirts</option>
											<option value="jogger">Joggers</option>
										</select>
										<input
											type="number"
											placeholder="Min €"
											v-model="minPrice"
										/>
										<input
											type="number"
											placeholder="Max €"
											v-model="maxPrice"
										/>
									</div>
								</div>
								<div
									v-if="Array.isArray(searchResults) && searchResults.length > 0"
									class="header__fullscreenSearch--results"
								>
									<ul>
										<li v-for="result in searchResults" :key="result.id">
											<Link
												:href="
												result.type === 'tshirt'
												? route('tshirts.show', { tshirt: result.id })
												: route('joggers.show', { jogger: result.id })"
												class="header__link"
											>
												<img
													:src="
													result.type === 'tshirt'
													? '/storage/img/tshirts/' + result.image
													: '/storage/img/joggers/' + result.image"
												alt="Product Image"
												/>
												<br />
												{{ result.name }} - {{ result.price }}€
												<br />
												<small>{{ result.type.toUpperCase() }}</small>
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
							:href="route('shop.index')"
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
						v-show="isSearchOpen"
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
							/>
							<div class="header__filters">
								<select v-model="category">
									<option value="">All</option>
									<option value="tshirt">Tshirts</option>
									<option value="jogger">Joggers</option>
								</select>
								<input
									type="number"
									placeholder="Min €"
									v-model="minPrice"
								/>
								<input
									type="number"
									placeholder="Max €"
									v-model="maxPrice"
								/>
							</div>		
						</div>
						<div
							class="header__fullscreenSearch--results"
							v-if="searchResults.length > 0"
						>
							<ul>
								<li v-for="result in searchResults" :key="result.id">
											<Link
												:href="
												result.type === 'tshirt'
												? route('tshirts.show', { tshirt: result.id })
												: route('joggers.show', { jogger: result.id })"
												class="header__link"
											>
												<img
													:src="
													result.type === 'tshirt'
													? '/storage/img/tshirts/' + result.image
													: '/storage/img/joggers/' + result.image"
												alt="Product Image"
												/>
												<br />
												{{ result.name }} - {{ result.price }}€
												<br />
												<small>{{ result.type.toUpperCase() }}</small>
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