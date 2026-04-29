<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
  events: {
    type: Array,
    required: true,
  },
});

const handleEventClick = (info) => {
  Inertia.visit(route("events.show", info.event.id));
};

const calendarOptions = {
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: "dayGridMonth",
  firstDay: 1,
  events: props.events,

  eventContent: (arg) => {
    const bgColor = arg.event.backgroundColor || "#3788d8";

    // Función para detectar si el color es claro u oscuro
    const isLight = (color) => {
      const c = color.substring(1);
      const rgb = parseInt(c, 16);
      const r = (rgb >> 16) & 0xff;
      const g = (rgb >> 8) & 0xff;
      const b = (rgb >> 0) & 0xff;

      // Fórmula luminancia
      const brightness = (r * 299 + g * 587 + b * 114) / 1000;
      return brightness > 155;
    };

    const textColor = isLight(bgColor) ? "#000" : "#fff";
    const title = arg.event.title;
    const location = arg.event.extendedProps.location;

    return {
      html: `
      <div style="
        background:${bgColor};
        color:${textColor};
      ">
        <strong>${title}</strong>
        <br>
        <small>📍${location}</small>
      </div>
    `,
    };
  },

  eventClick: handleEventClick,
};
</script>

<template>
  <AppLayout>
    <div class="events">
      <h1 class="events__title">EVENT CALENDAR</h1>

      <div
        v-if="$page.props.user.permissions.includes('createEvents')"
        class="events__create"
      >
        <Link :href="route('events.create')">CREATE EVENT</Link>
      </div>

      <div class="events__calendar">
        <FullCalendar :options="calendarOptions" />
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
@import "../../../css/pages/events/index.css";
</style>
