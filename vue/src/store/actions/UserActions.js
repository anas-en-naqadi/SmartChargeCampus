import axiosClient from "../../../axios";
const userActions = {
    async productDetails({ commit }, product_id) {
        try {
            const response = await axiosClient.get(`/product/${product_id}`);
            return response;
        } catch (error) {
            return error;
        }
    },
    async fetchExpense() {
        try {
            const response = await axiosClient.get("/expense");
            return response;
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
    async storeExpense({ commit }, expense) {
        try {
            const response = await axiosClient.post("/expense", expense);
            return response;
        } catch (error) {
            return error;
        }
    },
};

export default userActions;
