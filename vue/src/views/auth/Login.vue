

<template>
    <main class="w-full h-full py-[3rem]">
    <div id="container"
        class="flex mt-[2rem] bg-opacity-80 mb-[4rem] shadow-md  bg-white border border-gray-200 xl:w-[27%] lg:w-[40%] md:w-[40%] sm:w-[50%] w-[50%] mx-auto rounded-md flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm sm:max-h-sm -mt-12">
            <img class="mx-auto rounded-md h-32 w-32 mt-3 -mb-10" src="@/assets/images/mahali.PNG" alt="Your Company" />
            <h2 class="mt-10 text-center text-xl font-bold leading-9 tracking-tight text-green-900">
                Sign in to your account
            </h2>
        </div>
        <div class="mt-7 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6">
                <div v-if="errorMsg.length || message"
                    class="flex p-4  mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Danger</span>
                    <div>
                        <span class="font-medium">Ensure that these requirements are met:</span>
                        <ul v-if="errorMsg.length" class="mt-1.5 list-disc list-inside">
                            <li v-for="(error, index) in errorMsg" :key="index">{{ error[0] }}</li>

                        </ul>
                        <ul v-if="message" class="mt-1.5 list-disc list-inside">
                            <li>{{ message }}</li>
                        </ul>
                    </div>
                </div>
                <div>


                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input v-model="user.email" id="email" name="email" type="email" autofocus autocomplete="email"
                            required placeholder="oil_store@example.com"
                            class="block w-full rounded-md border-sm p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password
                        </label>
                    </div>
                    <div class="mt-2">
                        <input v-model="user.password" id="password" name="password" type="password"
                            autocomplete="current-password" required=""
                            class="block w-full rounded-md border-1 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" v-model="user.remember" name="remember-me" id="remember me"
                            class="font-semibold text-green-600 hover:text-green-500" />
                        <label for="remember-me" class="ml-3 block text-sm">
                            Remember me
                        </label>
                    </div>
                    <div class="text-sm">
                        <router-link :to="{ name: 'emailVerification' }"
                            class="font-semibold text-green-600 hover:text-green-500 hover:underline">Forgot Password?
                        </router-link>
                    </div>
                </div>

                <div>
                    <Button :loading="loading" @trigger-event="login()" :string="'Sign in'" :color="'green'" />
                </div>
            </form>
            <p class="mt-6 text-sm text-gray-400 text-center">or continue with</p>
            <div class="space-x-6 mt-4 flex justify-center">
                <button type="button" @click="google" class="border-none outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30px" class="inline w-8 h-8" viewBox="0 0 512 512">
                        <path fill="#fbbd00"
                            d="M120 256c0-25.367 6.989-49.13 19.131-69.477v-86.308H52.823C18.568 144.703 0 198.922 0 256s18.568 111.297 52.823 155.785h86.308v-86.308C126.989 305.13 120 281.367 120 256z"
                            data-original="#fbbd00" />
                        <path fill="#0f9d58"
                            d="m256 392-60 60 60 60c57.079 0 111.297-18.568 155.785-52.823v-86.216h-86.216C305.044 385.147 281.181 392 256 392z"
                            data-original="#0f9d58" />
                        <path fill="#31aa52"
                            d="m139.131 325.477-86.308 86.308a260.085 260.085 0 0 0 22.158 25.235C123.333 485.371 187.62 512 256 512V392c-49.624 0-93.117-26.72-116.869-66.523z"
                            data-original="#31aa52" />
                        <path fill="#3c79e6"
                            d="M512 256a258.24 258.24 0 0 0-4.192-46.377l-2.251-12.299H256v120h121.452a135.385 135.385 0 0 1-51.884 55.638l86.216 86.216a260.085 260.085 0 0 0 25.235-22.158C485.371 388.667 512 324.38 512 256z"
                            data-original="#3c79e6" />
                        <path fill="#cf2d48"
                            d="m352.167 159.833 10.606 10.606 84.853-84.852-10.606-10.606C388.668 26.629 324.381 0 256 0l-60 60 60 60c36.326 0 70.479 14.146 96.167 39.833z"
                            data-original="#cf2d48" />
                        <path fill="#eb4132"
                            d="M256 120V0C187.62 0 123.333 26.629 74.98 74.98a259.849 259.849 0 0 0-22.158 25.235l86.308 86.308C162.883 146.72 206.376 120 256 120z"
                            data-original="#eb4132" />
                    </svg>
                </button>

                <button type="button" @click="facebook" class="border-none outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30px" fill="#007bff" class="w-8 h-8"
                        viewBox="0 0 167.657 167.657">
                        <path
                            d="M83.829.349C37.532.349 0 37.881 0 84.178c0 41.523 30.222 75.911 69.848 82.57v-65.081H49.626v-23.42h20.222V60.978c0-20.037 12.238-30.956 30.115-30.956 8.562 0 15.92.638 18.056.919v20.944l-12.399.006c-9.72 0-11.594 4.618-11.594 11.397v14.947h23.193l-3.025 23.42H94.026v65.653c41.476-5.048 73.631-40.312 73.631-83.154 0-46.273-37.532-83.805-83.828-83.805z"
                            data-original="#010002"></path>
                    </svg>
                </button>
            </div>
            <p class="mt-10 text-center text-sm text-gray-500">
                Not a member?
                {{ " " }}
                <a href="/register"
                    class="font-semibold leading-6 text-green-600 hover:text-green-500 hover:underline">Register</a>
            </p>
        </div>
    </div></main>
</template>
<style scoped>
@media only screen and (max-width: 550px) {

    #container {
        width: 80%;
    }


}
main {
  background-image: url("../../assets/images/mahali_background.jpeg");
  background-repeat: no-repeat;
  background-size: cover;
}
</style>
<script setup>
import store from "../../store";
import Button from "../../components/Button.vue";
import { ref } from "vue";
const errors = ref({});
const user = {
    email: "",
    password: "",
    remember: false,
};
function makeitRed() {
    document.querySelector("#email").classList.add("border-red-600");
    document.querySelector("#password").classList.add("border-red-600");
}
let errorMsg = ref([]);
let message = "";
const loading = ref(false);

function login() {
    document.querySelector("#email").classList.remove("border-red-600");
    document.querySelector("#password").classList.remove("border-red-600");
    errorMsg.value = [];
    message = ""
    loading.value = true;
    store
        .dispatch("login", user)
        .then((res) => {

            if (res.status === 200) {
                setTimeout(() => {
                        window.location.href = "http://localhost:5173/admin/dashboard";
                }, 1300);
            }
            if (res.status === 422 && res.response) {
                errorMsg.value = [...Object.values(res.response.data.errors)];
                loading.value = false;
            }
            if (res.status === 401 && res.response) {
                message = res.response.data.message;
                loading.value = false;
            }

        })
        .catch((err) => {
            loading.value = false;
        });
}
function google() {
    window.location.href = "http://127.0.0.1:8000/auth/google"

}
function facebook() {
    window.location.href = "http://127.0.0.1:8000/auth/facebook"
}

</script>
