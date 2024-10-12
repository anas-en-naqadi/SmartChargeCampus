import { createStore } from "vuex";
import adminActions from "./actions/AdminActions";

const store = createStore({
    state: {
        products: {},
        suppliers: {},
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
        SET_SUPPLIER(state, suppliers) {
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
