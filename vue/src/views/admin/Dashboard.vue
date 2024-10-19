<template>
    <div>

        <div class="m-10 pt-10">
            <div class="mb-813 grid gap-y-10 gap-x-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md">
                    <div
                        class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-blue-600 to-blue-400 text-white shadow-blue-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path
                                d="M10.464 8.746c.227-.18.497-.311.786-.394v2.795a2.252 2.252 0 0 1-.786-.393c-.394-.313-.546-.681-.546-1.004 0-.323.152-.691.546-1.004ZM12.75 15.662v-2.824c.347.085.664.228.921.421.427.32.579.686.579.991 0 .305-.152.671-.579.991a2.534 2.534 0 0 1-.921.42Z" />
                            <path fill-rule="evenodd"
                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v.816a3.836 3.836 0 0 0-1.72.756c-.712.566-1.112 1.35-1.112 2.178 0 .829.4 1.612 1.113 2.178.502.4 1.102.647 1.719.756v2.978a2.536 2.536 0 0 1-.921-.421l-.879-.66a.75.75 0 0 0-.9 1.2l.879.66c.533.4 1.169.645 1.821.75V18a.75.75 0 0 0 1.5 0v-.81a4.124 4.124 0 0 0 1.821-.749c.745-.559 1.179-1.344 1.179-2.191 0-.847-.434-1.632-1.179-2.191a4.122 4.122 0 0 0-1.821-.75V8.354c.29.082.559.213.786.393l.415.33a.75.75 0 0 0 .933-1.175l-.415-.33a3.836 3.836 0 0 0-1.719-.755V6Z"
                                clip-rule="evenodd" />
                        </svg>

                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans text-sm leading-normal font-bold text-blue-gray-600">
                            إجمالي الربح اليوم
                        </p>
                        <h4 v-if="statistics.todayMoney >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatNumber(Math.floor(statistics.todayMoney)) }} درهم
                        </h4>
                        <h4 v-else
                            class=" antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p v-if="statistics.moneyPercentageChange >= 0"
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.moneyPercentageChange >= 0" class="text-green-500">+{{
                                statistics.moneyPercentageChange }}%</strong>
                            <strong v-else class="text-green-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>
                            &nbsp;أكثر من أمس
                        </p>
                        <p v-else
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.moneyPercentageChange <= 0" class="text-red-500">{{
                                statistics.moneyPercentageChange }}%</strong>
                            <strong v-else class="text-red-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>
                            &nbsp;أقل من أمس
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
                            إجمالي الدين اليوم
                        </p>

                        <h4 v-if="statistics.todayOwn >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatNumber(statistics.todayOwn) }} درهم
                        </h4>
                        <h4 v-else
                            class="antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p v-if="statistics.ownPercentageChange >= 0"
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.ownPercentageChange >= 0" class="text-green-500">+{{
                                statistics.ownPercentageChange }}%</strong>
                            <strong v-else class="text-green-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>
                            &nbsp;أكثر من أمس
                        </p>
                        <p v-else
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.ownPercentageChange <= 0" class="text-red-500">{{
                                statistics.ownPercentageChange }}%</strong>
                            <strong v-else class="text-red-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>
                            &nbsp;أقل من أمس
                        </p>
                    </div>
                </div>





                <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md">
                    <div
                        class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-green-600 to-green-400 text-white shadow-green-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                            <path fill-rule="evenodd"
                                d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM4.5 9.75A.75.75 0 0 1 5.25 9h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V9.75Z"
                                clip-rule="evenodd" />
                            <path
                                d="M2.25 18a.75.75 0 0 0 0 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 0 0-.75-.75H2.25Z" />
                        </svg>


                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans text-sm leading-normal font-bold text-blue-gray-600">
                            إجمالي البيع اليوم
                        </p>
                        <h4 v-if="statistics.todaySales >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatNumber(statistics.todaySales) }} درهم
                        </h4>
                        <h4 v-else
                            class="antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p v-if="statistics.salesPercentageChange >= 0"
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.salesPercentageChange >= 0" class="text-green-500">+{{
                                statistics.salesPercentageChange }}%</strong>
                            <strong v-else class="text-green-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>
                            &nbsp;أكثر من أمس
                        </p>
                        <p v-else
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.salesPercentageChange <= 0" class="text-red-500">{{
                                statistics.salesPercentageChange }}%</strong>
                            <strong v-else class="text-red-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>
                            &nbsp;أقل من أمس
                        </p>
                    </div>
                </div>






                <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md">
                    <div
                        class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-orange-600 to-orange-400 text-white shadow-orange-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            aria-hidden="true" class="w-6 h-6 text-white">
                            <path
                                d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z">
                            </path>
                        </svg>
                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans text-sm leading-normal font-bold text-blue-gray-600">
                            إجمالي البيع لهذا الشهر
                        </p>
                        <h4 v-if="statistics.monthSales >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{
                                common.formatNumber(Math.floor(statistics.monthSales))
                            }}
                            درهم
                        </h4>
                        <h4 v-else
                            class="antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p v-if="statistics.monthSalesPercentageChange >= 0"
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.monthSalesPercentageChange >= 0" class="text-green-500">+{{
                                statistics.monthSalesPercentageChange }}%</strong>
                            <strong v-else class="text-green-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>
                            &nbsp;أكثر من الشهر الماضي
                        </p>
                        <p v-else
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.monthSalesPercentageChange <= 0" class="text-red-500">{{
                                statistics.monthSalesPercentageChange }}%</strong>
                            <strong v-else class="text-red-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>
                            &nbsp;أقل من الشهر الماضي
                        </p>
                    </div>
                </div>

            </div>

        </div>

        <div class="pt-6 px-4">

            <div class="w-full grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-4 gap-4">
                <!-- here the place of it -->
                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
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
</div>

<div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
    <div v-if="weeklySales.labels && weeklySales.data && Object.keys(statistics).length">
        <div class="flex items-center justify-between mb-4">
            <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{
                    common.formatNumber(statistics.thisWeekSalesAmount) }} درهم</span>
                <h3 v-if="Object.keys(statistics).length" class="text-base font-normal text-gray-500">
                    مبيعات هذا الأسبوع
                </h3>
                <Skeleton v-else width="15rem"></Skeleton>
            </div>
            <div v-if="statistics.salesWeekChange >= 0"
                class="flex items-center justify-end flex-1 text-green-500 text-base font-bold">
                {{ statistics.salesWeekChange }}%
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
            <div v-else class="flex items-center justify-end flex-1 text-red-500 text-base font-bold">
                {{ statistics.salesWeekChange }}%
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 17.25 12 21m0 0-3.75-3.75M12 21V3" />
                </svg>
            </div>
        </div>
        <div id="main-chart" class="flex items-center justify-center">
            <Bar id="my-chart-id" :data="{
                labels: weeklySales.labels,
                datasets: [
                    {
                        label: 'المبيعات اليومية لكل أسبوع',
                        backgroundColor: monthlySales.colors,
                        borderColor: monthlySales.borderColors,
                        borderWidth: 1,
                        data: weeklySales.data,
                    },
                ],
            }" :options="options" class="h-[25rem]" />
        </div>
    </div>
    <div v-else>
        <div class="flex justify-between mb-4 items-center">
            <div>
                <Skeleton width="12rem" class="mb-2"></Skeleton>
                <Skeleton width="6rem" class="mb-2"></Skeleton>
            </div>
            <Skeleton width="6rem" class="mb-2"></Skeleton>
        </div>
        <Skeleton height="25rem"></Skeleton>
    </div>
</div>

<div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
    <div class="mb-4 flex items-center justify-between ">
        <div class="w-full text-right">
            <h3 v-if="Object.keys(stockStatistics).length" class="text-xl font-bold text-gray-900  ">
                تحليلات المخزون
            </h3>
            <Skeleton v-else width="15rem"></Skeleton>
        </div>
    </div>
    <div class="flex flex-col mt-3 items-center justify-center w-full">
        <div class="overflow-x-auto rounded-lg w-full">
            <div class="align-middle inline-block w-full">
                <div class="shadow overflow-hidden sm:rounded-lg w-full">
                    <Bar v-if="stockStatistics.labels && stockStatistics.datasets" id="my-chart-id"
                        :data="{
                            labels: stockStatistics.labels,
                            datasets: stockStatistics.datasets,
                        }" :options="options" class="h-[25rem]" />
                    <Skeleton v-else height="25rem"></Skeleton>
                </div>
            </div>
        </div>
    </div>
</div>


            </div>

        </div>

        <div class="grid grid-cols-1 2xl:grid-cols-2 xl:gap-4 my-12 gap-5 p-4">
            <!-- قسم العملاء الجدد -->
            <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
                <div class="mb-4 flex items-center justify-between">

                    <div class="flex-shrink-0">
                        <router-link :to="{ name: 'clients' }"
                            class="text-sm font-medium text-cyan-600 hover:bg-gray-100 rounded-lg p-2">عرض
                            الكل</router-link>
                    </div>
                    <div class="text-right">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">أحدث العملاء</h3>
                        <span class="text-base font-normal text-gray-500">هذه قائمة بأحدث العملاء</span>
                    </div>
                </div>
                <div class="flow-root">
                    <ul v-if="clients && clients.length > 0">
                        <li class="py-3 sm:py-4" v-for="client in clients" :key="client.id">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <avatar :fullname="client.name" :size="40"></avatar>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ client.name }}</p>
                                    <p class="text-sm text-gray-500 truncate">
                                        <a href="tel:{{ client.phone }}">{{ client.phone }}</a>
                                    </p>

                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                    {{ client.cni ?? '-' }}
                                </div>
                            </div>
                        </li>
                        <div v-if="clients.length === 0" class="text-center w-full text-sm text-red-600 py-4">لا يوجد
                            عملاء جدد.</div>
                    </ul>
                    <ul v-else>
                        <li class="mb-3" v-for="(i, index) in new Array(7)" :key="index">
                            <div class="flex">
                                <Skeleton shape="circle" size="4rem" class="mr-2"></Skeleton>
                                <div class="align-self-center mt-2" style="flex: 1">
                                    <Skeleton width="16%" class="mb-2"></Skeleton>
                                    <Skeleton width="30%"></Skeleton>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- قسم المبيعات الجديدة -->
            <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8">
                <div class="mb-4 flex items-center justify-between">

                    <router-link :to="{ name: 'sells' }" class="flex-shrink-0">
                        <span class="text-sm font-medium text-cyan-600 hover:bg-gray-100 rounded-lg p-2">عرض الكل</span>
                    </router-link>
                    <div class="text-right">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">أحدث المبيعات</h3>
                        <span class="text-base font-normal text-gray-500">هذه قائمة بأحدث المبيعات</span>
                    </div>
                </div>
                <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                        <div class="align-middle inline-block min-w-full">
                            <div class="shadow overflow-hidden sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 text-center">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                الحالة</th>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                التاريخ والوقت</th>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                المبلغ</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="sells && sells.length" class="bg-white">
                                        <tr v-for="sell in sells" :key="sell.id">
                                            <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-900">
                                                <span :class="[
                                                    sell.status === 'مدفوع' ? 'bg-green-500' :
                                                        (sell.status === 'متبقي' ? 'bg-orange-500' : 'bg-red-500'),
                                                    'text-xs', 'text-white', 'rounded-md', 'text-center', 'px-1.5', 'py-1'
                                                ]">{{ sell.status }}</span>
                                            </td>
                                            <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">{{
                                                common.formatDate(sell.created_at) }}</td>
                                            <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{
                                                common.formatNumber(sell.total_price) + " درهم" }}</td>
                                        </tr>
                                        <tr v-if="sells.length === 0">
                                            <td colspan="3" class="py-3 text-red-600 text-sm">لا توجد مبيعات حتى الآن.
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr v-for="(sell, index) in new Array(8)" :key="index">
                                            <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-900">
                                                <Skeleton></Skeleton>
                                            </td>
                                            <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                                                <Skeleton></Skeleton>
                                            </td>
                                            <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                <Skeleton></Skeleton>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
const monthlyRemaining = ref({});
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
const stockStatistics = ref({});
const monthlySales = ref({});
onMounted(() => {

    fetchMonthlySales();
     fetchWeeklySales();
    getLastClients();
    getLastSells();
    fetchDashboardDetails();

    getMonthlyRemaining();
         fetchStockAnalytics();

});

const statistics = ref({});


function getMonthlyRemaining() {
    store.dispatch("fetchMonthlySales").then((res) => {
        monthlyRemaining.value = res;
    });
}
function fetchStockAnalytics() {
    store.dispatch("getStockStatistics").then((res) => {
        stockStatistics.value = res;
    });
}

function fetchWeeklySales() {
    store.dispatch("getWeeklySales").then((res) => {
        weeklySales.value = res;
    });
}

function fetchMonthlySales() {
    store.dispatch("getMonthlySales").then((res) => {
        monthlySales.value = res;
    });
}

function fetchDashboardDetails() {
    store.dispatch("getDashboardData").then((res) => {
        statistics.value = res;
        console.log(statistics.value)
    });
}

function getLastClients() {
    store.dispatch("fetchLastClients").then((res) => {
        clients.value = res;
    });
}

function getLastSells() {
    store.dispatch("fetchLastSells").then((res) => {
        sells.value = res;
    });
}
</script>
