import { createRouter, createWebHistory } from "vue-router";
import AdminRouter from "./routes/AdminRouter";
import UserRoutes from "./routes/UserRoutes";
import store from "../store";
const routes = [
    {
        path: "/pannel",
        redirect: "/pannel",
        component: () => import("@/components/Layout.vue"),
        meta: {
            requiresAuth: true,
        },
        children: [...AdminRouter, ...UserRoutes],
    },
    {
        path: "/login",
        name: "login",
        meta: {
            title: "Login",
        },
        component: () => import("@/views/auth/Login.vue"),
    },
    {
        path: "/register",
        name: "register",
        meta: {
            title: "Register",
        },
        component: () => import("@/views/auth/Register.vue"),
    },
    {
        path: "/:pathMatch(.*)",
        component: () => import("@/components/NotFound.vue"),
        meta: {
            requiresAuth: false,
            title: "404 Not Found",
        },
    },
    {
        path: "/emailVerification",
        name: "emailVerification",
        component: () => import("@/views/auth/EmailVerification.vue"),
        meta: {
            requiresAuth: false,
            title: "Email Verification",
        },
    },
    {
        path: "/notFound",
        component: () => import("@/components/NotFound.vue"),
        name: "notFound",
        meta: {
            requiresAuth: false,
            title: "404 Not Found",
        },
    },
    {
        path: "/forbidden",
        component: () => import("@/components/Forbidden.vue"),
        name: "forbidden",
        meta: {
            requiresAuth: false,
            title: "403 Forbidden",
        },
    },
    {
        path: "/serverError",
        component: () => import("@/components/ServerError.vue"),
        name: "serverError",
        meta: {
            requiresAuth: false,
            title: "500 Server Error",
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const roleDashboardMap = {
    student: "user-dashboard",
    admin: "admin-dashboard",
};


router.beforeEach((to, from, next) => {
    document.title = `${to.meta.title}`;
    const token = sessionStorage.getItem("TOKEN");
    const userRole = store.state.user.data?.role;

        if (to.meta.requiresAuth && !token) {
        next({ name: "login" });
    } else if (!token && to.meta.isGuest) {
        next({ name: "login" });
    }else if (token && to.name === "login") {
        next({ name: roleDashboardMap[userRole] || "forbidden" }); // Prevent logged-in user from accessing login
    } else {
        next();
    }
});

export default router;
