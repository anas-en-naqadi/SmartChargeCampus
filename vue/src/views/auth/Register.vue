<template>
    <main class="w-full h-full py-[5rem]">
    <div
        class="flex  shadow-md mb-[5rem] bg-opacity-80 mt-[1.5rem] bg-white border border-gray-200 lg:w-[40%] md:w-[40%] sm:w-[40%] w-[80%] xl:w-[30%] mx-auto rounded-md min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm sm:max-h-sm -mt-12">
            <img class="mx-auto h-32 w-32 rounded-md mt-5 -mb-10" src="@/assets/images/mahali.PNG" alt="Your Company" />
        </div>

        <div class=" sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6">
                <div id="success" class="hidden p-4 mb-4 text-sm text-green-600 rounded-lg bg-green-50" role="alert">
                    Your account was created successfully, redirecting ...
                </div>
                <div v-if="errorMsg.length"
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
                        <ul class="mt-1.5 list-disc list-inside">
                            <li v-for="(error, index) in errorMsg" :key="index">{{ error[0] }}</li>

                        </ul>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Full Name</label>
                    <div class="mt-2">
                        <input autofocus v-model="user.name" id="name" name="fullName" placeholder="Ahmed ...."
                            type="text"
                            class="px-2 block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input v-model="user.email" id="email" name="email" type="email" placeholder="Oil@example.com"
                            autocomplete="email"
                            class="px-2 block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Phone number</label>
                    <div class="mt-2">
                        <input v-model="user.phone" id="phone" name="phone" type="text" placeholder="06"
                            class="px-2 block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    </div>
                    <div class="mt-2">
                        <input v-model="user.password" id="password" name="password" type="password" required
                            autocomplete="current-password"
                            class="px-2 block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password_confirmation"
                            class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
                    </div>
                    <div class="mt-2">
                        <input v-model="user.password_confirmation" id="password_confirmation"
                            name="password_confirmation" type="password" required autocomplete="current-password"
                            class="px-2 block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>
                <div>
                    <Button :loading="loading" @trigger-event="register()" :string="'Sign up'" :color="'green'" />
                </div>
            </form>
        </div>
    </div>
    </main>
</template>
<style scoped>
main {
    width:100%;
    height:100%;
  background-image: url("../../assets/images/mahali_background.jpeg");
  background-repeat: no-repeat;
  background-size: cover;
}
</style>
<script setup>
import { ref } from "vue";
import store from "../../store";
import { useRouter } from "vue-router";
import Button from "../../components/Button.vue";
let errorMsg = ref([]);
const user = {
    name: "",
    email: "",
    password: "",
    phone: "",
    password_confirmation: "",
};
const router = useRouter();
const loading = ref(false);

function register() {
    loading.value = true;
    store
        .dispatch("register", user)
        .then((res) => {
            loading.value = false;

            if (res.status == 200 && res.data) {
                document.querySelector("#success").classList.remove("hidden");

                setTimeout(() => {
                    if (res.data.is_admin)
                        router.push({
                            name: "dashboard",
                        });
                    else
                        router.push({
                            name: "home",
                        });
                }, 1300);
            }

            if (res.response && res.response.status === 422) {
                errorMsg.value = [...Object.values(res.response.data.errors)];
                loading.value = false;
            }

        })
        .catch((err) => {
            loading.value = false;

        });
}
</script>
