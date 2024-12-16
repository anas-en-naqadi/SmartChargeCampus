import { createStore } from "vuex";
import adminActions from "./actions/AdminActions";
import userActions from "./actions/UserActions";
const store = createStore({
    state: {
        reservations: [],
        students: [],
        charge_stations: [],

        user: {
            data: {},
            token: sessionStorage.getItem("TOKEN"),
        },
    },
    getters: {},
    actions: {
        ...adminActions,
        ...userActions,
    },
    mutations: {
        SET_RESERVATIONS(state, reservations) {
            // Update the reservations state with the received data
            state.reservations = reservations;
        },
        SET_STUDENTS(state, students) {
            // Update the students state with the received data
            state.students = students;
        },
        SET_CHARGE_STATIONS(state, charge_stations) {
            // Update the charge_stations state with the received data
            state.charge_stations = charge_stations;
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
