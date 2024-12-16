<template>
    <div class="min-h-screen flex items-center w-full justify-center" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1440 320\'%3E%3Cpath fill=\'%2300B38F\' fill-opacity=\'0.7\' d=\'M0,128L34.3,154.7C68.6,181,137,235,206,245.3C274.3,256,343,224,411,224C480,224,549,256,617,272C685.7,288,754,288,823,277.3C891.4,267,960,245,1029,218.7C1097.1,192,1166,160,1234,128C1302.9,96,1371,64,1406,48L1440,32L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z\'%3E%3C/path%3E%3C/svg%3E');
              background-size: cover;
              background-position: bottom;
              background-repeat: no-repeat;">
        <div
            class="flex flex-row gap-10 xl:w-[45%] w-[70%] xl:flex-nowrap flex-wrap justify-center items-center p-6 border shadow-lg rounded-lg bg-white">
            <div class="flex flex-col items-center mb-6">
                <img class="h-22 w-22 mb-2" src="@/assets/images/SMART_CHARGE_CAMPUS.webp" alt="Your Company" />
                <h2 class="text-3xl font-bold tracking-tight text-green-900">ALMOBARKA</h2>
            </div>
            <form class="w-full">
                <div class="mt-8">
                    <div v-if="error">
                        <Alert id="alert">
                            <ul class="mt-1.5 list-disc list-inside">
                                <li>{{ error }}</li>
                            </ul>
                        </Alert>
                    </div>

                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                    <div id="success" class="flex hidden p-4 mt-2 -mb-2 text-sm text-green-600 rounded-lg bg-green-50"
                        role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                        verification code sent successfully check your inbox, redirecting ...
                    </div>
                    <div class="mt-2">
                        <input v-model="email" id="email" name="email" type="email" autofocus autocomplete="email"
                            required=""
                            class="block w-full p-2 rounded-md border-sm py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>

                <div class="mt-4">
                    <Button :loading="loading" @trigger-event="sendVerificationCode()" :string="'Verify Now'"
                        :color="'green'" />
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
body {
  margin: 0;
  padding: 0;
  height: 100vh;
  /* Ensure body takes full height */
  overflow-x: hidden;
  /* Prevent scrolling */
}
</style>

<script setup>
import store from "../../store";
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import Button from "../../components/Button.vue";
import CryptoJS from 'crypto-js'
const error = ref("");
const email = ref("");
const loading = ref(false);

const router = useRouter();
async function sendVerificationCode() {
  loading.value = true;

  if (!email.value) {
    error.value = "Email address is required";
    return;
  }
  await store.dispatch("sendVerificationCode", email.value).then(res => {
    if (res.status === 200 && res.data.status === 'success') {
      error.value = "";
      document.getElementById("success").classList.remove("hidden");
      const encryptedToken = CryptoJS.AES.encrypt(res.data.token, "code").toString();
      setTimeout(() => {
        loading.value = false;
        router.push({ name: "code", query: { t: btoa(encryptedToken) } });
      }, 2000);
    } else {
      loading.value = false;

      error.value = res.response.data.message
    }
  });


};

</script>
