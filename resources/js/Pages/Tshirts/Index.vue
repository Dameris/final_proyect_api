<script>
export default {
	name: "TshirtIndex",
};
</script>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";

defineProps({
	tshirts: {
		type: Object,
		required: true,
	},
});

const deleteTshirt = (id) => {
	if (confirm("Are you sure?")) {
		Inertia.delete(route("tshirts.destroy", id));
	}
};
</script>

<template>
	<AppLayout>
		<template #header>
			<h1>Tshirts</h1>
		</template>

		<div>
			<div>
				<div>
					<div v-if="$page.props.user.permissions.includes('createtshirts')">
						<Link :href="route('tshirts.create')"> CREATE TSHIRT </Link>
					</div>
				</div>
			</div>
			<div>
				<ul>
					<li v-for="tshirt in tshirts.data">
						<p>{{ tshirt.tshirt_name }}</p>
						<p>
							<Link
								:href="route('tshirts.edit', tshirt.id)"
								v-if="$page.props.user.permissions.includes('updatetshirts')"
							>
								Edit
							</Link>
							<Link
								@click="deleteTshirt(tshirt.id)"
								v-if="$page.props.user.permissions.includes('deletetshirts')"
							>
								Delete
							</Link>
						</p>
					</li>
				</ul>
			</div>
			<div>
				<Link
					v-if="tshirts.current_page > 1"
					:href="tshirts.prev_page_url"
				>
					PREV
				</Link>
				<Link
					v-if="tshirts.current_page < tshirts.last_page"
					:href="tshirts.next_page_url"
				>
					NEXT
				</Link>
			</div>
		</div>
	</AppLayout>
</template>
