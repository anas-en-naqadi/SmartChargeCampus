<template>
    <div class="w-full">
        <div class="flex flex-col items-center w-full px-5">
            <div class="flex flex-wrap justify-center items-center w-full mt-12">
                <div class="w-full xl:w-2/4 p-3" v-if="Object.keys(user).length">
                    <div class="card bg-white border border-gray-300 rounded-xl">
                        <!-- Profile Card -->
                        <div class="bg-white  border-t-4 border-green-400">
                            <div class="pt-1.5 -mb-2 mt-2 overflow-hidden text-center">
                                <Avatar :fullname="user.name" :size="60" />
                            </div>
                            <div class="px-2">
                                <h1 class="text-gray-900 font-bold text-xl leading-8 my-1">
                                    {{ user.name }}
                                </h1>

                                <h3 class="text-gray-600 font-lg text-semibold leading-6">
                                    Role:
                                    <span class="text-sm font-semibold">{{ user.role }}</span>
                                </h3>
                                <h3 class="text-gray-600 font-lg text-semibold leading-6">
                                    email:
                                    <span class="text-sm font-semibold">{{ user.email }}</span>
                                </h3>

                            </div>


                            <ul
                                class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:shadow py-2 px-3 mt-3 divide-y rounded shadow-sm">
                                <li class="flex items-center py-3">
                                    <span>Status</span>
                                    <span class="ml-auto"><span
                                            class="bg-green-500 py-1 px-2 rounded text-white text-sm">online</span></span>
                                </li>
                                <li class="flex items-center py-3">
                                    <span>Member since</span>
                                    <span class="ml-auto">{{
                                        common.formatDate(user.created_at)
                                        }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="w-full xl:w-2/4 p-3" v-else>
                    <div class="card bg-white border border-gray-300 rounded-xl">
                        <!-- Profile Card -->
                        <div class="bg-white  border-t-4 border-green-400">
                            <div class=" flex items-center justify-center -mb-2 mt-2 overflow-hidden text-center">
                                <Skeleton shape="circle" size="5rem"></Skeleton>
                            </div>
                            <div class="px-2">
                                <h1 class="text-gray-900 font-bold text-xl leading-8 ">
                                    <Skeleton width="12rem"></Skeleton>
                                </h1>

                                <h3 class="text-gray-600 my-2 flex gap-4 items-center font-lg text-semibold leading-6">
                                    <span>Role:</span>
                                    <Skeleton width="9rem"></Skeleton>
                                </h3>
                                <h3 class="text-gray-600 my-2 flex gap-4 items-center font-lg text-semibold leading-6">
                                    <span>Email:</span>
                                    <Skeleton width="14rem"></Skeleton>
                                </h3>

                            </div>


                            <ul
                                class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:shadow py-2 px-3 mt-3 divide-y rounded shadow-sm">
                                <li class="flex justify-between items-center py-3">
                                    <span>Status</span>

                                    <Skeleton width="7rem"></Skeleton>

                                </li>
                                <li class="flex justify-between items-center py-3">
                                    <span>Member Since</span>

                                    <Skeleton width="7rem"></Skeleton>

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="w-full xl:w-2/4 p-3">
                    <form @submit.prevent="updatePassword"
                        class="card bg-white border border-gray-300 rounded-xl p-6 pb-7">
                        <div class="flex w-full justify-between gap-4 items-center pb-4">
                            <p class="mb-0 white:text-white/80 font-bold">Edit Password</p>
                            <div class="cursor-pointer ml-[25%]"
                                :title="!showCurrentPassword ? 'Afficher le mot de pass' : 'cacher le mot de pass'">
                                <svg v-if="!showCurrentPassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" @click="showCurrentPassword = !showCurrentPassword"
                                    stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    @click="showCurrentPassword = !showCurrentPassword" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>

                            </div>
                            <button
                                class="inline-block px-8 py-2 mt-3 mb-4 ml-auto font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-red-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                save
                            </button>
                        </div>

                        <div class="flex flex-wrap mx-3 pb-4">
                            <div class="w-full px-3 mb-4">
                                <label for="current_password"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">Current
                                    Password</label>
                                <input required v-model="passwordBag.currentPassword" :type="showCurrentPassword ? 'text' : 'password'"
                                    name="current_password"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="new_password"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">New
                                    Password</label>
                                <input required v-model="passwordBag.newPassword" :type="showCurrentPassword ? 'text' : 'password'" name="new_password"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="confirm_password"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">Confirm
                                    Password</label>
                                <input required v-model="passwordBag.newPassword_confirmation" :type="showCurrentPassword ? 'text' : 'password'"
                                    name="confirm_password"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div v-if="Object.keys(user).length" @submit.prevent="updateProfile" class="w-full p-3 mt-6">
                <form class="card bg-white border border-gray-300 rounded-xl p-6 pb-0">
                    <div class="flex items-center">
                        <p class="mb-0 white:text-white/80 font-bold">Edit Profile</p>
                        <button
                            class="inline-block px-8 py-2 mt-3 mb-4 ml-auto font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            save
                        </button>
                    </div>

                    <div class="flex-auto p-6">
                        <p
                            class="leading-normal uppercase white:text-white white:opacity-60 text-sm font-semibold mb-2 text-blue-600">
                            User Information
                        </p>
                        <div id="profile" class="flex flex-wrap -mx-3 pb-2">
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="name"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">
                                    name</label>
                                <input required v-model="user.name" type="text" id="name"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="email"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">Email
                                    address</label>
                                <input required v-model="user.email" type="email" id="email"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>


                        </div>
                    </div>
                </form>
            </div>
            <div v-else class="w-full p-3 mt-6">
                <div class="card bg-white border border-gray-300 rounded-xl p-6 pb-0">
                    <div class="flex justify-between items-center">
                        <p class="mb-0 white:text-white/80 font-bold">Edit Profile</p>
                        <Skeleton width="7rem"></Skeleton>
                    </div>

                    <div class="flex-auto p-6">
                        <p
                            class="leading-normal uppercase white:text-white white:opacity-60 text-sm font-semibold mb-2 text-blue-600">
                            User Information
                        </p>
                        <div id="profile" class="flex flex-wrap -mx-3 pb-2">
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="first_name"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">
                                    name</label>
                                <Skeleton></Skeleton>
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="email"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">Email
                                    address</label>
                                <Skeleton></Skeleton>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Toast />
    </div>
</template>


<script setup>
import { ref, onMounted } from "vue";
import store from "../../store";
import Avatar from "vue-avatar-component";
import common from "../../utils/common";
import { useToast } from "primevue/usetoast";
const user = ref({});
const showCurrentPassword = ref(false);

const passwordBag = ref({});
const toast = useToast();
onMounted(() => {
    store.dispatch("getLoggedUser").then((res) => {
        if (res.status === 200)
            user.value = res.data;
    })
})
function updateProfile() {
    document.body.style.cursor = 'wait';
    store
        .dispatch("updateUserProfile", user.value)
        .then((res) => {
            if (res && res.status === 200)
                common.showToast({ title: res.data.message, icon: "success" });
            if (res && res.response && res.response.status === 422) {
               common.showValidationErrors(res,toast);
            }

        })
        .catch((error) => {
            console.error(error);
        }).finally(() => document.body.style.cursor = 'default'
        )
}
function updatePassword() {
    document.body.style.cursor = 'wait';

    store
        .dispatch("updatePassword", passwordBag.value)
        .then((res) => {
            if (res && res.status === 200)
                common.showToast({ title: res.data.message, icon: "success" });
            passwordBag.value = {};
            if (res && res.response &&res.response.status === 422) {
                common.showValidationErrors(res, toast);

            }

        })
        .catch((error) => {
            console.error(error);
        }).finally(() => document.body.style.cursor = 'default'
        );
}
</script>
