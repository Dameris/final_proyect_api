<script>
export default {
  name: "EventShow",
};
</script>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  event: Object,
});

const deleteEvent = () => {
  if (confirm("Are you sure?")) {
    Inertia.delete(route("events.destroy", props.event.id));
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleString("es-ES", { timeZone: "Europe/Madrid" });
};
</script>

<template>
  <AppLayout>
    <div class="eventShow">
      <div class="eventShow__card">
        <!-- ACTIONS -->
        <div
          class="eventShow__actions"
          v-if="$page.props.user.permissions.includes('updateEvents')"
        >
          <Link :href="route('events.edit', event.id)">Edit</Link>
          <button @click="deleteEvent">Delete</button>
        </div>

        <!-- HEADER -->
        <div class="eventShow__header">
          <div
            class="eventShow__colorIndicator"
            :style="{ background: event.color }"
          ></div>

          <h1 class="eventShow__title">{{ event.title }}</h1>
        </div>

        <!-- INFO -->
        <div class="eventShow__info">
          <div class="eventShow__block">
            <span class="eventShow__label">Description</span>
            <p>{{ event.description || "No description" }}</p>
          </div>

          <div class="eventShow__block">
            <span class="eventShow__label">Start</span>
            <p>{{ formatDate(event.start_date) }}</p>
          </div>

          <div v-if="event.end_date" class="eventShow__block">
            <span class="eventShow__label">End</span>
            <p>{{ formatDate(event.end_date) }}</p>
          </div>

          <div class="eventShow__block">
            <span class="eventShow__label">Location</span>
            <p>{{ event.location || "No location" }}</p>
          </div>

          <div class="eventShow__block">
            <span class="eventShow__label">All day</span>
            <p>{{ event.all_day ? "Yes" : "No" }}</p>
          </div>
        </div>
      </div>

      <div class="eventShow__back">
        <Link :href="route('events.index')">Back</Link>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
@import "../../../css/pages/events/show.css";
</style>
