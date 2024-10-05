
<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                قائمة المشتريات







            </h2>
            <div class="flex items-center justify-center">
                <Button @click="visible1 = true" label="+ منتج جديد"
                    class="p-button-outlined h-10 bg-white py-2 px-3 mr-6 border border-black rounded-md text-black hover:text-white hover:bg-black" />
                <Button @click="visible1 = true" label="+ منتج موجود"
                    class="p-button-outlined h-10 bg-white py-2 px-3 mr-6 border border-black rounded-md text-black hover:text-white hover:bg-black" />
            </div>

        </div>
        <!-- Main Data Table -->
        <div v-if="!loading">
            <DataTable :value="filteredProducts" removableSort :paginator="true" class="p-datatable-sites -mt-5 m-6"
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
                            <InputText @input="filterTable($event)" placeholder="Search" id="searchInput"
                                class="pl-10 py-2 border-gray-300" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="Clear"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                            @click="clearFilter()" />
                    </div>
                </template>
                <template #empty>No products list found.</template>

                <Column field="description" header="إسم المنتج" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span> {{ data.product_name }}</span>
                    </template>
                </Column>
                <Column field="description" header="المورد" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span> {{ data.product_name }}</span>
                    </template>
                </Column>
                <Column field="category.category_name" header="الكمية" class="border-b-[1px] text-center" sortable>
                    <template #body="{ data }">
                        <span> {{ data.category.category_name }} </span>
                    </template>
                </Column>
                <Column field="price" header="سعر الشراء" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span> {{ common.formatNumber(data.price) }} DH</span>
                    </template>
                </Column>
                <Column class="border-b-[1px] text-center" field="quantity" header="سعر البيع"></Column>
                <Column sortable field="discount" class="border-b-[1px] text-center" header="تاريخ انتهاء الصلاحية">
                    <template #body="{ data }">
                        <span> {{ common.formatNumber(Math.floor(data.discount)) }}% </span>
                    </template>
                </Column>
                <Column sortable field="discount" class="border-b-[1px] text-center" header="تاريخ الإنشاء">
                    <template #body="{ data }">
                        <span> {{ common.formatNumber(Math.floor(data.discount)) }}% </span>
                    </template>
                </Column>
                <Column field="discount" class="border-b-[1px] text-center" header="تاريخ التعديل">
                    <template #body="{ data }">
                        <span> {{ common.formatNumber(Math.floor(data.discount)) }}% </span>
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
                            <InputText placeholder="Search" class="pl-10 py-2" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="Clear"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500" />
                    </div>
                </template>

                <Column field="id" header="ID" class="text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="product_name" header="Nom produit" class="text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="image" header="Image" style="min-width: 12rem"
                    class="text-center flex items-center justify-center">
                    <Column field="description" header="Description" class="text-center">
                        <template #body>
                            <Skeleton></Skeleton>
                        </template>
                    </Column>
                    <Column field="category.category_name" header="Category" class="text-center">
                        <template #body>
                            <Skeleton></Skeleton>
                        </template>
                    </Column>
                    <template #body>
                        <Skeleton width="9rem" height="9rem"></Skeleton>
                    </template>
                </Column>

                <Column field="price" header="Prix" class="text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column sortable class="text-center" field="quantity" header="Quantite">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column sortable field="discount" class="text-center" header="Coupons">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column sortable field="" class="text-center" header="Actions">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
            </DataTable>
        </div>
        <Dialog v-model:visible="visible2" modal header="Ajouter multiple image"
            class="w-[50%] xl:w-[35%] md:w-[50%] sm:w-[50%]">
            <div class="flex flex-col justify-center gap-5 items-center w-full">
                <div class="flex -mt-7 gap-5 w-full">
                    <Buton class="" :loading="loading" @trigger-event="addImages()" :string="'Upload All'"
                        :color="'green'" />
                    <Buton class="" :loading="loading" @click="images = []" :string="'Reset'" :color="'red'" />
                </div>
                <div v-if="images.length && images[0].id" class="flex items-center justify-center gap-5 w-full -mt-2.5">
                    <input v-model="imageId" type="text" placeholder="type the order of the image to delete"
                        class="focus:shadow-primary-outline white:bg-slate-850 white:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    <Buton class="-mt-[3px]" :loading="loading" @trigger-event="deleteImage()" :string="'Delete Image'"
                        :color="'red'" />
                </div>
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file"
                        class="flex flex-col items-center justify-center w-[20rem] h-54  border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 white:hover:bg-bray-800 white:bg-gray-700 hover:bg-gray-100 white:border-gray-600 white:hover:border-gray-500 white:hover:bg-gray-600">
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
                        <input id="dropzone-file" type="file" class="hidden" @change="addMoreImages" multiple />
                    </label>
                </div>
                <Galleria :value="images" :responsiveOptions="responsiveOptions" :numVisible="5" class="mb-10"
                    containerStyle="max-width: 650px;max-height:300px">
                    <template #item="slotProps">
                        <img :src="slotProps.item.src" alt="test" style="width: 100%" />
                    </template>
                    <template #thumbnail="slotProps">
                        <img :src="slotProps.item.src" alt="slotProps.item.alt" />
                    </template>
                </Galleria>

            </div>
        </Dialog>

        <Dialog v-model:visible="visible1" modal header="Modifier Produit"
            class="w-[50%] xl:w-[35%] md:w-[50%] sm:w-[50%]">
            <form class="max-w-md mx-auto mt-4 w-full">
                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="product.product_name" type="text" id="product_name"
                        class="block py-2 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="product_name"
                        class="peer-focus:font-medium absolute text-lg text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nom
                        Produit</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <div class="flex flex-col gap-4 items-center justify-center w-full">
                        <label for="message" class="block text-lg font-medium text-gray-500">Image produit</label>
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

                <div class="relative z-0 w-full mb-5 group">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-500">Description</label>
                    <textarea v-model="product.description" id="message" rows="2"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-500 focus:ring-blue-500 focus:border-blue-500 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:focus:ring-blue-500 white:focus:border-blue-500"
                        placeholder="Write your thoughts here..."></textarea>
                </div>

                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-5 group">
                        <input v-model="product.price" type="text" id="product_price"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="product_price"
                            class="peer-focus:font-medium absolute text-lg text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Prix</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input v-model="product.quantity" type="text" id="product_quantity"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="product_quantity"
                            class="peer-focus:font-medium absolute text-lg text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Quantite</label>
                    </div>
                </div>

                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="product.discount" type="text" id="product_discount"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="product_discount"
                        class="peer-focus:font-medium absolute text-lg text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Coupons</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <label for="product_discount"
                        class="peer-focus:font-medium absolute text-lg text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Category</label>

                    <select v-model="product.category" id="underline_select"
                        class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-300 appearance-none white:text-gray-400 white:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                        <option value="" selected>Choose a country</option>
                        <option v-for="category in categories" :key="category.id">
                            {{ category.category_name }}
                        </option>
                    </select>
                </div>
                <button @click="switchActions" type="button"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Submit
                </button>
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
const categories = ref([]);
const products = computed(() => store.state.products);
const filteredProducts = computed(() => store.state.products);
let imageId = 0;
const images = ref([]);
const product = ref({
    product_name: "",
    image: "",
    category: "",
    description: "",
    price: null,
    quantity: null,
    discount: null,
});
const loading = ref(true);

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
            loading.value = false;
        });
}
function updateProduct() {
    product.value.category_id = categories.value.find(
        (c) => c.category_name === product.value.category
    ).id;

    store
        .dispatch("updateProduct", {
            product: product.value,
            product_id: product.value.id,
        })
        .then((res) => {
            if (res.status === 200 && res.data) {
                visible1.value = false;
                common.showToast({ message: res.data.message, type: "success" });

                fetchProducts();
                products.value = {};
            }
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
        })
        .catch((error) => error)

}
function editProduct(id) {

    store
        .dispatch("getProduct", id)
        .then((res) => {
            if (res.status === 200 && res.data) {
                product.value.id = res.data.id;
                product.value.product_name = res.data.product_name;
                product.value.image = res.data.image;
                product.value.description = res.data.description;
                product.value.category = res.data.category.category_name;
                product.value.price = res.data.price;
                product.value.quantity = res.data.quantity;
                product.value.discount = res.data.discount;
                visible1.value = true;
            }
        })
        .catch((error) => error);

}
function switchActions() {
    product.value.id ? updateProduct() : addProduct();
}

function addProduct() {
    visible1.value = true;
    product.value.category_id = categories.value.find(
        (c) => c.category_name === product.value?.category
    )?.id;
    delete product.value.category;

    ;
    store
        .dispatch("storeProduct", product.value)
        .then((res) => {

            if (res.status === 200 && res.data) {
                visible1.value = false;
                common.showToast({ title: res.data.message, icon: "success" });

                fetchProducts();
                product.value = {};
            }
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
        })
        .catch((error) => {
            common.showToast({ title: error, icon: "error" });
        })

}

let productId;
function addImages() {
    images.value = images.value.filter((i) => i.id === undefined);
    store
        .dispatch("storeMultipleImages", { images: images.value, product_id: productId })
        .then((res) => {
            if (res.status === 200) {
                common.showToast({ title: res.data.message, icon: "success" });
            }
        })
        .catch((error) => {
            common.showToast({ title: error.data.message, icon: "error" });
        })
        .finally(() => {
            visible2.value = false;
            images.value = [];
            product.value = {};
        });
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
    console.log("product", product.value);
}

function addMoreImages(event) {

    if (event.target.files.length <= 5) {
        const files = event.target.files;
        const reader = new FileReader();

        reader.onload = () => {
            images.value.push({ src: reader.result, alt: "test" });
            // Check if there are more files
            if (images.value.length < files.length) {
                reader.readAsDataURL(files[images.value.length]);
            } else {
                console.log("images", images.value);
                return;
            }
        };

        // Start reading the first file
        reader.readAsDataURL(files[0]);
    } else {
        common.showToast({
            title: "Please select a maximum of 5 images",
            icon: "error",
        });
    }
}

function fetchProducts() {
    store
        .dispatch("getProducts")
        .then()
        .catch((error) => console.error(error))
        .finally(() => {
            loading.value = false;
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
                store.dispatch("destroyProduct", id).then((res) => {
                    if (res) {
                        common.showToast({ title: res.message, icon: "success" });
                        fetchProducts();
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
                p.description.toLowerCase().includes(filter) ||
                p.product_name.toLowerCase().includes(filter)
        );
}

// Define skeleton data
const skeletonObjects = new Array(10);
function getAllImages(id) {
    productId = id;
    store.dispatch("fetchAllImages", id).then((res) => {
        if (res.status === 200 && res.data) {
            images.value.length = 0; // Clear the existing images if needed
            res.data.forEach((i) => {
                images.value.push({
                    id: i.id,
                    src: i.image_path, // Adjust path based on where your images are stored
                    alt: "test" // Example alt text, you can set this dynamically if needed
                });
            });
        }
    }).catch((error) => {
        console.error("Error fetching images:", error);
    }).finally(() => {
        visible2.value = true; // Ensure this is executed after fetching is done
    });
}
function deleteImage() {
    if (imageId != 0) {
        const id = images.value[imageId - 1]?.id;
        images.value = images.value.filter((image, index) => index !== (imageId - 1));
        imageId = 0;
        if (id) {
            store.dispatch("destroyImage", { image_id: id }).then((res) => {
                console.log("res", res)
                if (res.status === 200 && res) {
                    common.showToast({ title: res.data.message, icon: "success" });
                    imageId = 0;
                }
            }).catch((error) => {
                common.showToast({ title: "Oops, something went wrong !!", icon: "warning" });

            })
        } else {
            common.showToast({ title: "Image deleted successfully !!", icon: "success" });

        }


    } else {
        common.showToast({ title: "Please type the order of the image", icon: "warning" });

    }
}
</script>
