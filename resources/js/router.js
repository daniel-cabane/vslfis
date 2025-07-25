import { createRouter, createWebHistory } from "vue-router";
import Home from "./components/views/home-view.vue";
import Manage from "./components/views/manage-view.vue";
import Admin from "./components/views/admin-view.vue";
import History from "./components/views/history-view.vue";
// import Identification from "./components/views/identification-view.vue";
import Unfinalized from "./components/views/unfinalized-view.vue";
import Exits from "./components/views/exits-view.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/manage",
        name: "Manage",
        component: Manage,
    },
    {
        path: "/admin",
        name: "Admin",
        component: Admin,
    },
    {
        path: "/history",
        name: "History",
        component: History,
    },
    // {
    //     path: "/identification",
    //     name: "Identification",
    //     component: Identification,
    // },
    {
        path: "/unfinalized",
        name: "Unfinalized",
        component: Unfinalized,
    },
    {
        path: "/exits",
        name: "Exits",
        component: Exits,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;