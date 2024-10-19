import axios from "axios";
import axiosClient from "../../../axios";

const adminActions = {
    async getProducts({ commit }) {
        try {
            const response = await axiosClient.get("/product");
            commit("SET_PRODUCTS", response.data);
            return response.data;
        } catch (error) {
            console.error(error);
            return error;
        }
    },
    async fetchClients({ commit }) {
        try {
            const response = await axiosClient.get("/client");
            if (response && response.status === 200 && response.data) {
                commit("SET_CLIENTS", response.data);
                return response.data;
            }        } catch (error) {
            return error;
        }
    },
    async fetchSuppliers({ commit }) {
        try {
            const response = await axiosClient.get("/supplier");
            if (response && response.status === 200 && response.data) {
                commit("SET_SUPPLIERS", response.data);
                return response.data;
            }
        } catch (error) {
            return error;
        }
    },
    async logout({ commit }) {
        return await axiosClient
            .post("/logout")
            .then((res) => {
                commit("DELETE_USER");
                return res;
            })
            .catch((error) => {
                return error;
            });
    },

    async login({ commit }, user) {
        try {
            const res = await axiosClient.post("/login", user);
            if (res.status === 200)
                sessionStorage.setItem("TOKEN", res.data.token);
            commit("SET_USER", res.data.user);
            return res;
        } catch (err) {
            return err;
        }
    },
    async register({ commit }, form) {
        return await axiosClient
            .post("/register", form)
            .then((res) => {
                commit("SET_USER", res.data.user);
                sessionStorage.setItem("TOKEN", res.data.token);
                return res;
            })
            .catch((error) => {
                return error;
            });
    },
    async updateAdminProfile({ commit }, user) {
        try {
            const res = await axiosClient.post("/update-admin-profile", user);
            if (res.status === 200) commit("SET_USER", res.data.user);
            return res;
        } catch (err) {
            return err;
        }
    },
    async storeUser({ commit }, user) {
        try {
            const res = await axiosClient.post("/store-user", user);

            return res;
        } catch (err) {
            return err;
        }
    },
    async storeSell({ commit }, {sell,client,products}) {
        try {
            const payload = {
                ...sell,
                client,
                products
            };

            const res = await axiosClient.post("/sell", payload);
            return res;
        } catch (err) {
            return err;
        }
    },

    async updatePassword({ commit }, passwordBag) {
        try {
            const res = await axiosClient.post("/update-pass", passwordBag);

            return res;
        } catch (err) {
            return err;
        }
    },

    async getLoggedUser({ commit }) {
        return await axiosClient
            .get("/user")
            .then((res) => {
                commit("SET_USER", res.data); // Assuming user data is in response.data
                return res;
            })
            .catch((error) => error);
    },

    async getWeeklySales() {
        try {
            const response = await axiosClient.get("/weekly-sales");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async getStockStatistics() {
        try {
            const response = await axiosClient.get("/stock-by-category");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async getMonthlySales() {
        try {
            const response = await axiosClient.get("/monthly-sales");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async fetchMonthlySales() {
        try {
            const response = await axiosClient.get("/month-remaining");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async getUserRegistrations() {
        try {
            const response = await axiosClient.get("/user-registrations");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async fetchLastClients() {
        try {
            const response = await axiosClient.get("/latest-clients");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async fetchLastSells() {
        try {
            const response = await axiosClient.get("/latest-sells");
            return response.data;
        } catch (error) {
            return error;
        }
    },

    async getDashboardData() {
        try {
            const response = await axiosClient.get("/dashboard-data");
            return response.data;
        } catch (error) {
            return error;
        }
    },

    async getSells() {
        try {
            const response = await axiosClient.get("/sell");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async getNotifications() {
        try {
            const response = await axiosClient.get("/notifications");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async register({ commit }, form) {
        return await axiosClient
            .post("/register", form)
            .then((res) => {
                commit("SET_USER", res.data.user);
                sessionStorage.setItem("TOKEN", res.data.token);
                return res;
            })
            .catch((error) => {
                return error;
            });
    },
    async deleteNotifications() {
        try {
            const response = await axiosClient.get("/delete-notifications");
            return response;
        } catch (error) {
            return error;
        }
    },

    async getCategories({ commit }) {
        try {
            const response = await axiosClient.get("/category");
            if (response && response.status === 200 && response.data) {
                commit("SET_CATEGORIES", response.data);
                return response;
            }
        } catch (error) {
            return error;
        }
    },

    async storeProduct({ commit }, product) {
        try {
            const response = await axiosClient.post("/product", product);
            return response;
        } catch (error) {
            return error;
        }
    },

    async destroyProduct({ commit }, product_id) {
        try {
            const response = await axiosClient.delete(`/product/${product_id}`);
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async destroyPurchase({ commit }, purchase_id) {
        try {
            const response = await axiosClient.delete(
                `/purchase/${purchase_id}`
            );
            return response.data;
        } catch (error) {
            return error;
        }
    },

    async destroyCategory({ commit }, category_id) {
        try {
            const response = await axiosClient.delete(
                `/category/${category_id}`
            );
            return response.data;
        } catch (error) {
            return error;
        }
    },

    async setReadAt({ commit }, now) {
        return await axiosClient.post("/setReadAt", now);
    },

    async getProduct({ commit }, product_id) {
        try {
            const response = await axiosClient.get(`/product/${product_id}`);
            return response;
        } catch (error) {
            return error;
        }
    },
    async updateProduct({ commit }, { product, product_id }) {
        try {
            const response = await axiosClient.put(
                `/product/${product_id}`,
                product
            );
            return response;
        } catch (error) {
            return error;
        }
    },
    async updatePurchase({ commit }, { purchase, purchase_id }) {
        try {
            const response = await axiosClient.put(
                `/purchase/${purchase_id}`,
                purchase
            );
            return response;
        } catch (error) {
            return error;
        }
    },
    async newDebt({ commit }, data) {
        try {
            const response = await axiosClient.post("new-debt", data);
            return response;
        } catch (error) {
            return error;
        }
    },

    async storeCategory({ commit }, category_name) {
        try {
            const response = await axiosClient.post(`/category`, {
                category_name: category_name,
            });
            return response;
        } catch (error) {
            return error;
        }
    },
    async productDetails({ commit }, product_id) {
        try {
            const response = await axiosClient.get(`/product/${product_id}`);
            return response;
        } catch (error) {
            return error;
        }
    },
    async fetchExpenses({ commit }) {
        try {
            const response = await axiosClient.get("/expense");
            if (response && response.status === 200 && response.data) {
                commit("SET_EXPENSES", response.data);
                return response;
            }
        } catch (error) {
            return error;
        }
    },
    async fetchActivities() {
        try {
            const response = await axiosClient.get("/activity");
            return response;
        } catch (error) {
            return error;
        }
    },
    async destroyExpense({ commit }, id) {
        try {
            const response = await axiosClient.delete(`/expense/${id}`);
            return response;
        } catch (error) {
            return error;
        }
    },
    async filterByDates({ commit }, date) {
        try {
            const response = await axiosClient.post(
                "/filterSellsByDates",
                date
            );
            return response;
        } catch (error) {
            return error;
        }
    },
    async storeExpense({ commit }, expense) {
        try {
            const response = await axiosClient.post("/expense", expense);
            return response;
        } catch (error) {
            return error;
        }
    },
    async fetchPurchases({ commit }) {
        try {
            const response = await axiosClient.get("/purchase");
            if (response && response.status === 200 && response.data) {
                commit("SET_PURCHASES", response.data);
                return response;
            }
        } catch (error) {
            return error;
        }
    },
    async storeSupplier({ commit }, supplier) {
        try {
            const response = await axiosClient.post("/supplier", supplier);
            return response;
        } catch (error) {
            return error;
        }
    },
    async updateSupplier({ commit }, { supplier, supplier_id }) {
        try {
            const response = await axiosClient.put(
                `/supplier/${supplier_id}`,
                supplier
            );
            return response;
        } catch (error) {
            return error;
        }
    },
    async editSupplier({ commit }, id) {
        try {
            const response = await axiosClient.get(`/supplier/${id}`);
            return response;
        } catch (error) {
            return error;
        }
    },
    async editPurchase({ commit }, id) {
        try {
            const response = await axiosClient.get(`/purchase/${id}`);
            return response;
        } catch (error) {
            return error;
        }
    },
    async destroySupplier({ commit }, id) {
        try {
            const response = await axiosClient.delete(`/supplier/${id}`);
            return response;
        } catch (error) {
            return error;
        }
    },

    async storeNewPurchase({ commit }, purchase) {
        try {
            const response = await axiosClient.post("/purchase", purchase);
            return response;
        } catch (error) {
            return error;
        }
    },
    async storeExistingPurchase({ commit }, purchase) {
        try {
            const response = await axiosClient.post(
                "/existing-purchase",
                purchase
            );
            return response;
        } catch (error) {
            return error;
        }
    },
};

export default adminActions;
