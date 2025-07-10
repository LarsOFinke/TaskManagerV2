import "../styles/app.css";
import { createApp } from "vue";
import NavbarItem from "@/components/shared/Navbar-Item.vue";
import FooterItem from "@/components/shared/Footer-Item.vue";
import MessageBox from "@/components/shared/Message-Box.vue";
import LoginForm from "@/components/home/Login-Form.vue";
import RegistrationForm from "@/components/home/Registration-Form.vue";
// import TaskList from "@/components/tasks/Task-List.vue";
// import TaskForm from "@/components/tasks/Task-Form.vue";
// import TaskEdit from "@/components/tasks/Task-Item-Edit.vue";

const components = {
    LoginForm,
    RegistrationForm,
    // TaskList,
    // TaskForm,
    // TaskEdit,
    MessageBox,
    NavbarItem,
    FooterItem,
};

window.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-vue-component]").forEach((el) => {
        const raw = el.getAttribute("data-props");
        let props = raw ? JSON.parse(raw) : {};
        const name = el.dataset.vueComponent;
        const Comp = components[name];
        if (Comp) createApp(Comp, props).mount(el);
    });
});
