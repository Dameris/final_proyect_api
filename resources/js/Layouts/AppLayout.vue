<script setup>
import { onMounted } from "vue";
import ShopHeader from "@/Components/Shared/ShopHeader.vue";
import ShopFooter from "@/Components/Shared/ShopFooter.vue";
import NotificationAlert from "@/Components/Shared/NotificationAlert.vue";
import ConfirmPopup from "@/Components/Shared/ConfirmAlert.vue";
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();

defineProps({
    title: {
        type: String,
        required: false,
        default: 'SkyUrban'
    }
});

// Aasegurar que el HTML tenga el atributo correcto
onMounted(() => {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        document.documentElement.setAttribute("data-theme", "dark");
    } else {
        document.documentElement.removeAttribute("data-theme");
    }
});
</script>

<template>
    <NotificationAlert /> 
    <ConfirmPopup />

    <ShopHeader />

    <!-- Clase envolvente para controlar transiciones suaves en toda la app -->
    <main class="app-main-content">
        <slot />
    </main>

    <ShopFooter />
</template>

<style scoped>
.app-main-content {
    min-height: 80vh;
    transition: background-color 0.3s ease, color 0.3s ease;
}
</style>