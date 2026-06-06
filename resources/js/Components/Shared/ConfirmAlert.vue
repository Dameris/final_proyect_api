<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import mitt from 'mitt';

window.emitter = window.emitter || mitt();

const visible = ref(false);
const message = ref('');
const callbackOnConfirm = ref(null);

onMounted(() => {
    // Escucha cuando un componente solicita una confirmación
    window.emitter.on('trigger-confirm', ({ message: msg, onConfirm }) => {
        message.value = msg;
        callbackOnConfirm.value = onConfirm;
        visible.value = true;
    });
});

onUnmounted(() => {
    window.emitter.off('trigger-confirm');
});

const handleCancel = () => {
    visible.value = false;
    callbackOnConfirm.value = null;
};

const handleConfirm = () => {
    if (callbackOnConfirm.value) {
        callbackOnConfirm.value();
    }
    visible.value = false;
};
</script>

<template>
    <Transition name="confirm-fade">
        <div v-if="visible" class="confirm-overlay" @click.self="handleCancel">
            <div class="confirm-popup">
                <div class="confirm-popup__icon">⚠️</div>
                <p class="confirm-popup__message">{{ message }}</p>
                <div class="confirm-popup__actions">
                    <button class="btn-cancel" @click="handleCancel">Cancel</button>
                    <button class="btn-confirm" @click="handleConfirm">Yes, Empty</button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
@import "../../../css/shared/confirmAlert.css";
</style>