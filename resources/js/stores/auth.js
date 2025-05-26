
import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
	state: () => ({
		user: null,
		isAuthenticated: false,
		loading: true,
	}),

	actions: {
		async fetchUser() {
			this.loading = true;
			try {
				const response = await axios.get("/auth/session");
				this.user = response.data.user;
				this.isAuthenticated = response.data.isAuthenticated;
			} catch (error) {
				console.error("Error fetching session:", error);
				this.user = null;
				this.isAuthenticated = false;
			} finally {
				this.loading = false;
			}
		},

		async logout() {
			try {
				await axios.post("/logout");
				this.user = null;
				this.isAuthenticated = false;
			} catch (error) {
				console.error("Logout failed:", error);
			}
		},
	},
});
