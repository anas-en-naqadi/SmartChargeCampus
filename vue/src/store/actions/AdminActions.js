import axiosClient from "../../../axios";

const adminActions = {
    async markAsPayed({ commit }, id) {
        try {
            const response = await axiosClient.post(
                "/mark-reservation-as-paid",
                { reservation_id: id }
            );
            if (response && response.status === 200 && response.data) {
                return response.data;
            }
        } catch (error) {
            return error;
        }
    },
    async changePortStatus({ commit }, id) {
        try {
            const response = await axiosClient.post("/mark-as-not-reserved", {
                port_id: id,
            });
            if (response && response.status === 200 && response.data) {
                return response.data;
            }
        } catch (error) {
            return error;
        }
    },
    async destroyChargeStation({ commit }, id) {
        try {
            const response = await axiosClient.delete(`/charge-station/${id}`);
            if (response && response.status === 200 && response.data) {
                return response.data;
            }
        } catch (error) {
            return error;
        }
    },
    async destroyPort({ commit }, id) {
        try {
            const response = await axiosClient.delete(`/port/${id}`);
            if (response && response.status === 200 && response.data) {
                return response.data;
            }
        } catch (error) {
            return error;
        }
    },
    async storeChargeStation({ commit }, charge_station) {
        try {
            const response = await axiosClient.post(
                "charge-station",
                charge_station
            );
            return response;
        } catch (error) {
            return error;
        }
    },

    async updatePort({ commit }, port) {
        try {
            const response = await axiosClient.put(`/port/${port.id}`, port);
            return response;
        } catch (error) {
            return error;
        }
    },
    async editPort({ commit }, id) {
        try {
            const response = await axiosClient.get("/port/" + id + "/edit");
            return response;
        } catch (error) {
            return error;
        }
    },
    async storePort({ commit }, port) {
        try {
            const response = await axiosClient.post("/port", port);
            return response;
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
            const res = await axiosClient.post("/update-user-company", user);
            if (res.status === 200) commit("SET_USER", res.data.user);
            return res;
        } catch (err) {
            return err;
        }
    },
    async updateUserProfile({ commit }, user) {
        try {
            const res = await axiosClient.post("/update-user", user);
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

    async fetchWeeklyGainAmount() {
        try {
            const response = await axiosClient.get("/gain-amount-per-week");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async fetchChargeStationPortCount() {
        try {
            const response = await axiosClient.get(
                "/charge-station-port-count"
            );
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async fetchMonthlyGainAmount() {
        try {
            const response = await axiosClient.get("/gain-amount-per-month");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async fetchChargePerWeek() {
        try {
            const response = await axiosClient.get("/charge-per-week");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async fetchChargePerMonth() {
        try {
            const response = await axiosClient.get("/charge-per-month");
            return response.data;
        } catch (error) {
            return error;
        }
    },

    async fetchStudents({ commit }) {
        try {
            const response = await axiosClient.get("/student");
            commit("SET_STUDENTS", response.data);
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async fetchChargeStations({ commit }) {
        try {
            const response = await axiosClient.get("/charge-station");
            commit("SET_CHARGE_STATIONS", response.data);
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async filterByDates({ commit }, date) {
        try {
            const response = await axiosClient.post(
                "/filterReservationsByDates",
                date
            );
            return response;
        } catch (error) {
            return error;
        }
    },

    async editChargeStation({ commit }, id) {
        try {
            const response = await axiosClient.get(
                "/charge-station/" + id + "/edit"
            );
            return response;
        } catch (error) {
            return error;
        }
    },
    async updateChargeStation({ commit }, charge_station) {
        try {
            const response = await axiosClient.put(
                "/charge-station/" + charge_station.id,
                charge_station
            );
            return response;
        } catch (error) {
            return error;
        }
    },

    async getAdminDashboardData() {
        try {
            const response = await axiosClient.get("/admin-dashboard-data");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async getUserDashboardData() {
        try {
            const response = await axiosClient.get("/user-dashboard-data");
            return response.data;
        } catch (error) {
            return error;
        }
    },
    async fetchPaymentStatus() {
        try {
            const response = await axiosClient.get("/payment-status");
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

    async deleteNotifications() {
        try {
            const response = await axiosClient.get("/delete-notifications");
            return response;
        } catch (error) {
            return error;
        }
    },

    async setReadAt({ commit }, now) {
        return await axiosClient.post("/setReadAt", now);
    },

    async fetchActivities() {
        try {
            const response = await axiosClient.get("/activity");
            return response;
        } catch (error) {
            return error;
        }
    },


};

export default adminActions;
