<template>
    <div class="card mt-2 m-3 bg-gray-50  rounded-xl">
        <div class="py-14 px-4 md:px-6 2xl:px-5 2xl:container 2xl:mx-auto" v-if="!loading">
            <div class="flex justify-start item-start space-y-2 flex-col">
                <h1 class="text-3xl white:text-white lg:text-4xl font-semibold leading-7 lg:leading-9 text-gray-800">
                    Order #{{ order.id }}
                </h1>
                <p class="text-base white:text-gray-300 font-medium leading-6 text-gray-600">
                    {{ common.formatDate(order.created_at) }}
                </p>
            </div>
            <div
                class="mt-10 flex flex-col xl:flex-row jusitfy-center items-stretch w-full xl:space-x-8 space-y-4 md:space-y-6 xl:space-y-0">
                <div class="flex flex-col justify-start items-start w-full space-y-4 md:space-y-6 xl:space-y-8">
                    <div
                        class="flex rounded-md flex-col gap-10 justify-start items-start white:bg-gray-800 bg-white px-4 py-4 md:py-6 md:p-6 xl:p-8 w-full">
                        <p
                            class="text-lg md:text-xl white:text-white font-semibold leading-6 xl:leading-5 text-gray-800">
                            Status de commande
                        </p>
                        <div class="w-full ">
                            <Steps :model="items" class="custom-steps " :readonly="false">
                                <template #item="{ item, index }">
                                    <div class="flex items-center justify-center gap-4">
                                        <div class="flex flex-col items-center" @click="handleStepClick(index)">
                                            <span :class="[
                                                'flex items-center justify-center border border-green-500 rounded-full h-12 w-12 cursor-pointer',
                                                {
                                                    'bg-green-400 text-white': item.active,
                                                    'text-green-500 bg-white': !item.active,
                                                },
                                            ]">
                                                <i :class="[item.icon, 'text-lg']"></i>
                                            </span>
                                            <span>{{ item.label }}</span>
                                        </div>
                                    </div>
                                </template>
                            </Steps>
                        </div>


                    </div>
                    <div
                        class="flex flex-col justify-start items-start white:bg-gray-800 bg-white rounded-md px-4 py-4 md:py-6 md:p-6 xl:p-8 w-full">
                        <p
                            class="text-lg md:text-xl white:text-white font-semibold leading-6 xl:leading-5 text-gray-800">
                            produits de commande
                        </p>
                        <div
                            class="mt-4 md:mt-6 flex flex-col md:flex-row overflow-x-auto justify-start items-start md:items-center md:space-x-6 xl:space-x-8 w-full">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 white:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-white white:bg-gray-700 white:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-16 py-3">
                                            <span class="sr-only">Image</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3">Product</th>
                                        <th scope="col" class="px-6 py-3">Qty</th>
                                        <th scope="col" class="px-6 py-3">Price</th>
                                        <th scope="col" class="px-6 py-3">discount</th>
                                    </tr>
                                </thead>
                                <tbody v-if="order.items.length">
                                    <tr v-for="item in order.items" :key="item.id"
                                        class="overflow-y-scroll bg-white border-b white:bg-gray-800 white:border-gray-700 hover:bg-white white:hover:bg-gray-600">
                                        <td class="p-4">
                                            <img :src="item.product.image" class="w-24 h-24 rounded-sm"
                                                alt="item image" />
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900 white:text-white">
                                            {{ item.product.product_name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-black white:text-red-500 hover:underline">{{
                                                item.quantity
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900 white:text-white">
                                            {{ item.unit_price }} DH
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-black white:text-red-500 hover:underline">{{
                                                item.product.discount
                                                }}%</span>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td class="h-4"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-red-500 text-center" colspan="6">No products in this order</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div
                        class="flex justify-center flex-col md:flex-row  items-stretch w-full space-y-4 md:space-y-0 md:space-x-6 xl:space-x-8">
                        <div
                            class="flex flex-col px-4 py-6 md:p-6 xl:p-8 w-full bg-white rounded-md white:bg-gray-800 space-y-6">
                            <h3 class="text-xl white:text-white font-semibold leading-5 text-gray-800">
                                Summary
                            </h3>
                            <div
                                class="flex justify-center items-center w-full space-y-4 flex-col border-gray-200 border-b pb-4">
                                <div class="flex justify-between w-full">
                                    <p class="text-base white:text-white leading-4 text-gray-800">
                                        Subtotal
                                    </p>
                                    <p class="text-base white:text-gray-300 leading-4 text-gray-600">
                                        {{ common.formatNumber(order.payment.amount) }}
                                        MAD
                                    </p>
                                </div>
                                <div class="flex justify-between items-center w-full">
                                    <p class="text-base white:text-white leading-4 text-gray-800">
                                        Discount
                                        <span
                                            class="bg-gray-200 p-1 text-xs font-medium white:bg-white white:text-gray-800 leading-3 text-gray-800">STUDENT</span>
                                    </p>
                                    <p class="text-base white:text-gray-300 leading-4 text-gray-600">
                                        -$28.00 (50%)
                                    </p>
                                </div>
                                <div class="flex justify-between items-center w-full">
                                    <p class="text-base white:text-white leading-4 text-gray-800">
                                        Shipping
                                    </p>
                                    <p class="text-base white:text-gray-300 leading-4 text-gray-600">
                                        $8.00
                                    </p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center w-full">
                                <p class="text-base white:text-white font-semibold leading-4 text-gray-800">
                                    Total
                                </p>
                                <p class="text-base white:text-gray-300 font-semibold leading-4 text-gray-600">
                                    {{ common.formatNumber(order.payment.amount) }}
                                    MAD
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex flex-col justify-center px-4 py-6 md:p-6 xl:p-8 w-full bg-white rounded-md white:bg-gray-800 space-y-6">
                            <h3 class="text-xl white:text-white font-semibold leading-5 text-gray-800">
                                Etat Finale du commande
                            </h3>
                            <select v-model="status"
                                class="block py-2.5 px-2.5 w-full rounded-md text-sm text-black bg-transparent border-2 border-gray-500  focus:outline-none focus:ring-0 focus:border-gray-300 peer">
                                <option selected value="null">Choose status</option>
                                <option value="completed">
                                    completed
                                </option>
                                <option value="returned">
                                    returned
                                </option>
                                <option value="canceled">
                                    canceled
                                </option>
                            </select>
                            <div class="w-full flex justify-center items-center">
                                <button @click="changeStatus"
                                    class="hover:bg-black white:bg-white white:text-gray-800 white:hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 py-5 w-96 md:w-full bg-gray-800 text-base font-medium leading-4 text-white">
                                    Save && Close it
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white rounded-md white:bg-gray-800 w-full xl:w-96 flex justify-between items-center md:items-start px-4 py-6 md:p-6 xl:p-8 flex-col">
                    <h3 class="text-xl white:text-white font-semibold leading-5 text-gray-800">
                        Client
                    </h3>
                    <div
                        class="flex flex-col md:flex-row xl:flex-col justify-start items-stretch h-full w-full md:space-x-6 lg:space-x-8 xl:space-x-0">
                        <div class="flex flex-col justify-start items-start flex-shrink-0">
                            <div
                                class="flex justify-center w-full md:justify-start items-center space-x-4 py-8 border-b border-gray-200">
                                <avatar :fullname="order.customer.name" :size="65"></avatar>

                                <div class="flex justify-start items-start flex-col space-y-2">
                                    <p
                                        class="text-base white:text-white font-semibold leading-4 text-left text-gray-800">
                                        {{ order.customer.name }}
                                    </p>
                                    <p class="text-sm white:text-gray-300 leading-5 text-gray-600">
                                        {{ order.customer.totalOrders }} Previous Orders
                                    </p>
                                </div>
                            </div>

                            <div
                                class="flex justify-center text-gray-800 white:text-white md:justify-start items-center space-x-2 py-4 border-b border-gray-200 w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>

                                <p class="cursor-pointer text-sm leading-5 -mt-1.5">
                                    {{ order.customer.email }}
                                </p>
                            </div>
                        </div>
                        <div class="flex justify-between xl:h-full items-stretch w-full flex-col mt-6 md:mt-0">
                            <div
                                class="flex justify-center md:justify-start xl:flex-col flex-col md:space-x-6 lg:space-x-8 xl:space-x-0 space-y-4 xl:space-y-12 md:space-y-0 md:flex-row items-center md:items-start">
                                <div
                                    class="flex justify-center md:justify-start items-center md:items-start flex-col space-y-4 xl:mt-8">
                                    <p
                                        class="text-base white:text-white font-semibold leading-4 text-center md:text-left text-gray-800">
                                        Shipping Address
                                    </p>
                                    <div>
                                        <p
                                            class="w-48 lg:w-full white:text-gray-300 xl:w-48 text-center md:text-left text-sm leading-5 text-gray-600">
                                            {{ order.customer.default_shipping_address.address }}, {{
                                                order.customer.default_shipping_address.city }}
                                            {{ order.customer.default_shipping_address.country }}
                                            {{ order.customer.default_shipping_address.postal_code }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-center md:justify-start items-center md:items-start flex-col space-y-4">
                                    <p
                                        class="text-base white:text-white font-semibold leading-4 text-center md:text-left text-gray-800">
                                        Informations paiement
                                    </p>
                                    <p
                                        class="w-48 lg:w-full white:text-gray-300 xl:w-48 text-center md:text-left text-sm leading-5 text-gray-600">
                                        <span class="font-semibold">methode de paiment:</span>
                                        {{ order.payment.payment_method.toUpperCase() }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
            <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->

            <div class="flex justify-start bg-white item-start p-4 space-y-2 flex-col">
                <Skeleton width="15rem"></Skeleton>
                <Skeleton width="19rem"></Skeleton>
            </div>
            <div
                class="mt-10 flex flex-col xl:flex-row jusitfy-center items-stretch w-full xl:space-x-8 space-y-4 md:space-y-6 xl:space-y-0">
                <div class="flex flex-col justify-start items-start w-full space-y-4 md:space-y-6 xl:space-y-8">
                    <div
                        class="flex flex-col gap-3 justify-center items-center white:bg-gray-800 bg-white px-4 py-4 md:py-6 md:p-6 xl:p-8 w-full">
                        <p
                            class="text-lg text-left md:text-xl white:text-white font-semibold leading-6 xl:leading-5 text-gray-800">
                            Status de commande
                        </p>
                        <div class="w-min">
                            <Skeleton width="40rem" class="mx-18 -mb-10" height="16rem"></Skeleton>
                        </div>
                    </div>
                    <div
                        class="flex flex-col justify-start items-start white:bg-gray-800 bg-white px-4 py-4 md:py-6 md:p-6 xl:p-8 w-full">
                        <p
                            class="text-lg md:text-xl white:text-white font-semibold leading-6 xl:leading-5 text-gray-800">
                            produits de commande
                        </p>
                        <div
                            class="mt-4 md:mt-6 flex flex-col md:flex-row overflow-x-auto justify-start items-start md:items-center md:space-x-6 xl:space-x-8 w-full">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 white:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-white white:bg-gray-700 white:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-16 py-3">
                                            <span class="sr-only">Image</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3">Product</th>
                                        <th scope="col" class="px-6 py-3">Qty</th>
                                        <th scope="col" class="px-6 py-3">Price</th>
                                        <th scope="col" class="px-6 py-3">discount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in new Array(2)" :key="index"
                                        class="overflow-y-scroll bg-white border-b white:bg-gray-800 white:border-gray-700 hover:bg-white white:hover:bg-gray-600">
                                        <td class="p-4">
                                            <Skeleton size="7rem"></Skeleton>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900 white:text-white">
                                            <Skeleton width="17rem"></Skeleton>
                                        </td>
                                        <td class="px-6 py-4">
                                            <Skeleton></Skeleton>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900 white:text-white">
                                            <Skeleton></Skeleton>
                                        </td>
                                        <td class="px-6 py-4">
                                            <Skeleton></Skeleton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div
                        class="flex justify-center md:flex-row flex-col items-stretch w-full space-y-4 md:space-y-0 md:space-x-6 xl:space-x-8">
                        <div
                            class="flex flex-col px-4 py-6 md:p-6 xl:p-8 w-full bg-white white:bg-gray-800 space-y-6">
                            <h3 class="text-xl white:text-white font-semibold leading-5 text-gray-800">
                                Summary
                            </h3>
                            <div
                                class="flex justify-center items-center w-full space-y-4 flex-col border-gray-200 border-b pb-4">
                                <div class="flex justify-between w-full">
                                    <p class="text-base white:text-white leading-4 text-gray-800">
                                        Subtotal
                                    </p>
                                    <Skeleton class="w-[60%] ml-6"></Skeleton>
                                </div>
                                <div class="flex justify-between items-center w-full">
                                    <p class="text-base white:text-white leading-4 text-gray-800">
                                        Discount
                                    </p>

                                    <Skeleton class="w-[60%] ml-6"></Skeleton>
                                </div>
                                <div class="flex justify-between items-center w-full">
                                    <p class="text-base white:text-white leading-4 text-gray-800">
                                        Shipping
                                    </p>

                                    <Skeleton class="w-[60%] ml-6"></Skeleton>
                                </div>
                            </div>
                            <div class="flex justify-between items-center w-full">
                                <p class="text-base white:text-white font-semibold leading-4 text-gray-800">
                                    Total
                                </p>
                                <Skeleton class="w-[50%] ml-6"></Skeleton>
                            </div>
                        </div>
                        <div
                            class="flex flex-col justify-center px-4 py-6 md:p-6 xl:p-8 w-full bg-white white:bg-gray-800 space-y-6">
                            <h3 class="text-xl white:text-white font-semibold leading-5 text-gray-800">
                                Etat Finale du commande
                            </h3>
                            <div class="flex justify-between items-start w-full">

                                <Skeleton width="100%"></Skeleton>
                            </div>
                            <div class="w-full flex justify-center items-center">
                                <Skeleton width="25rem" height="4rem"></Skeleton>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white white:bg-gray-800 w-full xl:w-96 flex justify-between items-center md:items-start px-4 py-6 md:p-6 xl:p-8 flex-col">
                    <h3 class="text-xl white:text-white font-semibold leading-5 text-gray-800">
                        Client
                    </h3>
                    <div
                        class="flex flex-col md:flex-row xl:flex-col justify-start items-stretch h-full w-full md:space-x-6 lg:space-x-8 xl:space-x-0">
                        <div class="flex flex-col justify-start items-start flex-shrink-0">
                            <div
                                class="flex justify-center w-full md:justify-start items-center space-x-4 py-8 border-b border-gray-200">
                                <Skeleton size="5rem" shape="circle"></Skeleton>
                                <Skeleton></Skeleton>
                            </div>

                            <div
                                class="flex justify-center text-gray-800 white:text-white md:justify-start items-center space-x-4 py-4 border-b border-gray-200 w-full">
                                <!-- avatar -->
                                <Skeleton class="ml-2"></Skeleton>
                            </div>
                        </div>
                        <div class="flex justify-between xl:h-full items-stretch w-full flex-col mt-6 md:mt-0">
                            <div
                                class="flex justify-center md:justify-start xl:flex-col flex-col md:space-x-6 lg:space-x-8 xl:space-x-0 space-y-4 xl:space-y-12 md:space-y-0 md:flex-row items-center md:items-start">
                                <div
                                    class="flex justify-center md:justify-start items-center md:items-start flex-col space-y-4 xl:mt-8">
                                    <p
                                        class="text-base white:text-white font-semibold leading-4 text-center md:text-left text-gray-800">
                                        Shipping Address
                                    </p>
                                    <div>
                                        <p
                                            class="w-48 lg:w-full white:text-gray-300 xl:w-48 text-center md:text-left text-sm leading-5 text-gray-600">
                                            <Skeleton></Skeleton>
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-center md:justify-start items-center md:items-start flex-col space-y-4">
                                    <p
                                        class="text-base white:text-white font-semibold leading-4 text-center md:text-left text-gray-800">
                                        Informations paiement
                                    </p>
                                    <p
                                        class="w-48 lg:w-full white:text-gray-300 xl:w-48 text-center md:text-left text-sm leading-5 text-gray-600">
                                        <Skeleton></Skeleton>
                                    </p>
                                </div>
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
import { useRoute, useRouter } from "vue-router";
import store from "../../../store";
import common from "../../../utils/common";
import Avatar from "vue-avatar-component";
import { useToast } from "primevue/usetoast";

const order = ref({});
const status = ref(null);
const router = useRouter();
const route = useRoute();
const toast =useToast();
const loading = ref(true);
let items = [
    { label: "Placed", icon: "pi pi-shopping-cart", active: false },
    { label: "Processing", icon: "pi pi-cog", active: false },
    { label: "Shipped", icon: "pi pi-send", active: false },
    { label: "Delivered", icon: "pi pi-check-circle", active: false },
];
onMounted(() => {
    fetchOrderById();
});
function fetchOrderById() {
    var id = route.params.id;

    store
        .dispatch("getOrderById", id)
        .then((res) => {
            if (res.status === 200 && res.data) {
                order.value = res.data;
                status.value = res.data.status;
                items.map((item) => {
                    item.active = order.value.status === item.label.toLowerCase();
                });
                loading.value = false;

            }

        })
        .catch((error) => console.error(error))

}

function handleStepClick(index) {
    items.forEach((item, i) => {
        item.active = i === index;
    });
    status.value = items[index].label;
    changeStatus();
}
function changeStatus() {
    if(status.value === "null")
    status.value = null;
    store
        .dispatch("setOrderStatus", {
            status: status.value?.toLowerCase(),
            id: route.params.id,
        })
        .then((res) => {

            if (res.status === 200 && res.data) {
                common.showToast({ title: res.data.message, icon: "success" });
                items.forEach((item, i) => {
                    item.active = i === index;
                });



            }
            if (res.response && res.response.status === 422) {

[...Object.values(res.response.data.errors)].forEach((e) => {
    toast.add({
        severity: "error",
        summary: "Error",
        detail: e[0],
        life: 3000,
    });
});
}
        })
        .catch((error) => error);
}

</script>
