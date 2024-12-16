import { createApp } from "vue";
import "./index.css";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import PrimeVue from "primevue/config";
import VueSweetalert2 from "vue-sweetalert2";
import Skeleton from "primevue/skeleton";
import InputText from "primevue/inputtext";
import FileUpload from "primevue/fileupload";
import Steps from "primevue/steps";
import ToastService from "primevue/toastservice";
import Galleria from "primevue/galleria";
import Textarea from "primevue/textarea";

import Button from "primevue/button";
import Toast from "primevue/toast";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Dialog from "primevue/dialog";
import { setupAxiosInterceptors } from "../axios"; // Adjust path
import vue3GoogleLogin from "vue3-google-login";

setupAxiosInterceptors(router);
createApp(App)
    .use(vue3GoogleLogin, {
        clientId:
            "24443962997-s3fa4hu3e0jvoivduf2rl2bcsaue0vf5.apps.googleusercontent.com",
    })
    .use(VueSweetalert2)
    .use(PrimeVue)
    .use(ToastService)
    .use(store)
    .use(router)
    .component("Toast", Toast)
    .component("InputText", InputText)
    .component("Skeleton", Skeleton)
    .component("Button", Button)
    .component("DataTable", DataTable)
    .component("Textarea", Textarea)
    .component("FileUpload", FileUpload)
    .component("Galleria", Galleria)
    .component("Steps", Steps)
    .component("Dialog", Dialog)
    .component("Column", Column)
    .mount("#app");
