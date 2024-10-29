<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <Button
            :disabled="loading"
                class="text-sm text-white -mt-5 font-bold py-2 px-4 ml-6 rounded-xl bg-green-600 hover:bg-green-700"
                @click="visible = true"
            >
                إضافة مصروف
            </Button>
            <p class="mb-4 text-2xl flex font-extrabold p-8">
                <span class="xl:text-2xl text-xl font-bold text-red-800"
                    >-{{ common.formatNumber(total) }} DH</span
                >
                <span class="ml-1"> : مجموع المصاريف</span>
            </p>
        </div>
        <!-- Main Data Table -->
        <div v-if="!loading">
            <DataTable
                :value="filteredExpenses"
                removableSort
                :paginator="true"
                class="p-datatable-sites -mt-5 m-6"
                showGridlines
                :rows="10"
                dataKey="id"
                tableStyle="min-width: 30rem"
                stripedRows
                :rowClassName="'border-t text-center border-gray-200'"
                filterDisplay="menu"
                :loading="loading"
                responsiveLayout="scroll"
                breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="عرض {first} إلى {last} من أصل {totalRecords} المصاريف"
            >
                <template #header>
                    <div
                        class="flex justify-between items-center gap-5"
                        style="display: flex"
                    >
                        <span class="relative">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600"
                            />
                            <InputText
                                @input="filterTable($event)"
                                placeholder="Search"
                                id="searchInput"
                                class="pl-10 py-2 border-gray-300"
                            />
                        </span>
                        <Button
                            type="button"
                            icon="pi pi-filter-slash"
                            label="إزالة"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                            @click="clearFilter()"
                        />
                    </div>
                </template>
                <template #empty>لا توجد مصاريف</template>
                <!-- Columns -->
                <Column
                    field="name"
                    header="إسم الرسم"
                    class="border-b-[1px] text-center"
                >
                </Column>
                <Column
                    sortable
                    field="amount"
                    header="المبلغ"
                    class="border-b-[1px] text-center"
                >
                    <template #body="{ data }">
                        <span>
                            {{ common.formatNumber(data.amount) }} درهم</span
                        >
                    </template>
                </Column>
                <Column
                    field="created_at"
                    header="تمت إضافته في"
                    class="border-b-[1px] text-center"
                    sortable
                >
                    <template #body="{ data }">
                        <span> {{ common.formatDate(data.created_at) }} </span>
                    </template>
                </Column>
                <Column header="إجراءات" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <div class="flex gap-3">
                            <button
                                title="Delete this product"
                                @click="deleteExpense(data.id)"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-6 h-6 text-red-500"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                    />
                                </svg>
                            </button>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Skeleton Data Table -->
        <div v-else>
            <DataTable
                :value="skeletonObjects"
                class="p-datatable-sites -mt-5 m-6"
                tableStyle="min-width: 30rem"
                :rowClassName="'border-t text-center border-gray-200'"
            >
                <template #header>
                    <div
                        class="flex justify-between items-center gap-5"
                        style="display: flex"
                    >
                        <span class="relative">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600"
                            />
                            <InputText
                                placeholder="Search"
                                class="pl-10 py-2"
                                disabled
                            />
                        </span>
                        <Button
                            type="button"
                            icon="pi pi-filter-slash"
                            label="Clear"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                        />
                    </div>
                </template>

                <Column
                    field=""
                    header="إسم الرسم"
                    style="min-width: 12rem"
                    class="text-center flex items-center justify-center border-b-2"
                >
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column
                    field=""
                    header=" المبلغ"
                    class="text-center border-b-2"
                >
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column
                    field=""
                    header="تمت إضافته في"
                    class="text-center border-b-2"
                >
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column
                    field=""
                    header="إجراءات"
                    class="text-center border-b-2"
                >
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog
            v-model:visible="visible"
            modal
            header="إضافة مصروف"
            class="w-[50%]"
        >
            <div class="w-full">
                <div class="relative z-0 w-full mb-5 group">
                    <label
                        for="name"
                        class="block mb-2 text-sm font-medium text-gray-500"
                        >إسم الرسم</label
                    >
                    <input
                        type="text"
                        id="name"
                        v-model="expense.name"
                        class="block py-2 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder="الإنترنت"
                    />
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <label
                        for="amount"
                        class="block mb-2 text-sm font-medium text-gray-500"
                        >المبلغ</label
                    >
                    <input
                        type="text"
                        id="amount"
                        v-model="expense.amount"
                        class="block py-2 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder="0.0000"
                    />
                </div>

                <div class="w-full">
                    <Buton
                        :loading="loaderButton"
                        color="green"
                        @trigger-event="addExpense"
                        string="إضافة"
                    />
                </div>
            </div>
        </Dialog>
        <Toast />
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import store from "../../store";
import common from "../../utils/common";
import Buton from "../../components/Button.vue";
import { useToast } from "primevue/usetoast";

const expenses = computed(() => store.state.expenses);
const filteredExpenses = ref([]);
const visible = ref(false);
const toast = useToast();
const loaderButton = ref(false);
const expense = ref({});
const skeletonObjects = new Array(10);

const loading = ref(true);
const total = computed(() =>
    filteredExpenses.value?.reduce((acc, curr) => acc + parseFloat(curr.amount), 0)
);
onMounted(() => {
    getExpenses();
});
function addExpense() {
    loaderButton.value = true;
    store
        .dispatch("storeExpense", expense.value)
        .then((res) => {
            if (res && res.status === 200 && res.data) {
                filteredExpenses.value = [
                    res.data.expense,
                    ...filteredExpenses.value
                ].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                store.commit("SET_EXPENSES",filteredExpenses.value);
                expense.value = {};
                visible.value = false;
                console.log("New total:", total.value); // Debugging total value

                common.showToast({ title: res.data.message, icon: "success" });
            } else common.showValidationErrors(res, toast);
        })
        .finally(() => (loaderButton.value = false));
}

function deleteExpense(id) {
    common
        .showSwal({
            title: "هل أنت متأكد؟",
            text: "! لن تتمكن من التراجع عن هذا",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "! نعم، احذفها",
        })
        .then((result) => {
            if (result.isConfirmed) {
                document.body.style.cursor = "wait";

                store
                    .dispatch("destroyExpense", id)
                    .then((res) => {
                        console.log(res.data.message);

                        if (res && res.status === 200 && res.data) {
                            filteredExpenses.value =
                                filteredExpenses.value.filter(
                                    (e) => e.id !== id
                                );
                                store.commit("SET_EXPENSES",filteredExpenses.value);
                                common.showToast({
                                title: res.data.message,
                                icon: "success",
                            });
                            document.body.style.cursor = "default";
                        }
                    })
                    .catch((error) => error)
                    .finally(() => {
                        loading.value = false;
                    });
            }
        });
}

function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredExpenses.value = products.value;
}

function getExpenses() {
    store
        .dispatch("fetchExpenses")
        .then((res) => filteredExpenses.value  = expenses.value)
        .catch(error => error)
        .finally(() => (loading.value = false));
}

function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredExpenses.value = expenses.value;
    else
        filteredExpenses.value = expenses.value.filter((p) =>
            p.name.toLowerCase().includes(filter)
        );
}
</script>
