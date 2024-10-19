<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center direction-rtl w-full mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                تقارير الموردين
            </h2>
            <Button :disabled="loading" @click="visible = true" label="+ مورد جديد"
                class="p-button-outlined h-10 bg-white py-2 px-3 mr-6 border border-black rounded-md text-black hover:text-white hover:bg-black" />
        </div>



        <!-- Main Data Table -->
        <div v-if="!loading">
            <DataTable :value="filteredSuppliers" v-model:expandedRows="expandedRows" @rowExpand="totalOwn" removableSort :paginator="true" class="p-datatable-sites -mt-5 m-6"
                showGridlines :rows="10" dataKey="id" tableStyle="min-width: 30rem" stripedRows
                :rowClassName="'border-t text-center border-gray-200'" filterDisplay="menu" :loading="loading"
                responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="عرض {first} إلى {last} من أصل {totalRecords} موردًا">
                <template #header>
                    <div class="flex justify-between items-center gap-5" style="display: flex">
                        <span class="relative">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600" />
                            <InputText @input="filterTable($event)" placeholder="بحث" id="searchInput"
                                class="pl-10 py-2 border-gray-300" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="إزالة"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                            @click="clearFilter()" />
                    </div>
                </template>
                <template #empty>لا يوجد موردون</template>

                <!-- Columns -->
                <Column expander style="width: 5rem" />

                <Column field="name" header="إسم المورد" class="border-b-[1px] text-center"></Column>
                <Column field="phone" header="رقم الهاتف" class="border-b-[1px] text-center"></Column>
                <Column field="cni" header="رقم البطاقة" class="border-b-[1px] text-center" ></Column>
                <Column field="purchase_count" header="عدد المشتريات" class="border-b-[1px] text-center" sortable>
                </Column>
                <Column field="total_credit" header="مجموع الدين " class="border-b-[1px] text-center" sortable>
                    <template #body="{ data }">
                        <span>{{ common.formatNumber(data.total_credit) }}درهم</span>
                    </template>
                </Column>
                <template #expansion="{ data }">
                    <div class="p-2.5">

                        <div :class="[
                            data.purchases.length ? 'mb-4' : '-mb-10',
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
                                <span class="ml-1">: مجموع المشتريات
                                </span>
                            </p>
                        </div>
                        <DataTable v-if="data.purchases.length"  :value="data.purchases" removableSort :paginator="true" class="p-datatable-sites -mt-5 m-6"
                showGridlines :rows="10" dataKey="id" tableStyle="min-width: 30rem" stripedRows
                :rowClassName="'border-t text-center border-gray-200'" filterDisplay="menu" :loading="loading"
                responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="عرض {first} إلى {last} من أصل {totalRecords} منتجًا">

                <template #empty>لم يتم العثور على المشتريات</template>

                <Column field="" header="إسم المنتج" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span> {{ data.name }}</span>
                    </template>
                </Column>
                <Column field="" header="المورد" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span> {{ data.transporter_name }}</span>
                    </template>
                </Column>
                <Column field="" header="الفئة" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span> {{ data.category_name }}</span>
                    </template>
                </Column>
                <Column field="" header="الكمية" class="border-b-[1px] text-center" sortable>
                    <template #body="{ data }">
                        <span> {{ common.formatNumber(data.stock_quantity) }} <span
                                class="text-red-500">Pcs</span></span>
                    </template>
                </Column>
                <Column field="price" header="سعر الشراء" class="border-b-[1px] text-center" sortable>
                    <template #body="{ data }">
                        <span> {{ common.formatNumber(data.purchase_price) }} درهم</span>
                    </template>
                </Column>

                <Column sortable field="" class="border-b-[1px] text-center" header="تاريخ انتهاء الصلاحية">
                    <template #body="{ data }">
                        <span> {{ common.formatDate(data.expiration_date) ?? '-' }}</span>
                    </template>
                </Column>
                <Column sortable field="" class="border-b-[1px] text-center" header="تاريخ الإنشاء">
                    <template #body="{ data }">
                        <span> {{ common.formatDate(data.created_at) }} </span>
                    </template>
                </Column>

                <Column header="إجراءات" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <div class="flex gap-3">
                            <button title="Edit this product" @click="editPurchase(data.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button title="Delete this product" @click="deletePurchase(data.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </Column>
            </DataTable>
                    </div>
                </template>
                <Column header="إجراءات" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <div class="flex gap-3">

                            <button title="تعديل هذا المورد" @click="editSupplier(data.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button title="إضافة دين جديد" @click="showDebtDialog(data.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6 text-blue-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>


                            </button>
                            <button title="حذف هذا المورد" @click="deleteSupplier(data.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Skeleton Data Table (Loading State) -->
        <div v-else>
            <DataTable :value="skeletonObjects" class="p-datatable-sites -mt-5 m-6" tableStyle="min-width: 30rem"
                :rowClassName="'border-t text-center border-gray-200'">
                <template #header>
                    <div class="flex justify-between items-center gap-5" style="display: flex">
                        <span class="relative">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600" />
                            <InputText disabled placeholder="بحث" class="pl-10 py-2" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="إزالة"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500" />
                    </div>
                </template>



                <Column field="" header="إسم المورد" class="text-center border-b-2">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header="رقم البطاقة" class="border-b-[1px] text-center" >
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="" header="رقم الهاتف" class="text-center border-b-2">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header="عدد المشتريات" class="text-center border-b-2" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="total_credit" header="مجموع الدين " class="border-b-[1px] text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column header="إجراءات" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
            </DataTable>
        </div>
        <Dialog v-model:visible="visible" modal :header="supplier.id ? 'تعديل المورد' : 'إضافة المورد'"
            class="w-[50%] xl:w-[35%] md:w-[50%] sm:w-[50%]">
            <form class="max-w-md mx-auto mt-4 w-full">
                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="supplier.name" type="text" id="name"
                        class="block p-2 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="name"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">إسم
                        المورد</label>
                </div>




                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-5 group">
                        <input v-model="supplier.phone" type="text" id="phone"
                            class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="phone"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">رقم
                            الهاتف

                        </label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input v-model="supplier.total_credit" type="text" id="supplier_total_credit"
                            class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="supplier_total_credit"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">مجموع
                            الدين</label>
                    </div>
                </div>

                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="supplier.cni" type="text" id="cni"
                        class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="cni"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                        رقم البطاقة الوطنية</label>
                </div>


                <Buton :loading="loaderButton" :string="supplier.id ? 'تعديل' : 'إضافة'" @trigger-event="switchActions"
                    color="green" />


            </form>
        </Dialog>
        <Dialog v-model:visible="visible1" modal header="إضافة دين" class="w-[50%] xl:w-[35%] md:w-[50%] sm:w-[50%]">
            <form class="max-w-md mx-auto mt-4 w-full">


                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="debt" type="text" id="cni"
                        class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="cni"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                        مبلغ الدين </label>
                </div>


                <Buton :loading="loaderButton" string="إضافة" @trigger-event="addNewDebt" color="green" />


            </form>
        </Dialog>
        <Toast />

    </div>
</template>

<script setup>
import Buton from "../../components/Button.vue";
import { ref, onMounted, computed } from "vue";
import store from "../../store";
import common from "../../utils/common";
import { useToast } from "primevue/usetoast";
const suppliers = computed(() => store.state.suppliers);
const filteredSuppliers = ref([]);
const toast = useToast();
const visible1 = ref(false);
const debt = ref("");
const visible = ref(false);
const expandedRows = ref([]);
const TotalBought = ref(0);

const loading = ref(true);
let supplierId = 0;
const loaderButton = ref(false);
const supplier = ref({

});
const skeletonObjects = new Array(10);

onMounted(() => {
    getSuppliers();
});
function totalOwn(event) {
    TotalBought.value = 0
    console.log(event)
    const supplier = event.data;
    TotalBought.value = supplier.purchases.reduce((acc, purchase) => acc + (parseFloat(purchase.purchase_price) * purchase.stock_quantity), 0);
    console.log(TotalBought.value)

}
function updateSupplier() {

    loaderButton.value = true;
    store
        .dispatch("updateSupplier", {
            supplier: supplier.value,
            supplier_id: supplier.value.id,
        })
        .then((res) => {
            if (res.status === 200 && res.data)
                make_changes(res);

            else

                common.showValidationErrors(res, toast);
        })
        .catch((error) => error).finally(() => loaderButton.value = false


        )


}
function showDebtDialog(id) {
    visible1.value = true;
    supplierId = id;
}
function addNewDebt() {
    loaderButton.value = true;
    store.dispatch("newDebt", { debt: debt.value, supplier_id: supplierId }).then((res) => {
        if (res && res.status === 200 && res.data) {
            common.showToast({ title: res.data.message, icon: "success" });
            getSuppliers();

            visible1.value = false;

        }
        else
            common.showValidationErrors(res, toast);

    }).catch((error) => error).finally(() => loaderButton.value = false);
}
function editSupplier(id) {
    document.body.style.cursor='wait';
    store
        .dispatch("editSupplier", id)
        .then((res) => {
            if (res && res.status === 200 && res.data) {
                supplier.value.id = res.data.id;
                supplier.value.name = res.data.name;
                supplier.value.phone = res.data.phone;
                supplier.value.total_credit = res.data.total_credit;
                supplier.value.cni = res.data.cni;
                document.body.style.cursor='default';

                visible.value = true;
            }
        })
        .catch((error) => error);

}
function switchActions() {
    supplier.value.id ? updateSupplier() : addSupplier();
}
function make_changes(res) {
    visible.value = false;
    common.showToast({ title: res.data.message, icon: "success" });



    if (supplier.value.id) {
        const index = filteredSuppliers.value.findIndex((p) => p.id === res.data.supplier.id);
        console.log(index);
        if (index !== -1) {
            filteredSuppliers.value[index] = res.data.supplier;
        }

    } else
        filteredSuppliers.value.push(res.data.supplier);

    store.commit("SET_SUPPLIERS", filteredSuppliers.value);


    filteredSuppliers.value.sort((a, b) => {
        return new Date(b.created_at) - new Date(a.created_at);
    });
    supplier.value = {};

}

function addSupplier() {
    loaderButton.value = true;

    store
        .dispatch("storeSupplier", supplier.value)
        .then((res) => {

            if (res && res.status === 200 && res.data)
                make_changes(res);

            else
                common.showValidationErrors(res, toast);
        })
        .catch((error) => error).finally(() =>
            loaderButton.value = false
        );


}


function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredSuppliers.value = suppliers.value;
}
function deleteSupplier(id) {
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
                document.body.style.cursor = 'wait';
                store.dispatch("destroySupplier", id).then((res) => {
                    if (res) {
                        common.showToast({ title: res.data.message, icon: "success" });
                        filteredSuppliers.value = filteredSuppliers.value.filter((p) => p.id !== id);
                        store.commit("SET_SUPPLIERS", filteredSuppliers.value);
                        document.body.style.cursor = 'default';
                    }
                });
            }
        });
}
function getSuppliers() {
    store
        .dispatch("fetchSuppliers")
        .then((res) =>
            filteredSuppliers.value = suppliers.value
        )
        .catch((error) => error)
        .finally(() =>
            loading.value = false
        );
}



function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredSuppliers.value = suppliers.value;
    else
        filteredSuppliers.value = suppliers.value.filter(
            (p) => p.name.toLowerCase().includes(filter) ||
                p.phone.toLowerCase().includes(filter) ||
                p.cni.toLowerCase().includes(filter)


        );
}

// Define skeleton data


</script>
