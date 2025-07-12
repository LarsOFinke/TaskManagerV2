import "../styles/app.css";
import { createApp } from "vue";
import axios from "axios";
import environment from "@/environments/environment";
import NavbarItem from "@/components/shared/Navbar-Item.vue";
import FooterItem from "@/components/shared/Footer-Item.vue";
import MessageBox from "@/components/shared/Message-Box.vue";
import LoginForm from "@/components/home/Login-Form.vue";
import RegistrationForm from "@/components/home/Registration-Form.vue";
import TaskList from "@/components/tasks/Task-List.vue";
import TaskForm from "@/components/tasks/Task-Form.vue";

const components = {
    LoginForm,
    RegistrationForm,
    TaskList,
    TaskForm,
    // TaskEdit,
    MessageBox,
    NavbarItem,
    FooterItem,
};

window.addEventListener("DOMContentLoaded", () => {
    // 1) Pull the token out of the meta
    const meta = document.head.querySelector('meta[name="csrf-token"]');
    if (!meta) {
        console.error("❌ CSRF meta tag not found!");
    } else {
        const token = meta.getAttribute("content");
        console.log("✅ CSRF token set!");

        // 2) Set it on your API instance
        environment.api.defaults.headers.common["X-CSRF-TOKEN"] = token;

        // 3) (Optional) also set it globally if you ever use axios directly
        axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
    }

    // 4) Now mount Vue components — any api calls from here will carry the header
    document.querySelectorAll("[data-vue-component]").forEach((el) => {
        const raw = el.getAttribute("data-props");
        const props = raw ? JSON.parse(raw) : {};
        const Comp = components[el.dataset.vueComponent];
        if (Comp) createApp(Comp, props).mount(el);
    });
});
