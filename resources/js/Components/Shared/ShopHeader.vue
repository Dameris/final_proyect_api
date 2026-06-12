<script setup>
import { computed, ref, onMounted, watch } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3"; 
import { useAuthStore } from "../../stores/auth";
import { storeToRefs } from "pinia";
import axios from "axios";

const auth = useAuthStore();
const { user } = storeToRefs(auth);

const page = usePage();
const cartCount = computed(() => {
    const props = page.props;
    return props && props.cartTotalQuantity !== undefined ? props.cartTotalQuantity : 0;
});

// Estado y lógica para modo oscuro
const isDarkMode = ref(false);

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    if (isDarkMode.value) {
        document.documentElement.setAttribute("data-theme", "dark");
        localStorage.setItem("theme", "dark");
    } else {
        document.documentElement.removeAttribute("data-theme");
        localStorage.setItem("theme", "light");
    }
};

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
    
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        isDarkMode.value = true;
        document.documentElement.setAttribute("data-theme", "dark");
    }
});

// Función para toggle del menú de la tienda
const toggleShopSlide = () => {
    isShopOpen.value = !isShopOpen.value;
};

// Función para toggle de la búsqueda
const toggleSearch = () => {
    isSearchOpen.value = !isSearchOpen.value;
};

// --- NUEVA FUNCIÓN: Control de precios negativos (Igual que en el Carrito) ---
const fixPrices = () => {
    if (minPrice.value !== "" && minPrice.value < 0) {
        window.emitter.emit('trigger-alert', { 
            message: "The minimum price cannot be negative.", 
            type: "error" 
        });
        minPrice.value = ""; // Resetea el campo si es negativo
    }
    
    if (maxPrice.value !== "" && maxPrice.value < 0) {
        window.emitter.emit('trigger-alert', { 
            message: "The maximum price cannot be negative.", 
            type: "error" 
        });
        maxPrice.value = ""; // Resetea el campo si es negativo
    }

    // Opcional: Controlar que el mínimo no sea mayor que el máximo si ambos existen
    if (minPrice.value !== "" && maxPrice.value !== "" && minPrice.value > maxPrice.value) {
        window.emitter.emit('trigger-alert', { 
            message: "Minimum price cannot be greater than maximum price.", 
            type: "error" 
        });
        minPrice.value = "";
    }
};

// Función para realizar búsqueda
let timeout = null;

watch([searchQuery, category, minPrice, maxPrice], ([query]) => {
    clearTimeout(timeout);

    // Evitamos búsquedas vacías
    if (
        (!query || query.length < 3) &&
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

const clearFilters = () => {
    category.value = "";
    minPrice.value = "";
    maxPrice.value = "";
    searchQuery.value = "";
    searchResults.value = [];
};

const logout = async () => {
    await auth.logout();
    router.visit("/");
};
</script>

<template>
    <header>
        <div class="headerTop">
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

            <ul class="header__list" v-if="!user">
                <li><Link class="header__btn--logIn" :href="route('login')">LOG IN</Link></li>
                <li><Link class="header__btn--signUp" :href="route('signup')">SIGN UP</Link></li>
            </ul>
            <ul class="header__list" v-else>
                <li><Link class="header__btn--logIn" href="/" @click="logout">LOG OUT</Link></li>
            </ul>
        </div>

        <div>
            <nav class="headerBottom">
                <button class="btn__open--slideMenu" @click="toggleShopSlide">OPEN</button>

                <div class="header__slideMenu" :class="{ visible: isShopOpen }">
                    <button class="btn__close--slideMenu" @click="toggleShopSlide">CLOSE</button>
                    <ul class="header__list--slide">
                        <li>
                            <button @click="toggleDarkMode" class="header__darkmode-btn">
                                {{ isDarkMode ? '☀️' : '🌙' }}
                            </button>
                        </li>
                        <li v-if="user">
                            <Link class="header__btn--logIn" href="/" @click="logout">LOG OUT</Link>
                        </li>
                        <li v-if="!user">
                            <Link class="header__btn--logIn" :href="route('login')">LOG IN</Link>
                        </li>
                        <li v-if="!user">
                            <Link class="header__btn--signUp" :href="route('signup')">SIGN UP</Link>
                        </li>
                        <li>
                            <Link class="header__btn--logIn" :href="route('profile')" id="user-profile_link">
                                <img :src="user?.profile_photo_path || '/img/pfp_image.png'" alt="Profile" class="header__profile--img" />
                                <p v-if="user">{{ user?.first_name }} {{ user?.last_name }}</p>
                            </Link>
                        </li>
                        <li><Link class="header__btn" :href="route('shop.index')"><strong>SHOP</strong></Link></li>
                        <li><Link class="header__link" :href="route('about')">ABOUT US</Link></li>
                        <li>
                            <button class="header__search--btn" @click="toggleSearch">SEARCH</button>

                            <div v-show="isSearchOpen" class="header__fullscreenSearch">
                                <div class="header__fullscreenSearch--bar">
                                    <button class="header__fullscreen--closeSearch" @click="toggleSearch">✖</button>
                                    <input type="text" placeholder="Type to search..." v-model="searchQuery" />
                                    <button @click="clearFilters" class="clear__filters">Clear filters</button>
                                    
                                    <div class="header__filters">
                                        <select v-model="category">
                                            <option value="">All</option>
                                            <option value="tshirt">Tshirts</option>
                                            <option value="jogger">Joggers</option>
                                        </select>
                                        <input
                                            type="number"
                                            min="0"
                                            placeholder="Min €"
                                            v-model.number="minPrice"
                                            @input="fixPrices"
                                        />
                                        <input
                                            type="number"
                                            min="0"
                                            placeholder="Max €"
                                            v-model.number="maxPrice"
                                            @input="fixPrices"
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <ul class="header__list">
                    <li><Link class="header__btn" :href="route('shop.index')"><strong>SHOP</strong></Link></li>
                    <li><Link class="header__link" :href="route('about')">ABOUT US</Link></li>
                </ul>

                <Link href="/">
                    <img src="../../../../resources/img/logo/SkyUrban-logo-blueS.png" alt="SKYURBAN" class="header__logo" />
                </Link>

                <div class="header__searchContainer header__list">
                    <button class="header__search--btn" @click="toggleSearch">SEARCH</button>

                    <div v-show="isSearchOpen" class="header__fullscreenSearch">
                        <div class="header__fullscreenSearch--bar">
                            <button class="header__fullscreen--closeSearch" @click="toggleSearch">✖</button>
                            <input type="text" placeholder="Type to search..." v-model="searchQuery" />
                            <button @click="clearFilters" class="clear__filters">Clear filters</button>
                            
                            <div class="header__filters">
                                <select v-model="category">
                                    <option value="">All</option>
                                    <option value="tshirt">Tshirts</option>
                                    <option value="jogger">Joggers</option>
                                </select>
                                <input
                                    type="number"
                                    min="0"
                                    placeholder="Min €"
                                    v-model.number="minPrice"
                                    @input="fixPrices"
                                />
                                <input
                                    type="number"
                                    min="0"
                                    placeholder="Max €"
                                    v-model.number="maxPrice"
                                    @input="fixPrices"
                                />
                            </div>
                        </div>

                        <div class="header__fullscreenSearch--results" v-if="searchResults.length > 0">
                            <ul>
                                <li v-for="result in searchResults" :key="result.id">
                                    <Link
                                        :href="result.type === 'tshirt' ? route('tshirts.show', { tshirt: result.id }) : route('joggers.show', { jogger: result.id })"
                                        class="header__link"
                                    >
                                        <img 
                                            :src="result.type === 'tshirt' ? `/img/tshirts/${result.image || result.img1}` : `/img/joggers/${result.image || result.img1}`"
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

                    <button @click="toggleDarkMode" class="header__darkmode-btn">
                        {{ isDarkMode ? '☀️' : '🌙' }}
                    </button>

                    <Link class="header__cart-badge" :href="route('shoppingCart')">
                        CART <span v-if="cartCount > 0">({{ cartCount }})</span>
                    </Link>
                </div>
            </nav>
        </div>
    </header>
</template>

<style scoped>
@import "../../../css/shared/shopHeader.css";
</style>