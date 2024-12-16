import axios from "axios";
import store from "./src/store";

const axiosClient = axios.create({
    baseURL: `${import.meta.env.VITE_BASE_BACKEND_URL}/api`,
});

// Create a function to set up interceptors
export const setupAxiosInterceptors = (router) => {
    axiosClient.interceptors.request.use((config) => {
        config.headers.Authorization = `Bearer ${store.state.user.token}`;
        return config;
    });

    axiosClient.interceptors.response.use(
        (response) => response,
        (error) => {
            if (error.response && error.response.status === 401) {
                store.commit("DELETE_USER");
                router.push({ name: "login" });
            }

            if (error.response && error.response.status === 404) {
                router.push({ name: "notFound" }); // Redirect to notFound page
            }
            if (error.response && error.response.status === 500) {
                router.push({ name: "serverError" }); // Redirect to notFound page
            }
             if (error.response && error.response.status === 403) {
                 router.push({ name: "forbidden" }); // Redirect to notFound page
             }


            return Promise.reject(error);
        }
    );
};

export default axiosClient;




