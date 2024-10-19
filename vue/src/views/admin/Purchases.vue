<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                قائمة المشتريات

            </h2>
            <div class="flex items-center justify-center">
                <Button :disabled="loading" @click="visible2 = true; visible1 = true" label="+ منتج جديد"
                    class="p-button-outlined h-10 bg-white py-2 px-3 mr-6 border border-black rounded-md text-black hover:text-white hover:bg-black" />
                <Button :disabled="loading" @click="visible1 = true; visible2 = false" label="+ منتج موجود"
                    class="p-button-outlined h-10 bg-white py-2 px-3 mr-6 border border-black rounded-md text-black hover:text-white hover:bg-black" />
            </div>

        </div>
        <!-- Main Data Table -->
        <div v-if="!loading">
            <DataTable :value="filteredPurchases" removableSort :paginator="true" class="p-datatable-sites -mt-5 m-6"
                showGridlines :rows="10" dataKey="id" tableStyle="min-width: 30rem" stripedRows
                :rowClassName="'border-t text-center border-gray-200'" filterDisplay="menu" :loading="loading"
                responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="عرض {first} إلى {last} من أصل {totalRecords} منتجًا">
                <template #header>
                    <div class="flex justify-between items-center gap-5" style="display: flex">
                        <span class="relative">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600" />
                            <InputText @input="filterTable($event)" placeholder="بحث" id="searchInput"
                                class="pl-10 py-2 border-gray-300" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="مسح"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                            @click="clearFilter()" />
                    </div>
                </template>
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
                <Column field="" header="الفئة" class="border-b-[1px] text-center" sortable>
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

        <!-- Skeleton Data Table -->
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
                        <Button type="button" icon="pi pi-filter-slash" label="مسح"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500" />
                    </div>
                </template>

                <Column field="" header="إسم المنتج" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header="المورد" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header="الفئة" class="border-b-[1px] text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header="الكمية" class="border-b-[1px] text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="price" header="سعر الشراء" class="border-b-[1px] text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column class="border-b-[1px] text-center" field="" header="سعر البيع" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column sortable field="" class="border-b-[1px] text-center" header="تاريخ انتهاء الصلاحية">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column sortable field="" class="border-b-[1px] text-center" header="تاريخ الإنشاء">
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


        <Dialog v-model:visible="visible1" modal :header="purchase.id ? 'تعديل المشتريات' : 'إضافة المشتريات'"
            class="w-[50%] xl:w-[35%] md:w-[50%] sm:w-[50%]">
            <form class="max-w-md mx-auto mt-4 w-full">
                <div v-if="!visible2" class="relative z-0 w-full mb-5 group">
                    <label for="updated_product"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">المنتج</label>

                    <select v-model="purchase.updated_product" id="updated_product"
                        class="block p-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-300 appearance-none white:text-gray-400 white:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                        <option selected>اختر المنتج</option>
                        <option v-for="product in products" :value="product.id" :key="product.id">
                            {{ product.name }}
                        </option>
                    </select>
                </div>
                <div v-else class="relative z-0 w-full mb-5 group">
                    <input v-model="purchase.name" type="text" id="name"
                        class="block p-2 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="name"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">إسم
                        المنتج</label>
                </div>




                <div class="grid md:grid-cols-2 md:gap-6">

                    <div class="relative z-0 w-full mb-5 group">
                        <input v-model="purchase.stock_quantity" type="text" id="product_stock_quantity"
                            class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="product_stock_quantity"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">الكمية</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <label for="transporter_id"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">المورد</label>

                        <select v-model="purchase.transporter_id" id="transporter_id"
                            class="block p-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-300 appearance-none white:text-gray-400 white:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option value="0" selected>اختر المورد</option>
                            <option v-for="supplier in suppliers" :value="supplier.id" :key="supplier.id">
                                {{ supplier.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="purchase.purchase_price" type="text" id="purchase_price"
                        class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="purchase_price"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">سعر
                        الشراء</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="purchase.expiration_date" type="date" id="expiration_date"
                        class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="expiration_date"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">تاريخ
                        انتهاء
                        الصلاحية</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <label for="category_id"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">الفئة</label>

                    <select v-model="purchase.category_id" id="category_id"
                        class="block p-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-300 appearance-none white:text-gray-400 white:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                        <option value="0" selected>اختر فئة</option>
                        <option v-for="category in categories" :value="category.id" :key="category.id">
                            {{ category.category_name }}
                        </option>
                    </select>
                </div>
                <Buton :loading="loaderButton" :string="purchase.id ? 'تعديل' : 'إضافة'" @trigger-event="switchActions"
                    :color="'green'" />


            </form>
        </Dialog>
        <Toast />
    </div>
</template>

<script setup>

import { useToast } from "primevue/usetoast";
import { ref, onMounted, computed } from "vue";
import store from "../../store";
import common from "../../utils/common";
import Buton from "../../components/Button.vue";
const visible1 = ref(false);
const visible2 = ref(false);
const toast = useToast();
const categories = computed(() => store.state.categories);
const skeletonObjects = new Array(10);

const products = computed(() => store.state.products);
const suppliers = computed(() => store.state.suppliers);
const loaderButton = ref(false);
const purchases = computed(() => store.state.purchases);
const filteredPurchases = ref([]);
const purchase = ref({
    transporter_id: 0,
    category_id: 0,
});
const loading = ref(true);

onMounted(() => {
    getPurchases();
    store.dispatch("fetchSuppliers");
    store.dispatch("getProducts");
    store
        .dispatch("getCategories");
});

function updatePurchase() {
    loaderButton.value = true;

    store
        .dispatch("updatePurchase", {
            purchase: purchase.value,
            purchase_id: purchase.value.id,
        })
        .then((res) => {
            if (res.status === 200 && res.data) {
                make_changes(res);
            }
            else {
                loaderButton.value = false;

                common.showValidationErrors(res, toast);
            }
        })
        .catch((error) => error)

}
function editPurchase(id) {
    document.body.style.cursor = 'wait';

    store
        .dispatch("editPurchase", id)
        .then((res) => {
            if (res.status === 200 && res.data) {
                purchase.value.id = res.data.id;
                purchase.value.name = res.data.name;
                purchase.value.category_id = res.data.category_id;
                purchase.value.transporter_id = res.data.transporter_id;
                purchase.value.updated_product = res.data.updated_product;

                purchase.value.purchase_price = res.data.purchase_price;
                purchase.value.stock_quantity = res.data.stock_quantity;
                purchase.value.expiration_date = res.data.expiration_date;
                visible2.value = res.data.updated_product ? false : true;

                document.body.style.cursor = 'default';

                visible1.value = true;
            }
        })
        .catch((error) => error);

}
function switchActions() {
    if (purchase.value.id) {
        updatePurchase(); // If there's an ID, update the purchase
    } else if (visible2.value) {
        addNewPurchase(); // If `visible2` is true, add a new purchase
    } else {
        addExistingPurchase(); // Otherwise, add an existing purchase
    }
}


function addExistingPurchase() {
    visible1.value = true;
    loaderButton.value = true;


    store
        .dispatch("storeExistingPurchase", purchase.value)
        .then((res) => {

            if (res.status === 200 && res.data)

                make_changes(res);

            else {
                common.showValidationErrors(res, toast);
                loaderButton.value = false;
            }
        })
        .catch((error) => {
            common.showToast({ title: error, icon: "error" });
        })

}

function addNewPurchase() {
    visible1.value = true;

    loaderButton.value = true;


    store
        .dispatch("storeNewPurchase", purchase.value)
        .then((res) => {

            if (res.status === 200 && res.data) {

                make_changes(res);
            }
            else {
                common.showValidationErrors(res, toast);
                loaderButton.value = false;
            }
        })
        .catch((error) => {
            common.showToast({ title: error, icon: "error" });
        })

}
function make_changes(res) {
    loaderButton.value = false;

    visible1.value = false;
    common.showToast({ title: res.data.message, icon: "success" });
    if (res.data.purchase.id) {
        const index = filteredPurchases.value.findIndex(p => p.id === res.data.purchase.id);
        if (index !== -1) {
            filteredPurchases.value[index] = res.data.purchase;

        }

    } else
        filteredPurchases.value.push(res.data.purchase);
    store.commit("SET_PURCHASES", filteredPurchases.value);


    filteredPurchases.value.sort((a, b) => {
        return new Date(b.created_at) - new Date(a.created_at);
    });
    purchase.value = {
        purchase_price: null,
        expiration_date: null,
        transporter_id: 0,
        stock_quantity: null,
        category_id: 0,
    };
}
function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredPurchases.value = purchases.value;
}




function getPurchases() {
    store
        .dispatch("fetchPurchases")
        .then((res) => filteredPurchases.value = res.data)
        .catch((error) => error)
        .finally(() => {
            loading.value = false;
        });
}

function deletePurchase(id) {
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

                store.dispatch("destroyPurchase", id).then((res) => {
                    if (res) {
                        common.showToast({ title: res.message, icon: "success" });
                        filteredPurchases.value = filteredPurchases.value.filter((p) => p.id != id);
                        store.commit("SET_PURCHASES", filteredPurchases.value);

                        document.body.style.cursor = 'default';
                    }
                });
            }
        });
}

function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredPurchases.value = purchases.value;
    else
        filteredPurchases.value = purchases.value.filter(
            (p) =>
                p.transporter_name.toLowerCase().includes(filter) ||
                p.name.toLowerCase().includes(filter) ||
                p.category_name.toLowerCase().includes(filter)

        );
}



</script>
