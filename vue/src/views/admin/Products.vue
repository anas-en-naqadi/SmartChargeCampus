<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                قائمة المنتجات

            </h2>
            <Button :disabled="!loading" @click="visible = true" label="+ منتج جديد"
                class="p-button-outlined h-10 bg-white py-2 px-3 mr-6 border border-black rounded-md text-black hover:text-white hover:bg-black" />
        </div>
        <!-- Main Data Table -->
        <div v-if="loading">
            <DataTable :value="filteredProducts" removableSort :paginator="true"
                class="p-datatable-sites text-center -mt-5 m-6" showGridlines :rows="10" dataKey="id"
                tableStyle="min-width: 30rem;text-align:center" stripedRows
                :rowClassName="'border-t text-center border-gray-200'" filterDisplay="menu" :loading="!loading"
                responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="عرض {first} إلى {last} من أصل {totalRecords} منتجًا">
                <template #header>
                    <div class="flex justify-between items-center gap-5" style="display: flex">
                        <span class="relative">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600" />
                            <InputText @input="filterTable($event)" placeholder="Search" id="searchInput"
                                class="pl-10 py-2 border-gray-300" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="Clear"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                            @click="clearFilter()" />
                    </div>
                </template>
                <template #empty>لا يوجد منتجات</template>
                <!-- Columns -->
                <Column field="image" header="الصورة" class="border-b-[1px] text-center" style="min-width: 12rem">
                    <template #body="{ data }">
                        <img v-if="data.image" class="w-36 h-24 rounded-sm" :src="data.image" alt="Product Image" />
                        <span v-else>لا توجد صورة</span>
                    </template>
                </Column>
                <Column field="name" header="إسم المنتج" class="border-b-[1px] text-center">

                </Column>
                <Column field="category" header="الفئة" class="border-b-[1px] text-center" sortable>
                    <template #body="{ data }">
                        <span> {{ data.category_name }}</span>
                    </template>
                </Column>
                <Column field="stock_quantity" header="الكمية" class="border-b-[1px] text-center" sortable>

                </Column>
                <Column field="purchase_price" header="سعر الشراء" sortable class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span> {{ common.formatNumber(data.purchase_price) }} درهم</span>
                    </template>
                </Column>
                <Column class="border-b-[1px] text-center" field="selling_price" sortable header="سعر البيع">
                    <template #body="{ data }">
                        <span> {{ common.formatNumber(data.selling_price) }} درهم</span>
                    </template>
                </Column>
                <Column sortable field="expiration_date" class="border-b-[1px] text-center"
                    header="تاريخ انتهاء الصلاحية">
                    <template #body="{ data }">
                        <span> {{ data.expiration_date ? common.formatDate(data.expiration_date) : "-" }} </span>
                    </template>
                </Column>
                <Column sortable field="created_at" class="border-b-[1px] text-center" header="تاريخ الإنشاء">
                    <template #body="{ data }">
                        <span> {{ common.formatDate(data.created_at) }} </span>
                    </template>
                </Column>

                <Column header="إجراءات" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <div class="flex gap-3">

                            <button title="Edit this product" @click="editProduct(data.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button title="Delete this product" @click="deleteProduct(data.id)">
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
                            <InputText disabled placeholder="Search" class="pl-10 py-2" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="Clear"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500" />
                    </div>
                </template>
                <Column field="" header="الصورة" class="border-b-[1px] text-center" style="min-width: 12rem">
                    <template #body>
                        <Skeleton size="8rem"></Skeleton>
                    </template>
                </Column>
                <Column field="" header="إسم المنتج" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="" header=" الفئة" class="text-center border-b-2" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header="الكمية" class="border-b-[1px] text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header="سعر الشراء" class="border-b-[1px] text-center" sortable>
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
                <Column sortable field="created_at" class="border-b-[1px] text-center" header="تاريخ الإنشاء">
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


        <Dialog v-model:visible="visible" modal :header="product.id ? 'تعديل منتج' : 'إضافة منتج'"
            class="w-[50%] xl:w-[35%] md:w-[50%] sm:w-[50%]">
            <form class="max-w-md mx-auto mt-4 w-full">
                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="product.name" type="text" id="name"
                        class="block p-2 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="name"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">إسم
                        المنتج</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <div class="flex flex-col gap-4 items-center justify-center w-full">
                        <label for="message" class="block text-lg font-medium text-gray-500">الصورة</label>
                        <img v-if="product.image" :src="product.image" alt="product image" width="450"
                            class="rounded-md h-44" />
                        <label for="dropzone-file"
                            class="flex flex-col items-center justify-center w-full mb-2 h-44 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 white:hover:bg-bray-800 white:bg-gray-700 hover:bg-gray-100 white:border-gray-600 white:hover:border-gray-500 white:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500 white:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 white:text-gray-400">
                                    <span class="font-semibold">Click to upload</span> or drag and
                                    drop
                                </p>
                                <p class="text-xs text-gray-500 white:text-gray-400">
                                    SVG, PNG, JPG or GIF (MAX. 800x400px)
                                </p>
                            </div>

                            <input @change="onChooseImage($event)" id="dropzone-file" type="file" class="hidden" />
                        </label>
                    </div>
                </div>



                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-5 group">
                        <input v-model="product.selling_price" type="text" id="selling_price"
                            class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="selling_price"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">سعر
                            البيع
                        </label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input v-model="product.stock_quantity" type="text" id="product_stock_quantity"
                            class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="product_stock_quantity"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">الكمية</label>
                    </div>
                </div>

                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="product.purchase_price" type="text" id="purchase_price"
                        class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="purchase_price"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">سعر
                        الشراء</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="product.expiration_date" type="date" id="expiration_date"
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

                    <select v-model="product.category_id" id="underline_select"
                        class="block p-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-300 appearance-none white:text-gray-400 white:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                        <option value="0" selected>اختر فئة</option>
                        <option v-for="category in categories" :value="category.id" :key="category.id">
                            {{ category.category_name }}
                        </option>
                    </select>
                </div>
                <Buton :loading="loaderButton" :string="product.id ? 'تعديل' : 'إضافة'" @trigger-event="switchActions"
                    :color="'blue'" />


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
const visible = ref(false);
const toast = useToast();
const loaderButton = ref(false);
const categories = ref([]);
const products = computed(() => store.state.products);
const filteredProducts = ref([]);
const skeletonObjects = new Array(10);
const product = ref({
    name: "",
    image: "",
    category_id: 0,
    selling_price: "",
    purchase_price: null,
    stock_quantity: null,
    expiration_date: null,
});
const loading = ref(false);

onMounted(() => {
    fetchProducts();
    fetchCategories();
});
function fetchCategories() {
    store
        .dispatch("getCategories")
        .then((res) => {
            categories.value = res;
        })
        .catch((error) => console.error(error))
        .finally(() => {
            loading.value = true;
        });
}
function make_changes(res) {
    visible.value = false;
    common.showToast({ title: res.data.message, icon: "success" });
    console.log(res.data.product);

    if (product.value.id) {
        const index = filteredProducts.value.findIndex((p) => p.id === res.data.product.id);
        console.log(index);
        if (index !== -1) {
            filteredProducts.value[index] = res.data.product;
        }

    } else
        filteredProducts.value.push(res.data.product);

    store.commit("SET_PRODUCTS",filteredProducts.value);


    filteredProducts.value.sort((a, b) => {
        return new Date(b.created_at) - new Date(a.created_at);
    });
    product.value = {

        category_id: 0,

    };
}
function updateProduct() {

    loaderButton.value = false;
    store
        .dispatch("updateProduct", {
            product: product.value,
            product_id: product.value.id,
        })
        .then((res) => {
            if (res.status === 200 && res.data)

                make_changes(res);
            else

                common.showValidationErrors(res, toast);
        })
        .catch((error) => error).finally(() => loaderButton.value = false


        );


}
function editProduct(id) {

    store
        .dispatch("getProduct", id)
        .then((res) => {
            if (res.status === 200 && res.data) {
                product.value.id = res.data.id;
                product.value.name = res.data.name;
                product.value.image = res.data.image;
                product.value.selling_price = res.data.selling_price;
                product.value.category_id = res.data.category.id;
                product.value.purchase_price = res.data.purchase_price;
                product.value.stock_quantity = res.data.stock_quantity;
                product.value.expiration_date = res.data.expiration_date;
                visible.value = true;
            }
        })
        .catch((error) => error);

}
function switchActions() {
    product.value.id ? updateProduct() : addProduct();
}

function addProduct() {
    visible.value = true;
    loaderButton.value = true;

    store
        .dispatch("storeProduct", product.value)
        .then((res) => {

            if (res && res.status === 200 && res.data)
                make_changes(res);

            else
                common.showValidationErrors(res, toast);
        })
        .catch((error) => error).finally(() => loaderButton.value = false);


}


function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredProducts.value = products.value;
}
function onChooseImage(event) {
    const file = event.target.files[0]; // Get the first file
    const reader = new FileReader();

    reader.onload = () => {
        const base64Image = reader.result; // Get the base64 representation of the image
        product.value.image = base64Image; // Assign base64 image to product.image
    };

    reader.onerror = (error) => {
        console.error("Error reading the file:", error);
    };

    // Read the file as a data URL
    reader.readAsDataURL(file);
}



function fetchProducts() {
    store
        .dispatch("getProducts")
        .then((res) => {
            filteredProducts.value = products.value;
        })
        .catch((error) => console.error(error))
        .finally(() => {
            loading.value = true;
        });
}

function deleteProduct(id) {
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
                store.dispatch("destroyProduct", id).then((res) => {
                    if (res) {
                        common.showToast({ title: res.message, icon: "success" });
                        document.body.style.cursor = "default";
                        filteredProducts.value = filteredProducts.value.filter((p)=>p.id != id);

                    }
                });
            }
        });
}

function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredProducts.value = products.value;
    else
        filteredProducts.value = products.value.filter(
            (p) =>
                p.name.toLowerCase().includes(filter)
        );
}



</script>
