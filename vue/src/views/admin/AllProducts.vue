<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                تقرير المنتجات
            </h2>

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
                        <Button type="button" icon="pi pi-filter-slash" label="إزالة"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                            @click="clearFilter()" />
                    </div>
                </template>
                <template #empty>لا يوجد منتجات في المخزون</template>
                <!-- Columns -->
                <Column field="image" header="الصورة" class="border-b-[1px] text-center" style="min-width: 12rem">
                    <template #body="{ data }">
                        <img v-if="data.image" class="w-36 h-24 rounded-sm" :src="data.image" alt="Product Image" />
                        <span v-else>لا توجد صورة</span>
                    </template>
                </Column>
                <Column field="name" header="إسم المنتج" class="border-b-[1px] text-center">

                </Column>

                <Column field="category" header="الفئة" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span> {{ data.category.category_name }}</span>
                    </template>
                </Column>
                <Column field="stock_quantity" header="الكمية" class="border-b-[1px] text-center" sortable>
                    <template #body="{ data }">
                        <span> {{ common.formatNumber(data.stock_quantity) }} Pcs</span>
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



                <Column field="" header="الصورة" style="min-width: 12rem"
                    class="text-center flex items-center justify-center border-b-2">
                    <template #body>
                        <Skeleton size="8rem"></Skeleton>
                    </template>
                </Column>

                <Column field="" header="إسم المنتج" class="text-center border-b-2">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header=" الفئة" sortable class="text-center border-b-2">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header="الكمية" class="text-center border-b-2">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>


            </DataTable>
        </div>



    </div>
</template>

<script setup>

import { ref, onMounted, computed } from "vue";
import store from "../../store";
import common from "../../utils/common";

const products = computed(() => store.state.products);
const filteredProducts = ref([]);


const loading = ref(true);

onMounted(() => {
    fetchProducts();
});




function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredProducts.value = products.value;
}

function fetchProducts() {
    store
        .dispatch("getProducts")
        .then((res)=>{
            filteredProducts.value = products.value;
        })
        .catch((error) => console.error(error))
        .finally(() => {
            loading.value = false;
        });
}



function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredProducts.value = products.value;
    else
        filteredProducts.value = products.value.filter(
            (p) => p.name.toLowerCase().includes(filter)
        );
}

// Define skeleton data
const skeletonObjects = new Array(10);


</script>
