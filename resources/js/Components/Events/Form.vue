<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  form: Object,
  updating: Boolean,
});

const emit = defineEmits(["submit"]);

const submit = () => {
  if (props.updating) {
    emit("submit");
  } else {
    props.form.post(route("events.store"));
  }
};
</script>

<template>
  <form @submit.prevent="submit">
    <input v-model="form.title" placeholder="Title" />
    <textarea v-model="form.description" placeholder="Description"></textarea>
    <input type="datetime-local" v-model="form.start_date" />
    <input type="datetime-local" v-model="form.end_date" />
    <input v-model="form.location" placeholder="Location" />
    <input type="color" v-model="form.color" />
    <label>
      <input type="checkbox" v-model="form.all_day" />
      All Day
    </label>
    <button type="submit">
      {{ updating ? "Update" : "Create" }}
    </button>
  </form>
</template>
