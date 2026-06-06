<script setup>
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
</script>

<template>
    <NotificationAlert /> 
    <ConfirmPopup />

    <ShopHeader />

    <main>
        <slot />
    </main>

    <ShopFooter />
</template>
