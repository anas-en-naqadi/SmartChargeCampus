<template>
    <div>
        <div class="m-10 ">
            <div class="mb-813 grid gap-y-10 gap-x-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md">
                    <div
                        class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-blue-600 to-blue-400 text-white shadow-blue-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>


                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans text-sm leading-normal font-bold text-blue-gray-600">
                            Total des réservations
                        </p>
                        <h4 v-if="statistics.totalReservations >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatIntNumber(statistics.totalReservations) }}
                        </h4>
                        <h4 v-else
                            class=" antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p
                            class="flex justify-between antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong class="text-green-500">+
                                100%</strong>

                            &nbsp;Smart Charge
                        </p>

                    </div>
                </div>


                <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md">
                    <div
                        class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-pink-600 to-pink-400 text-white shadow-pink-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>


                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans font-bold text-sm leading-normal text-blue-gray-600">
                            Total Étudiants </p>

                        <h4 v-if="statistics.totalStudent >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatIntNumber(statistics.totalStudent) }}
                        </h4>
                        <h4 v-else
                            class="antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p
                            class="flex justify-between antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong class="text-green-500">+
                                100%</strong>

                            &nbsp;Plug in
                        </p>
                    </div>
                </div>





                <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md">
                    <div
                        class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-green-600 to-green-400 text-white shadow-green-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                        </svg>

                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans text-sm leading-normal font-bold text-blue-gray-600">
                            Total Stations
                        </p>
                        <h4 v-if="statistics.totalChargeStation >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatIntNumber(statistics.totalChargeStation) }}
                        </h4>
                        <h4 v-else
                            class="antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p
                            class="flex justify-between antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong class="text-green-500">+
                                100%</strong>

                            &nbsp;Power Up
                        </p>
                    </div>
                </div>






                <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md">
                    <div
                        class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-orange-600 to-orange-400 text-white shadow-orange-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>

                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans text-sm leading-normal font-bold text-blue-gray-600">
                            Total Gagné
                        </p>
                        <h4 v-if="statistics.totalWin >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{
                            common.formatIntNumber(statistics.totalWin)
                            }} DH

                        </h4>
                        <h4 v-else
                            class="antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p
                            class="flex justify-between antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong class="text-green-500">+
                                100%</strong>

                            &nbsp;Stay Smart
                        </p>
                    </div>
                </div>

            </div>

        </div>

        <div class=" px-4">

            <div class="w-full grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-4 gap-4">

                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
                    <div class="mb-4 flex items-center justify-between ">
                        <div class="w-full text-left">
                            <h3 class="text-xl font-bold text-gray-900  ">
                                Total Gagné Par Semaine
                            </h3>
                        </div>
                    </div>


                    <div class="flex flex-col mt-3 items-center justify-center w-full">
                        <div class="overflow-x-auto rounded-lg w-full">
                            <div class="align-middle inline-block w-full">
                                <div class="shadow overflow-hidden sm:rounded-lg w-full">

                                    <Bar v-if="gainWeeklyAmount.labels && gainWeeklyAmount.data" id="my-chart-id" :data="{
                                        labels: gainWeeklyAmount.labels,
                                        datasets: [{
                                            label: 'Total Gagne en DH', // Give a label for the dataset
                                            data: gainWeeklyAmount.data, // Your dataset values
                                            backgroundColor: gainWeeklyAmount.colors, // Example background color
                                            borderColor: gainWeeklyAmount.borderColors, // Example border color
                                            borderWidth: 1 // Example border width
                                        }]
                                    }" :options="options" class="h-[25rem]" />
                                    <Skeleton v-else height="25rem"></Skeleton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
                    <div class="mb-4 flex items-center justify-between ">
                        <div class="w-full text-left">
                            <h3 class="text-xl font-bold text-gray-900  ">
                                Répartition des Statuts de Paiement
                            </h3>
                        </div>
                    </div>


                    <div class="flex flex-col mt-3 items-center justify-center w-full">
                        <div class="overflow-x-auto rounded-lg w-full">
                            <div class="align-middle inline-block w-full">
                                <div class="shadow overflow-hidden sm:rounded-lg ">

                                    <div class="w-full h-[25rem] flex justify-center">
                                        <Pie v-if="paymentStatus.labels && paymentStatus.data"
                                            id="payment-status-pie-chart" :data="{
                                            labels: paymentStatus.labels,
                                            datasets: [{
                                                label: 'Répartition des Statuts de Paiement',
                                                data: paymentStatus.data, // Données en pourcentage
                                                backgroundColor: paymentStatus.colors, // Couleurs
                                                borderColor: paymentStatus.borderColors, // Couleurs des bordures
                                                borderWidth: 1
                                            }]
                                        }" :options="{responsive:true}" />

                                        <Skeleton v-else height="25rem"></Skeleton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">

                    <div class="mb-4 flex items-center justify-between ">
                        <div class="w-full text-left">
                            <h3 class="text-xl font-bold text-gray-900  ">
                                Total Gagné Par Mois
                            </h3>
                        </div>
                    </div>
                    <div class="flex flex-col mt-3 items-center justify-center w-full">
                        <div class="overflow-x-auto rounded-lg w-full">
                            <div class="align-middle inline-block w-full">
                                <div class="shadow overflow-hidden sm:rounded-lg w-full">

                                    <Bar v-if="gainMonthAmount.labels && gainMonthAmount.data" id="my-chart-id" :data="{
                                        labels: gainMonthAmount.labels,
                                        datasets: [{
                                            label: 'Total Gagne en DH', // Give a label for the dataset
                                            data: gainMonthAmount.data, // Your dataset values
                                            backgroundColor: gainMonthAmount.colors, // Example background color
                                            borderColor: gainMonthAmount.borderColors, // Example border color
                                            borderWidth: 1 // Example border width
                                        }]
                                    }" :options="options" class="h-[25rem]" />
                                    <Skeleton v-else height="25rem"></Skeleton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
                    <div class="mb-4 flex items-center justify-between ">
                        <div class="w-full text-left">
                            <h3 class="text-xl font-bold text-gray-900  ">
                                Répartition des Statuts de Paiement
                            </h3>
                        </div>
                    </div>


                    <div class="flex flex-col mt-3 items-center justify-center w-full">
                        <div class="overflow-x-auto rounded-lg w-full">
                            <div class="align-middle inline-block w-full">

                                    <div class="shadow overflow-hidden sm:rounded-lg w-full">

                                        <Bar v-if="chargeStationPortCount.labels && chargeStationPortCount.data" id="my-chart-id"
                                            :data="{
                                                labels: chargeStationPortCount.labels,
                                                datasets: [{
                                                    label: 'Total Port', // Give a label for the dataset
                                                    data: chargeStationPortCount.data, // Your dataset values
                                                    backgroundColor: chargeStationPortCount.colors, // Example background color
                                                    borderColor: chargeStationPortCount.borderColors, // Example border color
                                                    borderWidth: 1 // Example border width
                                                }]
                                            }" :options="options" class="h-[25rem]" />
                                        <Skeleton v-else height="25rem"></Skeleton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>




        <!-- test-->

    </div>
</template>



<script setup>
import { Bar, Pie, Doughnut, Line } from "vue-chartjs";
import { ref, onMounted } from "vue";
import common from "@/utils/common";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    PointElement,
    CategoryScale,
    LinearScale,
    PieController,
    ArcElement,
    LineElement
} from "chart.js";
import store from "../../store";
import { data } from "autoprefixer";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PieController,
    ArcElement,
    PointElement,
    LineElement
);
const loading = ref(true);
const paymentStatus = ref({});
const sells = ref({});
const weeklySales = ref({});
const chargeStationPortCount = ref({});
const thisMonth = ref({});
const options = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        x: {
            grid: {
                color: '#ddd',
            },
            ticks: {
                color: '#333',
            },
        },
        y: {
            grid: {
                color: '#ddd',
            },
            ticks: {
                color: '#333',
            },
        },
    }
}
const gainWeeklyAmount = ref({});
const gainMonthAmount = ref({});
onMounted(() => {

    getChargeStationPortCount();
    getWeeklyGainAmount();
    getMonthlyGainAmount();
    fetchDashboardDetails();

     getPaymentStatus();

});

const statistics = ref({});


function getMonthlyGainAmount() {
    store.dispatch("fetchMonthlyGainAmount").then((res) => {
        gainMonthAmount.value = res;
    });
}
function getWeeklyGainAmount() {
    store.dispatch("fetchWeeklyGainAmount").then((res) => {
        gainWeeklyAmount.value = res;
    });
}
function getChargeStationPortCount() {
    store.dispatch("fetchChargeStationPortCount").then((res) => {
        chargeStationPortCount.value = res;
    });
}
function getPaymentStatus() {
    store.dispatch("fetchPaymentStatus").then((res) => {
        paymentStatus.value = res;
    });
}


function fetchDashboardDetails() {
    store.dispatch("getAdminDashboardData").then((res) => {
        statistics.value = res;
    });
}


</script>
