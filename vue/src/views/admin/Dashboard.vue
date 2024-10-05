<template>
    <div>
        <div class="m-10 pt-10">
            <div class="mb-813 grid gap-y-10 gap-x-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md">
                    <div
                        class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-blue-600 to-blue-400 text-white shadow-blue-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            aria-hidden="true" class="w-6 h-6 text-white">
                            <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"></path>
                            <path fill-rule="evenodd"
                                d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z"
                                clip-rule="evenodd"></path>
                            <path
                                d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z">
                            </path>
                        </svg>
                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans text-sm leading-normal font-bold text-blue-gray-600">
                            إجمالي الربح
                        </p>
                        <h4 v-if="statistics.todayMoney >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatNumber(Math.floor(statistics.todayMoney)) }} DH
                        </h4>
                        <h4 v-else
                            class=" antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p v-if="statistics.moneyChange >= 0"
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.moneyChange" class="text-green-500">+{{ statistics.moneyChange
                                }}%</strong><strong v-else class="text-green-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>
                            &nbsp;than last week
                        </p>
                        <p v-else
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.moneyChange" class="text-red-500">{{ statistics.moneyChange
                                }}%</strong><strong v-else class="text-red-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>&nbsp;than last week
                        </p>
                    </div>
                </div>

                <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md">
                    <div
                        class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-pink-600 to-pink-400 text-white shadow-pink-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            aria-hidden="true" class="w-6 h-6 text-white">
                            <path fill-rule="evenodd"
                                d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans font-bold text-sm leading-normal text-blue-gray-600">
                            إجمالي الدين
                        </p>

                        <h4 v-if="statistics.todaysUsers >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatNumber(statistics.todaysUsers) }}
                        </h4>
                        <h4 v-else
                            class=" antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p v-if="statistics.usersChange >= 0"
                            class="flex items-center  antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.usersChange" class="text-green-500">+{{ statistics.usersChange
                                }}%</strong>
                            <strong v-else class="text-green-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>
                            &nbsp;than last month
                        </p>
                        <p v-else
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.usersChange" class="text-red-500">{{ statistics.usersChange
                                }}%</strong>
                            <strong v-else class="text-red-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>&nbsp;than last month
                        </p>
                    </div>
                </div>
                <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md">
                    <div
                        class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-green-600 to-green-400 text-white shadow-green-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            aria-hidden="true" class="w-6 h-6 text-white">
                            <path
                                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
                            </path>
                        </svg>
                    </div>
                    <div class="p-4 text-right">
                        <p class="block antialiased font-sans text-sm leading-normal font-bold text-blue-gray-600">
                            إجمالي البيع
                        </p>
                        <h4 v-if="statistics.newClients >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{ common.formatNumber(statistics.newClients) }}
                        </h4>
                        <h4 v-else
                            class=" antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>
                    </div>
                    <div class="border-t border-blue-gray-50 p-4">
                        <p v-if="statistics.newClientsChange >= 0"
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.newClientsChange" class="text-green-500">+{{
                                statistics.newClientsChange }}%</strong>
                            <strong v-else class="text-green-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>

                            &nbsp;than last month
                        </p>
                        <p v-else
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.newClientsChange" class="text-red-500">{{
                                statistics.newClientsChange }}%</strong>
                            <strong v-else class="text-red-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>&nbsp;than last month
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
                            عدد البائعين
                        </p>
                        <h4 v-if="statistics.todaysSalesAmount >= 0"
                            class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            {{
                            common.formatNumber(Math.floor(statistics.todaysSalesAmount))
                            }}
                            DH
                        </h4>
                        <h4 v-else
                            class=" antialiased flex items-center justify-end mt-3 tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900">
                            <Skeleton width="5rem"></Skeleton>
                        </h4>

                    </div>
                    <div class="border-t border-blue-gray-50 p-4">

                        <p v-if="statistics.salesAmountChange >= 0"
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.salesAmountChange" class="text-green-500">+{{
                                statistics.salesAmountChange }}%</strong>
                            <strong v-else class="text-green-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>

                            &nbsp;than last month
                        </p>
                        <p v-else
                            class="flex items-center antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
                            <strong v-if="statistics.salesAmountChange" class="text-red-500">{{
                                statistics.salesAmountChange }}%</strong>
                            <strong v-else class="text-red-500 mr-1.5">
                                <Skeleton width="3rem"></Skeleton>
                            </strong>&nbsp;than last month
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
                        <div>
                            <h3 v-if="Object.keys(monthlySales).length" class="text-xl font-bold text-gray-900">
                                Sales for each month
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
                                                label: 'Monthly Sales',
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
                        <div>
                            <h3 v-if="Object.keys(thisMonth).length" class="text-xl font-bold text-gray-900">
                                Sales for this month
                            </h3>
                            <Skeleton v-else width="15rem"></Skeleton>
                        </div>
                    </div>
                    <div class="flex flex-col mt-3 items-center justify-center w-full">
                        <div class="overflow-x-auto rounded-lg w-full">
                            <div class="align-middle inline-block w-full">
                                <div class="shadow overflow-hidden sm:rounded-lg w-full">
                                    <Bar v-if="thisMonth.labels && thisMonth.data" id="my-chart-id" :data="{
                                        labels: thisMonth.labels,
                                        datasets: [
                                            {
                                                label: 'Monthly Sales',
                                                backgroundColor: thisMonth.colors,
                                                borderColor: thisMonth.borderColors,
                                                borderWidth: 1,
                                                data: thisMonth.data,
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
                        <div>
                            <h3 v-if="Object.keys(thisMonth).length" class="text-xl font-bold text-gray-900 ">
                                User Analytics
                            </h3>
                            <Skeleton v-else width="15rem"></Skeleton>
                        </div>
                    </div>
                    <div class="flex flex-col mt-3 items-center justify-center w-full">
                        <div class="overflow-x-auto rounded-lg w-full">
                            <div class="align-middle inline-block w-full">
                                <div class="shadow overflow-hidden sm:rounded-lg w-full">
                                    <Line v-if="userRegistrations.data && userRegistrations.labels" id="my-chart-id"
                                        :data="{
                                        labels: userRegistrations.labels,
                                        datasets: [
                                            {
                                                label: 'User Rates Per Month',
                                                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Example background color
                                                borderColor: 'rgba(75, 192, 192, 1)', // Example border color
                                                borderWidth: 1,
                                                data: userRegistrations.data,
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
                        <div>
                            <h3 v-if="Object.keys(stockStatistics).length" class="text-xl font-bold text-gray-900 ">
                                Stock Analytics
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



                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
                    <div v-if="orderStatus.data && orderStatus.labels">
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <h3 v-if="Object.keys(orderStatus).length" class="text-xl font-bold text-gray-900 ">
                                    Status of all orders
                                </h3>
                                <Skeleton v-else width="15rem"></Skeleton>

                            </div>
                        </div>
                        <div class="flex flex-col mt-3 items-center justify-center">
                            <div class="overflow-x-auto">
                                <div class="align-middle inline-block w-full">
                                    <div class="shadow overflow-hidden w-full">
                                        <div>
                                            <Doughnut
                                                v-if="orderStatus.labels && orderStatus.data && orderStatus.backgroundColor"
                                                :data="{
                                                    labels: orderStatus.labels,
                                                    datasets: [{
                                                        data: orderStatus.data,
                                                        backgroundColor: orderStatus.backgroundColor,
                                                        hoverOffset: 4
                                                    }]
                                                }" :options="{ responsive: true }" class="h-[25rem]" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <Skeleton width="15rem" class="mb-12"></Skeleton>

                        <div class="flex flex-col mt-2 items-center justify-center">
                            <Skeleton width="20rem" class="mb-5"></Skeleton>

                            <Skeleton width="21rem" shape="circle" height="20rem"></Skeleton>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 2xl:col-span-2">
                    <div v-if="weeklySales.labels && weeklySales.data && Object.keys(statistics).length">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex-shrink-0">
                                <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{
                                    common.formatNumber(statistics.thisWeekSalesAmount) }} DH</span>
                                <h3 v-if="Object.keys(statistics).length" class="text-base font-normal text-gray-500">
                                    Sales this week
                                </h3>
                                <Skeleton v-else width="15rem"></Skeleton>

                            </div>
                            <div v-if="statistics.salesWeekChange >= 0"
                                class="flex items-center justify-end flex-1 text-green-500 text-base font-bold">
                                {{ statistics.salesWeekChange }}%
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div v-else class="flex items-center justify-end flex-1 text-red-500 text-base font-bold">
                                {{ statistics.salesWeekChange }}%
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
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
                                        label: 'Daily Sales Per Week',
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
            </div>
        </div>

        <div class="grid grid-cols-1 2xl:grid-cols-2 xl:gap-4 my-12 gap-5 p-4">
            <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
                <div v-if="users.length >= 0" class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            Latest Customers
                        </h3>
                        <span class="text-base font-normal text-gray-500">This is a list of latest Customers</span>
                    </div>

                    <div class="flex-shrink-0">
                        <router-link :to="{ name: 'users' }"
                            class="text-sm font-medium text-cyan-600 hover:bg-gray-100 rounded-lg p-2">View
                            all</router-link>
                    </div>
                </div>
                <div v-else class="flex justify-between mb-4 items-center">
                    <div>
                        <Skeleton width="16rem" class="mb-2"></Skeleton>
                        <Skeleton width="10rem" class="mb-2"></Skeleton>
                    </div>
                    <Skeleton width="4rem" class="mb-2"></Skeleton>
                </div>
                <div class="flow-root">
                    <ul v-if="users.length >= 0">
                        <div v-if="users.length">
                            <li class="py-3 sm:py-4" v-for="user in users" :key="user.id">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img class="h-8 w-8 rounded-full" :src="user.avatar" alt="User avatar" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ user.name }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            <a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                                data-cfemail="17727a767e7b57607e7973646372653974787a">
                                                {{ user.email }}</a>
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                        {{
                                        user.orders.length
                                        ? common.formatNumber(user.orders[0].amount)
                                        : 0
                                        }}
                                        DH
                                    </div>
                                </div>
                            </li>
                        </div>
                        <div v-else class="text-center w-full text-sm text-red-600 py-4">No new customers have placed
                            orders recently.</div>
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

            <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Latest Orders</h3>
                        <span class="text-base font-normal text-gray-500">This is a list of latest Orders</span>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="#" class="text-sm font-medium text-cyan-600 hover:bg-gray-100 rounded-lg p-2">View
                            all</a>
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
                                                status
                                            </th>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date & Time
                                            </th>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Amount
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="!loading" class="bg-white">

                                        <tr v-for="order in orders" :key="order.id">
                                            <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-900">
                                                <span class="rounded-md p-1.5 text-gray-200 text-xs" :class="[
                                                    order.status === 'canceled'
                                                        ? 'bg-red-500'
                                                        : order.status === 'processing'
                                                            ? 'bg-orange-500'
                                                            : order.status === 'returned'
                                                                ? 'bg-blue-500'
                                                                : 'bg-green-500',
                                                ]">
                                                    {{ order.status }}</span>
                                            </td>
                                            <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                                                {{ common.formatDate(order.created_at) }}
                                            </td>
                                            <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {{ common.formatNumber(order.amount) + " DH" }}
                                            </td>
                                        </tr>



                                        <tr v-if="orders.length === 0">
                                            <td colspan="3" class="py-3 text-red-600 text-sm">No orders have been placed
                                                yet.</td>
                                        </tr>



                                    </tbody>
                                    <tbody v-else>
                                        <tr v-for="(order, index) in new Array(8)" :key="index">
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
const users = ref({});
const orderStatus = ref({});
const orders = ref({});
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
const userRegistrations = ref({});
onMounted(() => {

    fetchMonthlySales();
    fetchWeeklySales();
    fetchLastUsers();
    fetchLastOrders();
    fetchDashboardDetails();
    fetchOrderStatus();
    fetchThisMonthSales();
    fetchUserRegistration();
    fetchStockAnalytics();
});

const statistics = ref({});


function fetchThisMonthSales() {
    store.dispatch("getThisMonthSales").then((res) => {
        thisMonth.value = res;
    });
}
function fetchStockAnalytics() {
    store.dispatch("getStockStatistics").then((res) => {
        stockStatistics.value = res;
    });
}
function fetchUserRegistration() {
    store.dispatch("getUserRegistrations").then((res) => {
        userRegistrations.value = res;
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
function fetchOrderStatus() {
    store.dispatch("getOrdersStatus").then((res) => {
        orderStatus.value = res;
        loading.value = false;
    });
}
function fetchDashboardDetails() {
    store.dispatch("getDashboardData").then((res) => {
        statistics.value = res;
    });
}

function fetchLastUsers() {
    store.dispatch("getlastUsers").then((res) => {
        users.value = res;
    });
}

function fetchLastOrders() {
    store.dispatch("getLastOrders").then((res) => {
        orders.value = res;
    });
}
</script>
