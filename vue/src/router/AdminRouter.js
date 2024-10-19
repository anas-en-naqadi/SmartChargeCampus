export default [
    {
        path: "/pannel",
        redirect: "/pannel",
        component: () => import("@/components/Layout.vue"),
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "/admin/dashboard",
                name: "dashboard",
                meta: {
                    title: "Dashboard - Admin",
                },
                component: () => import("@/views/admin/Dashboard.vue"),
            },
            {
                path: "/admin/clients",
                name: "clients",
                meta: {
                    title: "Clients - Admin",
                },
                component: () => import("@/views/admin/Clients.vue"),
            },
            {
                path: "/admin/suppliers",
                name: "suppliers",
                meta: {
                    title: "Suppliers - Admin",
                },
                component: () => import("@/views/admin/Suppliers.vue"),
            },
            {
                path: "/admin/products",
                name: "products",
                meta: {
                    title: "Products - Admin",
                },
                component: () => import("@/views/admin/Products.vue"),
            },


            {
                path: "/admin/menu",
                name: "new-sale",
                meta: {
                    title: "Menu - Admin",
                },
                component: () => import("@/views/admin/NewSale.vue"),
            },
            {
                path: "/admin/sells",
                name: "sells",
                meta: {
                    title: "Sells - Admin",
                },
                component: () => import("@/views/admin/Sells.vue"),
            },
            {
                path: "/admin/all-products",
                name: "admin-all-products",
                meta: {
                    title: "Products - Admin",
                },
                component: () => import("@/views/admin/AllProducts.vue"),
            },

         

            {
                path: "/admin/categories",
                name: "categories",
                meta: {
                    title: "Categories - Admin",
                },
                component: () => import("@/views/admin/Categories.vue"),
            },
            {
                path: "/admin/purchases",
                name: "purchases",
                meta: {
                    title: "Purchases - Admin",
                },
                component: () => import("@/views/admin/Purchases.vue"),
            },
            {
                path: "/admin/expenses",
                name: "expenses",
                meta: {
                    title: "Expenses - Admin",
                },
                component: () => import("@/views/admin/Expenses.vue"),
            },
            {
                path: "/admin/activities",
                name: "activities",
                meta: {
                    title: "Activities - Admin",
                },
                component: () => import("@/views/admin/ActivityLogs.vue"),
            },
        ],
    },
];
