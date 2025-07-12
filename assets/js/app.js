import "../styles/app.css";
import { createApp } from "vue";
import axios from "axios";
import config from "@/config";
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

window.addEventListener("DOMContentLoaded", async () => {
    // 1) Axios-Instanz bauen
    const api = axios.create({
        baseURL: config.apiBase,
        withCredentials: true,
        headers: { "X-Requested-With": "XMLHttpRequest" },
    });

    // 2) CSRF-Token setzen (falls vorhanden)
    const meta = document.head.querySelector('meta[name="csrf-token"]');
    if (meta) {
        api.defaults.headers.common["X-CSRF-TOKEN"] = meta.content;
        axios.defaults.headers.common["X-CSRF-TOKEN"] = meta.content;
    }

    // 3) Erstelle Vue-App und provide die Instanz
    const app = createApp({}); // wir mounten später die einzelnen Komponenten
    app.provide("api", api);

    // 4) Komponenten mounten
    document.querySelectorAll("[data-vue-component]").forEach((el) => {
        const raw = el.getAttribute("data-props");
        const props = raw ? JSON.parse(raw) : {};
        const Comp = components[el.dataset.vueComponent];
        if (Comp) {
            // für jede Komponente eine eigene App-Instanz, die aber das gleiche provide hat
            createApp(Comp, props).provide("api", api).mount(el);
        }
    });
});
