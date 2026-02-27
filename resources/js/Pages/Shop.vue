<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <AppLayout>
        <div class="shop__container">
            <h1>Choose a category</h1>

            <div class="shop__options">
                <Link :href="route('tshirts.index')" class="shop__card">
                    👕 T-Shirts
                </Link>

                <Link :href="route('joggers.index')" class="shop__card">
                    👖 Joggers
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@import "../../css/pages/shop.css";
</style>