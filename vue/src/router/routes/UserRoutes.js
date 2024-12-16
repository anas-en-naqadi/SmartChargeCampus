export default [
    {
        path: "/charge-station-locations",
        name: "charge-stations",
        meta: {
            title: "Charge Station Location",
        },
        component: () => import("@/views/user/ChargeStationLocations.vue"),
    },
    {
        path: "/ports/:portN",
        name: "ports",
        meta: {
            title: "Ports",
        },
        component: () => import("@/views/user/Ports.vue"),
        props: true, // This allows the route parameter to be passed as a prop to the component
    },
    {
        path: "/reservations",
        name: "reservations",
        meta: {
            title: "Reservations",
        },
        component: () => import("@/views/user/Reservations.vue"),
        props: true, // This allows the route parameter to be passed as a prop to the component
    },
    {
        path: "/profile",
        name: "profile",
        meta: {
            title: "Profile",
        },
        component: () => import("@/views/user/Profile.vue"),
        props: true, // This allows the route parameter to be passed as a prop to the component
    },
    {
        path: "/dashboard",
        name: "user-dashboard",
        meta: {
            title: "Dashboard",
        },
        component: () => import("@/views/user/UserDashboard.vue"),
    },
];
