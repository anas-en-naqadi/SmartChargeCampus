export default [
    {
        path: "/admin/dashboard",
        name: "admin-dashboard",
        meta: {
            title: "Dashboard - Admin",
        },
        component: () => import("@/views/admin/AdminDashboard.vue"),
    },
    {
        path: "/admin/profile",
        name: "admin-profile",
        meta: {
            title: "Profile - Admin",
        },
        component: () => import("@/views/admin/AdminProfile.vue"),
    },
    {
        path: "/admin/activities",
        name: "activities",
        meta: {
            title: "activities - Admin",
        },
        component: () => import("@/views/admin/ActivityLogs.vue"),
    },

    {
        path: "/admin/charge-station/:portN/ports",
        name: "admin-ports",
        meta: {
            title: "Ports - Admin",
        },
        component: () => import("@/views/user/Ports.vue"),
    },
    {
        path: "/admin/students",
        name: "students",
        meta: {
            title: "Students - Admin",
        },
        component: () => import("@/views/admin/Students.vue"),
    },
    {
        path: "/admin/reservations",
        name: "admin-reservations",
        meta: {
            title: "Reservations - Admin",
        },
        component: () => import("@/views/user/Reservations.vue"),
    },
    {
        path: "/admin/charge-stations",
        name: "charge-stations-admin",
        meta: {
            title: "Charge Stations - Admin",
        },
        component: () => import("@/views/admin/ChargeStations.vue"),
    },
    {
        path: "/admin/activities",
        name: "activities",
        meta: {
            title: "Activities - Admin",
        },
        component: () => import("@/views/admin/ActivityLogs.vue"),
    },
];
