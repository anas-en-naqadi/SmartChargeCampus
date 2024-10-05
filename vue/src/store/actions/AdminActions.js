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
                return response.data;
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
                const res = await axiosClient.post(
                    "/update-admin-profile",
                    user
                );
                if (res.status === 200) commit("SET_USER", res.data.user);
                return res;
            } catch (err) {
                return err;
            }
        },
         async storeUser({ commit }, user) {
            try {
                const res = await axiosClient.post(
                    "/store-user",
                    user
                );

                return res;
            } catch (err) {
                return err;
            }
        },
        async changeUserStatus({ commit }, {user_id,status}) {
            try {

                const response = await axiosClient.post("/changeStatus-user",{user_id,status});
                return response;
            } catch (error) {
                return error;
            }
        },
        async filterByDates({ commit }, {date,type}) {
            try {
                var url = type === "invoice" ? "/filterInvoicesByDates" : (type==="order" ? "/filterOrdersByDates" :"/filterSellsByDates");
                const response = await axiosClient.post(url,date);
                return response;
            } catch (error) {
                return error;
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
                const response = await axiosClient.get("/weeklySales");
                return response.data;
            } catch (error) {
                return error;
            }
        },
      async  getStockStatistics(){
        try {
            const response = await axiosClient.get("/stock-by-category");
            return response.data;
        } catch (error) {
            return error;
        }
    },
        async getMonthlySales() {
            try {
                const response = await axiosClient.get("/monthlySales");
                return response.data;
            } catch (error) {
                return error;
            }
        },async getThisMonthSales() {
            try {
                const response = await axiosClient.get("/this-month");
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
        async getlastUsers() {
            try {
                const response = await axiosClient.get("/last-users");
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
        async getOrdersStatus() {
            try {
                const response = await axiosClient.get("/status-orders");
                return response.data;
            } catch (error) {
                return error;
            }
        },
     
        async getSells() {
            try {
                const response = await axiosClient.get("/sells");
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
        async getInvoices() {
            try {
                const response = await axiosClient.get("/invoice");
                return response.data;
            } catch (error) {
                return error;
            }
        },
        async getCategories() {
            try {
                const response = await axiosClient.get("/category");
                return response.data;
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


        async storeInvoice({ commit }, invoice) {
            try {
                const response = await axiosClient.post("/invoice", invoice);
                return response;
            } catch (error) {
                return error;
            }
        },
        async destroyProduct({ commit }, product_id) {
            try {
                const response = await axiosClient.delete(
                    `/product/${product_id}`
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

       async setAsAdmin({ commit }, user_id) {
            return await axiosClient.post("/toAdmin", user_id).then((res) => {
                return res;
            });
        },
        async getProduct({ commit }, product_id) {
            try {
                const response = await axiosClient.get(
                    `/product/${product_id}`
                );
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
        async getOrderById({ commit }, id) {
            try {
                const response = await axiosClient.get(`/order/${id}`);
                return response;
            } catch (error) {
                return error;
            }
        },
        async getInvoiceById({ commit }, id) {
            try {
                const response = await axiosClient.get(`/invoice/${id}`);
                return response;
            } catch (error) {
                return error;
            }
        },


        async updateInvoice({ commit }, { invoice, id }) {
            try {
                const response = await axiosClient.put(
                    `/invoice/` + id,
                    invoice
                );
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
        }
}

export default adminActions;
