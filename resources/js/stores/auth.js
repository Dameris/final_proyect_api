// stores/auth.js
import { defineStore } from "pinia";
import { usePage } from "@inertiajs/inertia-vue3";

export const useAuthStore = defineStore("auth", {
	state: () => ({
		user: null,
	}),
	actions: {
		init() {
			const page = usePage();
			const props = page?.props?.value;

			if (props && props.auth && props.auth.user) {
				this.user = props.auth.user;
			} else {
				this.user = null;
			}
		},
		logout() {
			this.user = null;
		},
	},
});
