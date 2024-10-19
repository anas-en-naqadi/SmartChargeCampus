<template>
    <div class="card mx-5 mt-12 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                لائحة الزبناء
            </h2>

        </div>
        <!-- Main Data Table -->
        <div v-if="!loading">
            <DataTable @rowExpand="totalOwn" :value="filteredClients" v-model:expandedRows="expandedRows" removableSort
                :paginator="true" class="p-datatable-sites -mt-5 m-6" showGridlines :rows="10" dataKey="id"
                tableStyle="min-width: 30rem" stripedRows responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="عرض {first} إلى {last} من {totalRecords} الزبناء">
                <template #header>
                    <div class="flex justify-between items-center gap-5" style="display: flex">
                        <span class="relative">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600" />
                            <InputText @input="filterTable($event)" placeholder="Search" id="searchInput"
                                class="pl-10 py-2 border-gray-300" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="إزالة"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                            @click="clearFilter()" />
                    </div>
                </template>
                <template #empty>لا يوجد عملاء</template>
                <Column expander style="width: 5rem" />


                <Column field="name" headerStyle="" class="border-b-[1px] text-center" header="إسم الزبون">
                </Column>
                <Column field="cni" class="border-b-[1px] w-[5rem] text-center" header="رقم بطاقة الهوية ">
                </Column>
                <Column field="phone" class="border-b-[1px] w-[5rem] text-center" header="رقم الهاتف">
                </Column>

                <Column field="created_at" class="border-b-[1px] text-center w-full m-auto " header="أضيف في" sortable>
                    <template #body="{ data }">
                        <span>{{ common.formatDate(data.created_at) }}</span>
                    </template>
                </Column>

                <template #expansion="{ data }">
                    <div class="p-2.5">

                        <div :class="[
                            data.sells.length ? 'mb-4' : '-mb-10',
                            'flex',
                            'justify-between',
                            'items-center',
                            '-mt-6',

                        ]">

                            <p class="mb-4 text-2xl flex font-extrabold p-8">


                                <span class="text-2xl font-bold text-blue-800">{{ common.formatNumber(data.total_credit)
                                    }}
                                    درهم</span>
                                <span class="ml-1"> : إجمالي الدين</span>
                            </p>
                            <p class="mb-4 text-2xl flex font-extrabold p-8">


                                <span class="text-2xl font-bold text-blue-800">{{ common.formatNumber(TotalBought) }}
                                    درهم</span>
                                <span class="ml-1">: مجموع الحساب
                                </span>
                            </p>
                        </div>
                        <DataTable v-if="data.sells.length" :value="data.sells" removableSort :paginator="true"
                            class="p-datatable-sites -mt-5 m-6 " showGridlines :rows="10" dataKey="id"
                            tableStyle="min-width: 30rem;" stripedRows
                            :rowClassName="'border-t text-center border-gray-200'" filterDisplay="menu"
                            responsiveLayout="scroll" breakpoint="960px"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            currentPageReportTemplate="عرض {first} إلى {last} من أصل {totalRecords} مبيعات">
                            <template #header>
                                <div
                                    class="flex flex-col flex-wrap md:flex-row md:justify-between w-full md:items-center gap-5">


                                    <div class="flex flex-col md:flex-row gap-5">
                                        <input v-model="date.start_date" type="date"
                                            class="xl:w-[12rem] p-2 border-gray-300  focus:border-blue-500" />
                                        <input v-model="date.end_date" type="date"
                                            class="p-2 xl:w-[12rem] focus:border-blue-500 border-gray-300 " />

                                        <Button type="button" icon="pi pi-filter-slash" label="فلترة"
                                            class="p-button-outlined bg-white py-2 px-4 border border-green-500 rounded-md text-green-800 hover:text-white hover:bg-green-500"
                                            @click="filterSells(data.id)" />
                                    </div>

                                    <Button type="button" icon="pi pi-filter-slash" label="إزالة"
                                        class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500 mt-2 md:mt-0"
                                        @click="clearFilter()" />
                                </div>
                            </template>
                            <template #empty>.لا توجد مبيعات</template>
                            <!-- Columns -->
                            <Column field="client" class="border-b-[1px] text-center" header="اسم الزبون">
                                <template #body="{ data }">
                                    <span>{{ data.client_name }}</span>
                                </template>
                            </Column>

                            <Column field="total_price" class="border-b-[1px] text-center" header="مجموع الحساب"
                                sortable>
                                <template #body="{ data }">
                                    <span>{{ common.formatNumber(data.total_price) }} درهم</span>
                                </template>
                            </Column>

                            <Column field="paid_price" class="border-b-[1px] text-center" header="الدفع" sortable>
                                <template #body="{ data }">
                                    <span>{{ common.formatNumber(data.paid_amount) }} درهم</span>
                                </template>
                            </Column>
                            <Column field="remaining_amount" class="border-b-[1px] text-center" header="الدين" sortable>
                                <template #body="{ data }">
                                    <span>{{ common.formatNumber(data.remaining_amount) }} درهم</span>
                                </template>
                            </Column>
                            <Column field="change" class="border-b-[1px] text-center" header="الصرف">
                                <template #body="{ data }">
                                    <span>{{ common.formatNumber(data.change) }} درهم</span>
                                </template>
                            </Column>
                            <Column field="status" class="border-b-[1px] text-center" header="الحالة" sortable>
                                <template #body="{ data }">
                                    <span :class="[
                                        data.status === 'مدفوع' ? 'bg-green-500' :
                                            (data.status === 'متبقي' ? 'bg-orange-500' : 'bg-red-500'),
                                        'text-sm', 'text-white', 'rounded-md', 'px-1.5', 'py-1'
                                    ]">
                                        {{ data.status }}
                                    </span> </template>
                            </Column>
                            <Column field="" class="border-b-[1px] text-center" header="طريقة الدفع" sortable>
                                <template #body="{ data }">
                                    <span
                                        :class="['text-xs text-white rounded-md p-1.5 border', data.payment_method == 'نقدًا' ? 'bg-gray-500' : 'bg-sky-500']">{{
                                        data.payment_method }}</span>
                                </template>
                            </Column>
                            <Column field="check" class="border-b-[1px] text-center" header="تاريخ دفع الشيك" sortable>
                                <template #body="{ data }">
                                    <span>{{ data.check ? common.formatDate(data.check_date) : "-" }}</span>
                                </template>
                            </Column>

                            <Column header="الطباعة" class=" border-b-[1px] text-center">
                    <template #body="{ data }">
                        <div class="flex gap-3">

                            <button title="قم بتنزيل الإيصال"
                                class="border border-blue-500 px-2 rounded-md text-blue-500"
                                @click="editProduct(data.id)">
                                Bon

                            </button>
                            <button class="border border-red-500 px-2 rounded-md text-red-500"
                                title="قم بتنزيل الفاتورة" @click="deleteProduct(data.id)">
                                Facture
                            </button>
                        </div>
                    </template>
                </Column>
                        </DataTable>
                    </div>
                </template>

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
                            <InputText disabled placeholder="Search" class="pl-10 py-2" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="إزالة"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500" />
                    </div>
                </template>
                <Column expander style="width: 5rem" />

                <Column field="name" class="border-b-[1px] w-[5rem] text-center" header="إسم الزبون">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="cni" class="border-b-[1px] w-[5rem] text-center" header="رقم بطاقة الهوية ">
                    <template #body>
                        <Skeleton width="15rem"></Skeleton>
                    </template>
                </Column>
                <Column field="phone" headerStyle="text-align:center" class="border-b-[1px] text-center"
                    header="رقم الهاتف">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="name" header="أضيف في" class="text-center flex items-center justify-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>


            </DataTable>
        </div>



        <Toast />
    </div>
</template>

<script setup>
import store from "../../store";
import { ref, onMounted, computed } from "vue";
import common from "../../utils/common";
import { useToast } from "primevue/usetoast";
const clients = computed(() => store.state.clients);
const loading = ref(false);

const filteredClients = ref([]);
const expandedRows = ref([]);

const toast = useToast();

const date = ref({});
const TotalBought = ref(0);
onMounted(() => {
    fetchClients();
});
function totalOwn(event) {
    TotalBought.value = 0
    const user = event.data;
    TotalBought.value = user.sells.reduce((acc, sell) => acc + parseFloat(sell.total_price), 0);

}
function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredClients.value = clients.value;
}
function fetchClients() {
    loading.value = true;
    store
        .dispatch("fetchClients")
        .then((res) =>
            filteredClients.value = res
        )
        .catch((error) => console.error(error))
        .finally(() =>
            loading.value = false
        );
}

function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredClients.value = clients.value;
    else
        filteredClients.value = clients.value.filter(
            (c) =>
                c.cni?.toLowerCase().includes(filter) ||
                c.name?.toLowerCase().includes(filter) ||
                c.phone?.toLowerCase().includes(filter)
        );
}



function filterSells(id) {
    document.body.style.cursor = 'wait'
    console.log(id)
    store.dispatch("filterByDates", date.value).then((res) => {
        if (res.status === 200 && res.data) {
            const index = filteredClients.value.findIndex(c => c.id === id);
            console.log("index",index  )
            if (index !== -1)
                filteredClients.value[index].sells = [...res.data];
            TotalBought.value = filteredClients.value[index].sells.reduce((acc, sell) => acc + sell.total_price, 0);
        } else
            common.showValidationErrors(res, toast);
    }).catch(error => error).finally(() => document.body.style.cursor = 'default');
}


// Define skeleton data
const skeletonObjects = new Array(10);
</script>
