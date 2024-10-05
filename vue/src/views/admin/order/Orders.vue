<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
            List of orders
        </h2>
        <!-- Main Data Table -->
        <div v-if="!loading">
            <DataTable :value="filteredOrders" removableSort :paginator="true"
                class="p-datatable-sites -mt-5 m-6 rounded-md" showGridlines :rows="10" dataKey="id"
                tableStyle="min-width: 30rem" stripedRows :rowClassName="'border-t text-center border-gray-200'"
                filterDisplay="menu" :loading="loading" responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} orders">
                <template #header>
                    <div class="flex flex-col flex-wrap md:flex-row md:justify-between w-full md:items-center gap-5">
                        <div class="relative mb-2 md:mb-0 md:mr-5">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600"></i>
                            <InputText @input="filterTable($event)" placeholder="Search" id="searchInput"
                                class="pl-10 py-2 border-gray-300" />
                        </div>

                        <div class="flex flex-col md:flex-row gap-5">
                            <input v-model="date.start_date" type="date"
                                class="xl:w-[12rem] p-2 border-gray-300  focus:border-blue-500" />
                            <input v-model="date.end_date" type="date"
                                class="p-2 xl:w-[12rem] focus:border-blue-500 border-gray-300 " />

                            <Button type="button" icon="pi pi-filter-slash" label="Filter"
                                class="p-button-outlined bg-white py-2 px-4 border border-green-500 rounded-md text-green-800 hover:text-white hover:bg-green-500"
                                @click="filterOrders()" />
                        </div>

                        <Button type="button" icon="pi pi-filter-slash" label="Clear"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500 mt-2 md:mt-0"
                            @click="clearFilter()" />
                    </div>
                </template>

                <template #empty>No orders list found.</template>
                <!-- Columns -->
                <Column field="id" header="ID" class="border-b-[1px] text-center"></Column>
                <Column field="user" header="User Name" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span>{{ data.customer.name }}</span>
                    </template>
                </Column>

                <Column field="payment.payment_method" class="border-b-[1px] text-center" header="Methode de paiement">
                    <template #body="{ data }">
                        <span v-if="data.payment">{{ data.payment.payment_method }}</span>
                    </template>
                </Column>

                <Column field="status" class="border-b-[1px] text-center" header="Status">
                    <template #body="{ data }">
                        <span class="rounded-md px-1.5 py-1 text-gray-200 text-xs " :class="[
                            data.status === 'canceled'
                                ? 'bg-red-500'
                                : data.status === 'processing'
                                    ? 'bg-orange-500'
                                    :( data.status === 'returned'
                                        ? 'bg-blue-500'
                                        : (data.status==='completed' ?
                                            'bg-green-500' : 'bg-indigo-500'
                                        )),
                        ]">{{ data.status }}
                        </span>
                    </template>
                </Column>

                <Column field="amount" class="border-b-[1px] text-center" header="Total">
                    <template #body="{ data }">
                        <span>{{ common.formatNumber(data.amount) }} DH</span>
                    </template>
                </Column>
                <Column field="created_at" class="border-b-[1px] text-center" header="creer a">
                    <template #body="{ data }">
                        <span>{{ common.formatDate(data.created_at) }}</span>
                    </template>
                </Column>

                <Column header="Actions" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <div class="flex gap-3">
                            <button @click="showOrder(data.id)" title="Show this order"
                                class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                            <button @click="showOrder(data.id)" title="download it as invoice"
                                class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-green-500">
  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
</svg>
                            </button>


                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Skeleton Data Table -->
        <div v-else>
            <DataTable :value="skeletonObjects" class="p-datatable-sites -mt-5 m-6" tableStyle="min-width: 30rem"
                :rowClassName="'border-t text-center border-gray-200'">
                <template #header>
                    <div class="flex justify-between items-center gap-5" style="display: flex">
                        <span class="relative">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600" />
                            <InputText placeholder="Search" class="pl-10 py-2" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="Clear"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500" />
                    </div>
                </template>
                <Column field="id" header="ID" class="text-center">
                    <template #body="">
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="user" header="User Name" class="text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="payment.payment_method" class="text-center" header="Methode de paiement">
                    <template #body="">
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column sortable field="status" class="text-center" header="Status">
                    <template #body="">
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="amount" class="text-center" header="Total">
                    <template #body="">
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="created_at" class="text-center" header="creer a">
                    <template #body="">
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" class="text-center" header="Actions">
                    <template #body="">
                        <Skeleton></Skeleton>
                    </template>
                </Column>
            </DataTable>
        </div>
        <Toast />
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import store from "../../../store";
import { useRouter } from "vue-router";
import { useToast } from "primevue/usetoast";
import common from "../../../utils/common";
const loading = ref(true);
const orders = ref([]);
const router = useRouter();
const date = ref({});
const toast = useToast();
const filteredOrders = ref([]);
onMounted(() => {
    fetchOrders();
});
function filterOrders() {
    store.dispatch("filterByDates", { date: date.value, type: "order" }).then((res) => {

        if (res.status === 200 && res.data)
            filteredOrders.value = [...res.data];
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
    }).catch(error => error);
}
function fetchOrders() {
    store
        .dispatch("getOrders")
        .then((res) => {
            orders.value = res;
            console.log(res);
            filteredOrders.value = [...orders.value];
        })
        .catch((error) => console.error(error))
        .finally(() => {
            loading.value = false;
        });
}
function clearFilter() {
    document.getElementById("searchInput").value = "";
    date.value = {};
    filteredOrders.value = orders.value;
}
function showOrder(id) {
    // Implement the addToBlacklist function
    router.push({ name: "order-details", params: { id: id } });
}
function deleteOrder(id) {
    common
        .showSwal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log("Product deleted:", id);
                common.showSwal({
                    title: "Success!",
                    text: "Your product has been deleted successfully.",
                    icon: "success",
                    showConfirmButton: false,
                });
            }
        });
}
function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredOrders.value = orders.value;
    else
        filteredOrders.value = orders.value.filter(
            (o) =>
                o.status.toLowerCase().includes(filter) ||
                o.customer.name.toLowerCase().includes(filter)
        );
}
// Define skeleton data
const skeletonObjects = new Array(10);
</script>
