<script>
export default {
	name: "EventsCreate",
};
</script>

<script setup>
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import EventsForm from "@/Components/Events/Form.vue";

const form = useForm({
	title: "",
	description: "",
	start_date: "",
	end_date: "",
	location: "",
	color: "#3788d8",
	all_day: false,
});
</script>

<template>
	<AppLayout title="Create Event">
		<div>
			<h1>Create Event</h1>

			<EventsForm :form="form" />
		</div>
	</AppLayout>
</template>