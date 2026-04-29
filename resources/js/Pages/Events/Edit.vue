<script>
export default {
  name: "EventsEdit",
};
</script>

<script setup>
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import EventsForm from "@/Components/Events/Form.vue";

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
});

const form = useForm({
  id: props.event.id,
  title: props.event.title,
  description: props.event.description,
  start_date: props.event.start_date,
  end_date: props.event.end_date,
  location: props.event.location,
  color: props.event.color,
  all_day: props.event.all_day,
});

const submit = () => {
  form.put(route("events.update", form.id), {
    preserveScroll: true,
  });
};
</script>

<template>
  <AppLayout title="Edit Event">
    <div>
      <h1>Edit Event</h1>

      <EventsForm :updating="true" :form="form" @submit="submit" />
    </div>
  </AppLayout>
</template>
