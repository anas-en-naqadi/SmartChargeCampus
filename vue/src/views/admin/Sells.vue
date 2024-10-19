<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                تقرير المبيعات
            </h2>
            <p class="mb-4 text-2xl flex font-extrabold p-8">


                <span class="text-2xl font-bold text-blue-800">{{ common.formatNumber(total) }} DH</span>
                <span class="ml-1"> : مجموع المبيعات</span>
            </p>
        </div>
        <!-- Main Data Table -->

        <div v-if="!loading">
            <DataTable :value="filteredSells" removableSort :paginator="true" class="p-datatable-sites -mt-5 m-6"
                showGridlines :rows="10" dataKey="id" tableStyle="min-width: 30rem" stripedRows
                :rowClassName="'border-t text-center border-gray-200'" filterDisplay="menu" responsiveLayout="scroll"
                breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="عرض {first} إلى {last} من أصل {totalRecords} مبيعات">
                <template #header>
                    <div class="flex flex-col flex-wrap md:flex-row md:justify-between w-full md:items-center gap-5">
                        <div class="relative mb-2 md:mb-0 md:mr-5">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600"></i>
                            <InputText @input="filterTable($event)" placeholder="Search" id="searchInput"
                                class="pl-10 py-2 border-gray-300" />
                        </div>

                        <form  @submit.prevent="filterSells" class="flex flex-col md:flex-row gap-5">
                                <input v-model="date.start_date" required type="date"
                                    class="xl:w-[12rem] p-2 border-gray-300  focus:border-blue-500" />
                                <input v-model="date.end_date" required type="date"
                                    class="p-2 xl:w-[12rem] focus:border-blue-500 border-gray-300 " />

                                <Button type="submit" icon="pi pi-filter-slash" label="فلترة"
                                    class="p-button-outlined bg-white py-2 px-4 border border-green-500 rounded-md text-green-800 hover:text-white hover:bg-green-500"
                                    />
                        </form>

                        <Button type="button" icon="pi pi-filter-slash" label="إزالة"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500 mt-2 md:mt-0"
                            @click="clearFilter()" />
                    </div>
                </template>
                <template #empty>.لا توجد مبيعات</template>
                <!-- Columns -->
                <Column field="client_name" class="border-b-[1px] text-center" header="اسم الزبون">
                    <template #body="{data}">
                        <span>{{ data.client_name ?? '-'}} </span>
                    </template>
                </Column>

                <Column field="total_price" class="border-b-[1px] text-center" header="مجموع الحساب" sortable>
                    <template #body="{ data }">
                        <span>{{ common.formatNumber(data.total_price) }} درهم</span>
                    </template>
                </Column>

                <Column field="paid_amount" class="border-b-[1px] text-center" header="الدفع" sortable>
                    <template #body="{ data }">
                        <span>{{ common.formatNumber(data.paid_amount) }} درهم</span>
                    </template>
                </Column>
                <Column field="remaining_amount" class="border-b-[1px] text-center" header="الدين" sortable>
                    <template #body="{ data }">
                        <span>{{ common.formatNumber(data.remaining_amount) }} درهم</span>
                    </template>
                </Column>
                <Column field="change" class="border-b-[1px] text-center" header="الصرف" >
                    <template #body="{ data }">
                        <span>{{ common.formatNumber(data.change) }} درهم</span>
                    </template>
                </Column>
                <Column field="status" class="border-b-[1px] text-center" header="الحالة" sortable>
                    <template #body="{ data }">
                        <span :class="[
                            data.status === 'مدفوع' ? 'bg-green-500' :
                               ' bg-orange-500',
                            'text-sm', 'text-white', 'rounded-md','text-center', 'px-1.5', 'py-1'
                        ]">
                            {{ data.status }}
                        </span> </template>
                </Column>
                <Column field="" class="border-b-[1px] text-center" header="طريقة الدفع" sortable>
                    <template #body="{ data }">
                        <span :class="['text-xs text-white rounded-md p-1.5 border' , data.payment_method == 'نقدًا' ? 'bg-gray-500' : 'bg-sky-500']">{{ data.payment_method }}</span>
                    </template>
                </Column>
                <Column field="" class="border-b-[1px] text-center" header="تاريخ دفع الشيك">
                    <template #body="{ data }">
                        <span>{{ data.check ? common.formatDate(data.check_date) : "-" }}</span>
                    </template>
                </Column>
                <Column field="" class="border-b-[1px] text-center" header="تاريخ العملية ">
                    <template #body="{ data }">
                        <span>{{  common.formatDate(data.created_at)  }}</span>
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
        <!-- Skeleton Data Table -->
        <div v-else>
            <DataTable :value="skeletonObjects" class="p-datatable-sites -mt-5 m-6" tableStyle="min-width: 30rem"
                :rowClassName="'border-t text-center border-gray-200'">
                <template #header>
                    <div class="flex justify-between items-center gap-5" style="display: flex">
                        <span class="relative">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600" />
                            <InputText placeholder="Search" class="pl-10 py-2" disabled />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="Clear"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500" />
                    </div>
                </template>


                <Column field="" class="border-b-[1px] text-center" header="اسم الزبون">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="" class="border-b-[1px] text-center" header="مجموع الحساب" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="" class="border-b-[1px] text-center" header="الدفع" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" class="border-b-[1px] text-center" header="الدين" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="remaining_amount" class="border-b-[1px] text-center" header="الصرف" >
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" class="border-b-[1px] text-center" header="الحالة" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" class="border-b-[1px] text-center" header="الشيك" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" class="border-b-[1px] text-center" header="تاريخ دفع الشيك" sortable>
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
import common from "../../utils/common";
import { ref, onMounted, computed } from "vue";
import store from "../../store";
import { useToast } from "primevue/usetoast";
const sells = ref([]);
const loading = ref(true);
const filteredSells = ref([]);
const date = ref({start_date:null,end_date:null});
const toast = useToast();
onMounted(() => {
    fetchSells();
});
function filterSells() {
    document.body.style.cursor = 'wait'
    store.dispatch("filterByDates",  date.value ).then((res) => {
        if (res.status === 200 && res.data)
            filteredSells.value = [...res.data];
        else
       common.showValidationErrors(res,toast);
    }).catch(error => error).finally(()=>document.body.style.cursor = 'default');
}
function fetchSells() {
    store
        .dispatch("getSells")
        .then((res) => {

            sells.value = res;
            filteredSells.value = [...res];

        })
        .catch((error) => error)
        .finally(() => {
            loading.value = false;
        });
}
function clearFilter() {
    document.getElementById("searchInput").value = "";
    date.value = {};
    filteredSells.value = sells.value;
}
function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredSells.value = sells.value;
    else
        filteredSells.value = sells.value.filter(
            (s) =>
                s.client_name.toLowerCase().includes(filter)

        );
}
const total = computed(() => filteredSells.value.reduce(
    (acc, sell) => acc + parseFloat(sell.total_amount),
    0
));
// Define skeleton data
const skeletonObjects = new Array(10);
</script>
