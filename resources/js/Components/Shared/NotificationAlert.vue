<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import mitt from 'mitt';

window.emitter = window.emitter || mitt();

const page = usePage();
const visible = ref(false);
const message = ref('');
const type = ref('success');

const showAlert = (customMessage, customType = 'success') => {
    message.value = customMessage;
    type.value = customType;
    visible.value = true;
    setTimeout(() => { visible.value = false; }, 4000);
};

onMounted(() => {
    window.emitter.on('trigger-alert', ({ message, type }) => {
        showAlert(message, type);
    });
});

onUnmounted(() => {
    window.emitter.off('trigger-alert');
});

watch(() => page.props.flash?.alert, (newVal) => {
    if (newVal) showAlert(newVal, page.props.flash?.alertType || 'success');
}, { immediate: true });
</script>

<template>
    <Transition name="alert">
        <div v-if="visible" class="alert__notification" :class="type">
            <div class="alert__icon">
                <span v-if="type === 'success'">✓</span>
                <span v-else>⚠️</span>
            </div>
            <div class="alert__content">
                {{ message }}
            </div>
            <button @click="visible = false" class="alert__close">×</button>
        </div>
    </Transition>
</template>

<style scoped>
@import "../../../css/shared/notificationAlert.css";
</style>