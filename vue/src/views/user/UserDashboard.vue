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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path
                                d="M11.584 2.376a.75.75 0 0 1 .832 0l9 6a.75.75 0 1 1-.832 1.248L12 3.901 3.416 9.624a.75.75 0 0 1-.832-1.248l9-6Z" />
                            <path fill-rule="evenodd"
                                d="M20.25 10.332v9.918H21a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1 0-1.5h.75v-9.918a.75.75 0 0 1 .634-.74A49.109 49.109 0 0 1 12 9c2.59 0 5.134.202 7.616.592a.75.75 0 0 1 .634.74Zm-7.5 2.418a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Zm3-.75a.75.75 0 0 1 .75.75v6.75a.75.75 0 0 1-1.5 0v-6.75a.75.75 0 0 1 .75-.75ZM9 12.75a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Z"
                                clip-rule="evenodd" />
                            <path d="M12 7.875a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" />
                        </svg>

                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans font-bold text-sm leading-normal text-blue-gray-600">
                            Stations visitées
                        </p>

                        <h4 v-if="statistics.visitedChargeStations >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatIntNumber(statistics.visitedChargeStations) }}
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
                            Total des recharges
                        </p>
                        <h4 v-if="statistics.numberOfChargingTime >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatIntNumber(statistics.numberOfChargingTime) }}
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
                            Total Paye
                        </p>
                        <h4 v-if="statistics.totalPaid >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{
                                common.formatIntNumber(statistics.totalPaid)
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
                <!-- here the place of it -->
                <!-- <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="text-right w-full">
                            <h3 v-if="Object.keys(monthlySales).length" class="text-xl font-bold text-gray-900">
                                المبيعات لكل شهر
                            </h3>
                            <Skeleton v-else width="15rem"></Skeleton>
                        </div>
                    </div>
                    <div class="flex flex-col mt-3 items-center justify-center w-full">
                        <div class="overflow-x-auto rounded-lg w-full">
                            <div class="align-middle inline-block w-full">
                                <div class="shadow overflow-hidden sm:rounded-lg w-full">
                                    <Bar v-if="monthlySales.labels && monthlySales.data" id="my-chart-id" :data="{
                                        labels: monthlySales.labels,
                                        datasets: [
                                            {
                                                label: 'المبيعات الشهرية',
                                                backgroundColor: monthlySales.colors,
                                                borderColor: monthlySales.borderColors,
                                                borderWidth: 1,
                                                data: monthlySales.data,
                                            },
                                        ],
                                    }" :options="options" class="h-[25rem]" />
                                    <Skeleton v-else height="25rem"></Skeleton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="text-right w-full">
                            <h3 v-if="Object.keys(monthlyRemaining).length" class="text-xl  font-bold text-gray-900">
                                الدين لكل شهر
                            </h3>
                            <Skeleton v-else width="15rem"></Skeleton>
                        </div>
                    </div>
                    <div class="flex flex-col mt-3 items-center justify-center w-full">
                        <div class="overflow-x-auto rounded-lg w-full">
                            <div class="align-middle inline-block w-full">
                                <div class="shadow overflow-hidden sm:rounded-lg w-full">
                                    <Bar v-if="monthlyRemaining.labels && monthlyRemaining.data" id="my-chart-id" :data="{
                                        labels: monthlyRemaining.labels,
                                        datasets: [
                                            {
                                                label: ' إجمالي الدين',
                                                backgroundColor: monthlyRemaining.colors,
                                                borderColor: monthlyRemaining.borderColors,
                                                borderWidth: 1,
                                                data: monthlyRemaining.data,
                                            },
                                        ],
                                    }" :options="options" class="h-[25rem]" />
                                    <Skeleton v-else height="25rem"></Skeleton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->


                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
                    <div class="mb-4 flex items-center justify-between ">
                        <div class="w-full text-left">
                            <h3 class="text-xl font-bold text-gray-900  ">
                                Nombre de Charge Par Mois
                            </h3>
                        </div>
                    </div>
                    <div class="flex flex-col mt-3 items-center justify-center w-full">
                        <div class="overflow-x-auto rounded-lg w-full">
                            <div class="align-middle inline-block w-full">
                                <div class="shadow overflow-hidden sm:rounded-lg w-full">

                                    <Bar v-if="chargePerMonth.labels && chargePerMonth.data" id="my-chart-id" :data="{
                                        labels: chargePerMonth.labels,
                                        datasets: [{
                                            label: 'Charges par Mois', // Give a label for the dataset
                                            data: chargePerMonth.data, // Your dataset values
                                            backgroundColor: chargePerMonth.colors, // Example background color
                                            borderColor:chargePerMonth.borderColors, // Example border color
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
                                Nombre de Charge Par Semaine
                            </h3>
                        </div>
                    </div>


                    <div class="flex flex-col mt-3 items-center justify-center w-full">
                        <div class="overflow-x-auto rounded-lg w-full">
                            <div class="align-middle inline-block w-full">
                                <div class="shadow overflow-hidden sm:rounded-lg w-full">

                                    <Bar v-if="chargePerWeek.labels && chargePerWeek.data" id="my-chart-id" :data="{
                                        labels: chargePerWeek.labels,
                                        datasets: [{
                                            label: 'Charges par Semaine', // Give a label for the dataset
                                            data: chargePerWeek.data, // Your dataset values
                                            backgroundColor: chargePerWeek.colors, // Example background color
                                            borderColor: chargePerWeek.borderColors, // Example border color
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
import Avatar from "vue-avatar-component";
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
const clients = ref({});
const sells = ref({});
const weeklySales = ref({});
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
const chargePerWeek = ref({});
const chargePerMonth = ref({});
onMounted(() => {

    // fetchMonthlySales();
    //  fetchWeeklySales();
    getMonthlyCharge();
    fetchChargeAnalytics();
    fetchDashboardDetails();

    // getMonthlyRemaining();
    //      fetchStockAnalytics();

});

const statistics = ref({});


function getMonthlyCharge() {
    store.dispatch("fetchChargePerMonth").then((res) => {
        chargePerMonth.value = res;
    });
}
function fetchChargeAnalytics() {
    store.dispatch("fetchChargePerWeek").then((res) => {
        chargePerWeek.value = res;
    });
}



function fetchDashboardDetails() {
    store.dispatch("getUserDashboardData").then((res) => {
        statistics.value = res;
    });
}


</script>
