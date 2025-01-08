

<template>
    <main class="w-full h-full py-[3rem] ">
        <div id="container"
            class="flex  bg-opacity-80 mb-[4rem] shadow-md  bg-white border border-gray-200 xl:w-[27%] lg:w-[40%] md:w-[40%] sm:w-[50%] w-[50%] mx-auto rounded-md flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm sm:max-h-sm -mt-12">
                <img class="mx-auto rounded-md h-32 w-32 mt-3 -mb-10" loading="lazy"
                    src="@/assets/images/SMART_CHARGE_CAMPUS.webp" alt="Your Company" />
                <h2 class="mt-10 text-center text-xl font-bold leading-9 tracking-tight text-green-900">
                    Sign in to your account
                </h2>
            </div>
            <div class="mt-7 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6">
                    <!-- <GoogleLogin class="w-full text-xl font-bold" @success="onGoogleLoginSuccess"
                        @error="onGoogleLoginError" prompt /> -->


                    <div v-if="errorMsg.length || message"
                        class="flex p-4  mb-4 text-sm text-red-800 rounded-lg bg-red-50 " role="alert">
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


                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                            address</label>
                        <div class="mt-2">
                            <input v-model="user.email" id="email" name="email" type="email" autofocus
                                autocomplete="email" required placeholder="oil_store@example.com"
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
                            <input type="checkbox" v-model="user.remember" name="remember-me" id="remember-me"
                                class="font-semibold text-green-600 hover:text-green-500 cursor-pointer" />
                            <label for="remember-me" class="ml-3 block text-sm">
                                Remember me
                            </label>
                        </div>
                        <div class="text-sm">
                            <router-link :to="{ name: 'emailVerification' }"
                                class="font-semibold text-green-600 hover:text-green-500 hover:underline">Forgot
                                Password?
                            </router-link>
                        </div>

                    </div>



                    <div>
                        <Button :loading="loading" @trigger-event="login()" :string="'Sign in'" :color="'green'" />
                    </div>
                    <p class="mt-10 text-center text-sm text-gray-500">
                        Not a member?
                        {{ " " }}
                        <router-link to="/register"
                            class="font-semibold leading-6 text-green-600 hover:text-green-500 hover:underline">Register</router-link>
                    </p>
                </form>
            </div>
        </div>
    </main>
</template>
<style scoped>
@media only screen and (max-width: 550px) {

    #container {
        width: 80%;
    }


}
main {
  background-image: url("../../assets/images/smart_charge_campus_background.jpeg");
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

                    window.location.href = res.data.user.role === 'admin' ? `${import.meta.env.VITE_FRONT_URL}/admin/dashboard` : `${import.meta.env.VITE_FRONT_URL}/dashboard`;
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
        }).finally(()=>loading.value = false);
}
function google() {
    window.location.href = "http://127.0.0.1:8000/auth/google"

} import axios from 'axios';

async function onGoogleLoginSuccess(response) {
    try {
        // Extract the Google ID token from the response
        const googleToken = response.credential;

        // Send the token to the backend for verification
        const { data } = await axios.post('http://localhost:8000/api/auth/google/callback', { token: googleToken });

        // Handle the backend response (e.g., save the token, update UI)
        console.log('Backend Response:', data);

        // Store the token locally (for authenticated requests)
        localStorage.setItem('token', data.token);

        // Store the user data locally (optional)
        localStorage.setItem('user', JSON.stringify(data.user));

        // Optionally, update your UI (e.g., redirect, show welcome message)
        alert('Login successful!');
    } catch (error) {
        console.error('Login failed:', error);
        alert('Google login failed.');
    }
}

function onGoogleLoginError(error) {
    console.error('Google Login Error:', error);
    alert('An error occurred during Google login.');
}


function facebook() {
    window.location.href = "http://127.0.0.1:8000/auth/facebook"
}

</script>
