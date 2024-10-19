import { createStore } from "vuex";
import adminActions from "./actions/AdminActions";
import Expenses from "../views/admin/Expenses.vue";

const store = createStore({
    state: {
        products: {},
        suppliers: {},
        purchases:{},
        expenses:{},
        categories:{},
        clients:{},
        user: {
            data: {},
            token: sessionStorage.getItem("TOKEN"),
        },
    },
    getters: {},
    actions: {
        ...adminActions,
    },
    mutations: {
        SET_PRODUCTS(state, products) {
            // Update the products state with the received data
            state.products = products;
        },
        SET_CLIENTS(state, clients) {
            // Update the clients state with the received data
            state.clients = clients;
        },
        SET_CATEGORIES(state, categories) {
            // Update the categories state with the received data
            state.categories = categories;
        },
        SET_EXPENSES(state, expenses) {
            // Update the expenses state with the received data
            state.expenses = expenses;
        },
        SET_PURCHASES(state, purchases) {
            // Update the purchases state with the received data
            state.purchases = purchases;
        },
        SET_SUPPLIERS(state, suppliers) {
            // Update the products state with the received data
            state.suppliers = suppliers;
        },
        SET_USER(state, user) {
            state.user.data = user;
        },
        DELETE_USER(state) {
            sessionStorage.removeItem("TOKEN");
            state.user.data = {};
            state.user.token = null;
        },
    },
    modules: {},
});

export default store;
