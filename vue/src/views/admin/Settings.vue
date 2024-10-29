<template>
    <div class="w-full">
        <div class="flex flex-col items-center w-full px-5">
            <div class="flex flex-wrap justify-center items-center w-full mt-12">
                <div class="w-full xl:w-2/4 p-3" v-if="Object.keys(user).length >0">
                    <div class="card bg-white border border-gray-300 rounded-xl">
                        <!-- بطاقة الملف الشخصي -->
                        <div class="bg-white border-t-4 border-green-400">
                            <div class="pt-1.5 -mb-2 mt-2 overflow-hidden flex justify-center">
                                <img v-if="company.logo" :src="company.logo" alt="company logo" width="100" height="100"  class="w-24 h-24 rounded-full object-cover"/>

                            </div>
                            <div class="px-2">
                                <h1 class="text-gray-900 font-bold text-xl leading-8 my-1 text-right">
                                    {{ user.name }}
                                </h1>
                                <h3 class="text-gray-600 font-lg text-semibold leading-6 text-right">
                                    الدور :
                                    <span class="text-sm font-semibold">مدير</span>
                                </h3>
                                <h3 class="text-gray-600 font-lg text-semibold leading-6 text-right">

                                    <span class="text-sm font-semibold">{{ user.email }}</span>
                                    : البريد الإلكتروني
                                </h3>
                                <h3 class="text-gray-600 font-lg text-semibold leading-6 text-right">
                                    الهاتف:
                                    <span class="text-sm font-semibold text-left">{{ user.phone || "غير متوفر" }}</span>
                                </h3>
                            </div>

                            <ul
                                class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:shadow py-2 px-3 mt-3 divide-y rounded shadow-sm">
                                <li class="flex items-center py-3">
                                    <span>الحالة</span>
                                    <span class="ml-auto">
                                        <span class="bg-green-500 py-1 px-2 rounded text-white text-sm">متصل</span>
                                    </span>
                                </li>
                                <li class="flex items-center py-3">
                                    <span>عضو منذ</span>
                                    <span class="ml-auto">{{ common.formatDate(user.created_at) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="w-full xl:w-2/4 p-3" dir="rtl" v-else>
                    <div class="card bg-white border border-gray-300 rounded-xl">
                        <!-- بطاقة الملف الشخصي -->
                        <div class="bg-white border-t-4 border-green-400">
                            <div class="flex items-center justify-center -mb-2 mt-2 overflow-hidden text-center">
                                <Skeleton shape="circle" size="5rem"></Skeleton>
                            </div>
                            <div class="px-2">
                                <h1 class="text-gray-900 font-bold text-xl leading-8 text-right">
                                    <Skeleton width="12rem"></Skeleton>
                                </h1>
                                <h3
                                    class="text-gray-600 my-2 flex gap-4 items-center font-lg text-semibold leading-6 text-right">
                                    <span>الدور:</span>
                                    <Skeleton width="9rem"></Skeleton>
                                </h3>
                                <h3
                                    class="text-gray-600 my-2 flex gap-4 items-center font-lg text-semibold leading-6 text-right">
                                    <span>البريد الإلكتروني:</span>
                                    <Skeleton width="14rem"></Skeleton>
                                </h3>
                                <h3
                                    class="text-gray-600 my-2 flex gap-4 items-center font-lg text-semibold leading-6 text-right">
                                    <span>الهاتف:</span>
                                    <Skeleton width="14rem"></Skeleton>
                                </h3>
                            </div>

                            <ul
                                class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:shadow py-2 px-3 mt-3 divide-y rounded shadow-sm">
                                <li class="flex justify-between items-center py-3">
                                    <span>الحالة</span>
                                    <Skeleton width="7rem"></Skeleton>
                                </li>
                                <li class="flex justify-between items-center py-3">
                                    <span>عضو منذ</span>
                                    <Skeleton width="7rem"></Skeleton>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="w-full xl:w-2/4 p-3" dir="rtl">
                    <div class="card bg-white border border-gray-300 rounded-xl p-6 pb-7">
                        <div class="flex w-full justify-between items-center pb-4">
                            <button @click="updatePassword" type="button"
                                class="inline-block px-8 py-2 mt-3 mb-4 ml-auto font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-red-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                حفظ
                            </button>
                            <p class="mb-0 white:text-white/80 font-bold">تعديل كلمة المرور</p>

                        </div>

                        <div class="flex flex-wrap mx-3 pb-4">
                            <div class="w-full px-3 mb-4">
                                <label for="current_password"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">كلمة
                                    المرور الحالية</label>
                                <input v-model="passwordBag.currentPassword" type="password" name="current_password"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="new_password"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">كلمة
                                    المرور الجديدة</label>
                                <input v-model="passwordBag.newPassword" type="password" name="new_password"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="confirm_password"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">تأكيد
                                    كلمة المرور</label>
                                <input v-model="passwordBag.confirmation_password" type="password"
                                    name="confirm_password"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div v-if="Object.keys(user).length" class=" p-3 mt-6 w-full" dir="rtl">
                <div class="card bg-white border w-full border-gray-300 rounded-xl p-6 pb-0">
                    <div class="flex items-center">

                        <button @click="updateUserProfile" type="button"
                            class=" px-8 py-2 mt-3 mb-4 ml-auto font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            حفظ
                        </button>
                        <p class="mb-0 white:text-white/80 font-bold">تعديل المستخدم</p>


                    </div>

                    <div class="flex-auto p-6">
                        <p
                            class="leading-normal uppercase white:text-white white:opacity-60 text-sm font-semibold mb-2 text-blue-600">
                            معلومات المستخدم
                        </p>
                        <div id="profile" class="flex flex-wrap -mx-3 pb-2">
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="user_name"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">اسم
                                    المستخدم</label>
                                <input v-model="user.name" type="text" id="user_name"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="user_phone"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">هاتف المستخدم </label>
                                <input v-model="user.phone" type="tel" id="user_phone"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>



                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="w-full p-3 mt-6" dir="rtl">
                <div class="card bg-white border border-gray-300 rounded-xl p-6 pb-0">
                    <div class="flex justify-between items-center">
                        <Skeleton width="10rem"></Skeleton>

                        <Skeleton width="7rem"></Skeleton>
                    </div>

                    <div class="flex-auto p-6">
                        <p
                            class="leading-normal uppercase white:text-white white:opacity-60 text-sm font-semibold mb-2 text-blue-600">
                            معلومات المستخدم
                        </p>
                        <div id="profile" class="flex flex-wrap -mx-3 pb-2">
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_name"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">اسم
                                    المستخدم</label>
                                <Skeleton></Skeleton>
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_address"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">هاتف المستخدم </label>
                                <Skeleton></Skeleton>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div v-if="Object.keys(user).length" class=" p-3 mt-6" dir="rtl">
                <div class="card bg-white border w-full border-gray-300 rounded-xl p-6 pb-0 ">
                    <div class="flex items-center">

                        <button @click="updateProfile" type="button"
                            class=" px-8 py-2 mt-3 mb-4 ml-auto font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            حفظ
                        </button>
                        <p class="mb-0 white:text-white/80 font-bold">تعديل الشركة</p>


                    </div>
                    <form class="w-full m-auto flex justify-center mr-6">
  <div class="flex items-center space-x-6">
    <div>
        <img v-if="company.logo" :src="company.logo" alt="company logo" width="100" height="100"
        class="w-24 h-24 rounded-full object-cover"/>
       </div>
    <label class="block">
      <span class="sr-only">Choose profile photo</span>
      <input
        type="file"
        @change="loadFile"
        id="dropzone-file"
        accept="image/*"
        class="block w-full text-sm text-slate-500
          file:mr-4 file:py-2 file:px-4
          file:rounded-full file:border-0
          file:text-sm file:font-semibold
          file:bg-violet-50 file:text-violet-700
          hover:file:bg-violet-100
        "
      />
    </label>
  </div>
</form>





                    <div class="flex-auto p-6">
                        <p
                            class="leading-normal uppercase white:text-white white:opacity-60 text-sm font-semibold mb-2 text-blue-600">
                            معلومات الشركة
                        </p>
                        <div id="profile" class="flex flex-wrap -mx-3 pb-2">
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_name"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">اسم
                                    الشركة</label>
                                <input v-model="company.name" type="text" id="company_name"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_address"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">العنوان</label>
                                <input v-model="company.address" type="text" name="company_address"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_phone"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">هاتف
                                    الشركة</label>
                                <input v-model="company.phone" type="tel" name="company_phone"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_rc"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">رقم
                                    السجل التجاري</label>
                                <input v-model="company.RC" type="text" name="company_rc"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_ice"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">رقم
                                    ICE</label>
                                <input v-model="company.ICE" type="text" name="company_ice"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_if"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">رقم
                                    IF</label>
                                <input v-model="company.IF" type="text" name="company_if"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="instagram_url"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">رابط
                                    إنستغرام</label>
                                <input v-model="company.instagram_url" type="text" name="instagram_url"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="facebook_url"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">رابط
                                    فيسبوك</label>
                                <input v-model="company.facebook_url" type="text" name="facebook_url"
                                    class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="w-full p-3 mt-6" dir="rtl">
                <div class="card bg-white border border-gray-300 rounded-xl p-6 pb-0">
                    <div class="flex justify-between items-center">
                        <Skeleton width="10rem"></Skeleton>

                        <Skeleton width="7rem"></Skeleton>
                    </div>

                    <div class="flex-auto p-6">
                        <p
                            class="leading-normal uppercase white:text-white white:opacity-60 text-sm font-semibold mb-2 text-blue-600">
                            معلومات الشركة
                        </p>
                        <div id="profile" class="flex flex-wrap -mx-3 pb-2">
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_name"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">اسم
                                    الشركة</label>
                                <Skeleton></Skeleton>
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_address"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">العنوان</label>
                                <Skeleton></Skeleton>
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_phone"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">هاتف
                                    الشركة</label>
                                <Skeleton></Skeleton>
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_rc"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">رقم
                                    السجل التجاري (RC)</label>
                                <Skeleton></Skeleton>
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_ice"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">رقم
                                    ICE</label>
                                <Skeleton></Skeleton>
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="company_if"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">رقم
                                    IF</label>
                                <Skeleton></Skeleton>
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="instagram_url"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">رابط
                                    إنستغرام</label>
                                <Skeleton></Skeleton>
                            </div>
                            <div class="w-full md:w-6/12 px-3 mb-4">
                                <label for="facebook_url"
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 white:text-white/80">رابط
                                    فيسبوك</label>
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
import { ref, onMounted, computed, watch } from "vue";
import store from "../../store";
import common from "../../utils/common";
import { useToast } from "primevue/usetoast";

const user = computed(() => {
    console.dir(user.value);
    return {
        name:store.state.user.data.name,
        phone:store.state.user.data.phone,
        email:store.state.user.data.email,
        created_at:store.state.user.data.created_at,
    }
});
const passwordBag = ref({});
const company = ref({});
const loggedUser = computed(()=>store.state.user.data);
watch(loggedUser, (newVal) => {
    if (newVal?.company) {
        company.value = newVal.company;

        // Set a default logo if it's missing
        if (!company.value.logo) {
            company.value.logo = "https://medvirturials.com/img/old_logo.png";
        }
    } else {
        // If newVal.company is undefined, initialize company.value with a default object
        company.value = {
            logo: "https://medvirturials.com/img/old_logo.png"
        };
    }
});

const toast = useToast();
onMounted(() => {
    store.dispatch("getLoggedUser");
});


function loadFile (event) {
    const file = event.target.files[0]; // Get the first file
      if (!file) return; // Exit if no file is selected

      const reader = new FileReader();

      reader.onload = () => {
        const base64Image = reader.result; // Get the base64 representation of the image
        company.value.logo = base64Image; // Assign base64 image to company.logo
      };

      reader.onerror = (error) => {
        console.error("Error reading the file:", error);
      };

      // Read the file as a data URL
      reader.readAsDataURL(file);
};

function updateProfile() {
    store
        .dispatch("updateAdminProfile", company.value)
        .then((res) => {
            if (res.status === 200)
                common.showToast({ title: res.data.message, icon: "success" });
            else
                common.showValidationErrors(res, toast);

        })
        .catch((error) => {
            console.error(error);
        });
}
function updateUserProfile(){
    store
        .dispatch("updateUserProfile", user.value)
        .then((res) => {
            if (res.status === 200)
                common.showToast({ title: res.data.message, icon: "success" });
            else
                common.showValidationErrors(res, toast);

        })
        .catch((error) => {
            console.error(error);
        });
}
function updatePassword() {
    store
        .dispatch("updatePassword", passwordBag.value)
        .then((res) => {
            if (res.status === 200)
                common.showToast({ title: res.data.message, icon: "success" });
            else
                common.showValidationErrors(res, toast);


        })
        .catch((error) => {
            console.error(error);
        });
}


</script>
