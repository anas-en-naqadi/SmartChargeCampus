import axiosClient from "../../../axios";

const userActions = {
    async fetchChargeStationLocations({ commit }) {
        try {
            const response = await axiosClient.get("/charge-station");
            return response.data;
        } catch (error) {
            throw error;
        }
    },
    async fetchChargeStationPorts({ commit }, id) {
        try {
            const response = await axiosClient.get("/charge-station/" + id);
            return response.data;
        } catch (error) {
            throw error;
        }
    },
    async storeReservation({ commit }, post_id) {
        try {
            const response = await axiosClient.post("/reserve", post_id);
            return response.data;
        } catch (error) {
            throw error;
        }
    },
    async fetchReservations({ commit }) {
        try {
            const response = await axiosClient.get("/reserve");
            if (response && response.status === 200 && response.data) {
                commit("SET_RESERVATIONS", response.data);
                return response;
            }
        } catch (error) {
            return error;
        }
    },
    async cancelReservation({ commit }, id) {
        try {
            const response = await axiosClient.post("/cancel-reservation", {
                reservation_id: id,
            });

            return response.data;
        } catch (error) {
            return error;
        }
    },
    async updateUserProfile({ commit }, user) {
        try {
            const res = await axiosClient.post("/update-student-profile", user);
            if (res.status === 200) commit("SET_USER", res.data.user);
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
};
export default userActions;
