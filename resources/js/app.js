import "./bootstrap";
import "../css/main.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

console.log("Initial page data:", window.__inertia_page_data);

createInertiaApp({
	title: (title) => `${title} - ${appName}`,
	resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue")),
	setup({ el, App, props, plugin }) {
		console.log("Inertia setup props:", props);
		return createApp({ render: () => h(App, props) })
			.use(plugin)
			.use(ZiggyVue)
			.mount(el);
	},
	progress: {
		color: "#4B5563",
	},
});
