<script>
export default {
  name: "EventsForm",
};
</script>

<script setup>
import { defineProps } from "vue";
import { router } from "@inertiajs/vue3";
import FormSection from "../FormSection.vue";
import InputError from "../InputError.vue";
import InputLabel from "../InputLabel.vue";
import PrimaryButton from "../PrimaryButton.vue";
import TextInput from "../TextInput.vue";

const props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  updating: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const handleSubmit = () => {
  if (props.updating) {
    props.form._method = "PUT";
    router.post(route("events.update", { event: props.form.id }), props.form, {
      preserveScroll: true,
    });
  } else {
    router.post(route("events.store"), props.form, {
      preserveScroll: true,
    });
  }
};
</script>

<template>
  <FormSection class="formSection">
    <template #title>
      {{ updating ? "Update Event" : "Create Event" }}
    </template>

    <template #description>
      {{
        updating
          ? "Update selected event information"
          : "Create a new event in the schedule"
      }}
    </template>

    <template #form>
      <div>
        <InputLabel for="title" value="Title" />
        <TextInput
          id="title"
          v-model="form.title"
          type="text"
          autocomplete="title"
          placeholder="Enter event title"
        />
        <InputError :message="form.errors.title" />
      </div>

      <div>
        <InputLabel for="description" value="Description" />
        <textarea
          id="description"
          v-model="form.description"
          class="input"
          rows="4"
          placeholder="Enter description"
        ></textarea>
        <InputError :message="form.errors.description" />
      </div>

      <div>
        <InputLabel for="start_date" value="Start Date" />
        <input
          id="start_date"
          type="datetime-local"
          v-model="form.start_date"
          class="input"
        />
        <InputError :message="form.errors.start_date" />
      </div>

      <div>
        <InputLabel for="end_date" value="End Date" />
        <input
          id="end_date"
          type="datetime-local"
          v-model="form.end_date"
          class="input"
        />
        <InputError :message="form.errors.end_date" />
      </div>

      <div>
        <InputLabel for="location" value="Location" />
        <TextInput
          id="location"
          v-model="form.location"
          type="text"
          autocomplete="off"
          placeholder="Enter location or room"
        />
        <InputError :message="form.errors.location" />
      </div>

      <div>
        <InputLabel for="color" value="Event Badge Color" />
        <input id="color" type="color" v-model="form.color" class="input color-picker" />
        <InputError :message="form.errors.color" />
      </div>

      <div class="checkbox__container">
        <label for="all_day" class="checkbox__label">
          <input
            id="all_day"
            type="checkbox"
            v-model="form.all_day"
            class="checkbox__input"
          />
          <span>All Day Event</span>
        </label>
        <InputError :message="form.errors.all_day" />
      </div>
    </template>

    <template #actions>
      <PrimaryButton @click.prevent="handleSubmit">
        {{ updating ? "Update" : "Create" }}
      </PrimaryButton>
    </template>
  </FormSection>
</template>

<style scoped>
@import "../../../css/pages/form.css";
</style>
