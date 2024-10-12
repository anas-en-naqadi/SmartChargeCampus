<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                قائمة الفئات
            </h2>
            <Button :disabled="loading" @click="visible = true" label="+ فئة جديدة"
                class="p-button-outlined h-10 bg-white py-2 px-3 mr-6 border border-black rounded-md text-black hover:text-white hover:bg-black" />
        </div>

        <!-- Main Data Table -->

        <div v-if="!loading">
            <DataTable :value="filteredCategories" removableSort :paginator="true" class="p-datatable-sites -mt-5 m-6 "
                showGridlines :rows="10" dataKey="id" tableStyle="min-width: 30rem" stripedRows
                :rowClassName="'border-t text-center border-gray-200'" filterDisplay="menu" responsiveLayout="scroll"
                breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="عرض {first} إلى {last} من أصل {totalRecords} فئات">
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
                <template #empty>لم يتم العثور على أي فئات.</template>
                <!-- Columns -->
                <Column field="category_name" header="الفئة" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span>{{ data.category_name }}</span>
                    </template>
                </Column>

                <Column field="created_at" header="أنشئ في" sortable class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span>{{ common.formatDate(data.created_at) }}</span>
                    </template>
                </Column>
                <Column header="الإجراءات" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <div class="flex gap-3">


                            <button title="حذف هذا المنتج" @click="deleteCategory(data.id)">
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
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                            @click="clearFilter()" />
                    </div>
                </template>


                <Column field="" header="الفئة">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header="أنشئ في">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="" header="الإجراءات">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="visible" modal header="إضافة فئة" class="w-[30%]">
            <div class="w-ful">
                <div class="relative z-0 w-full mb-5 group">
                    <label for="category_name" class="block mb-2 text-sm font-medium text-gray-500">اسم الفئة</label>
                    <input v-model="category_name" type="text" id="category_name"
                        class="block py-2 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder="إلكترونيات..." required />
                </div>

                <div class="w-full">
                    <Buton :loading="loaderButton" color="green" @trigger-event="addCategory" string="إضافة" />
                </div>
            </div>
        </Dialog>
        <Toast />
    </div>
</template>


<script setup>
import common from "../../utils/common";
import { ref, onMounted, computed } from "vue";
import store from "../../store";
import { useToast } from "primevue/usetoast";
import Buton from "../../components/Button.vue";
const categories = computed(() => store.state.categories);
const loading = ref(true);
const filteredCategories = ref([]);
const visible = ref(false);
const loaderButton = ref(false);
const skeletonObjects = new Array(10);

const toast = useToast();
let category_name = null;
onMounted(() => {
    fetchCategories();
});

function fetchCategories() {
    store
        .dispatch("getCategories")
        .then((res) =>
            filteredCategories.value = res.data
        )
        .catch((error) => error)
        .finally(() =>
            loading.value = false
        );
}

function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredCategories.value = categories.value;
}
function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredCategories.value = categories.value;
    else
        filteredCategories.value = categories.value.filter((c) =>
            c.category_name.toLowerCase().includes(filter)
        );
}

function addCategory() {
    loaderButton.value = true;
    store
        .dispatch("storeCategory", category_name)
        .then((res) => {
            if (res.status === 200 && res.data) {
                visible.value = false;
                common.showToast({ title: res.data.message, icon: "success" });
                filteredCategories.value.sort((a,b)=> {
        return new Date(b.created_at) - new Date(a.created_at);
    });
                filteredCategories.value.push(res.data.category);
                store.commit("SET_CATEGORIES", filteredCategories.value);
                category_name = null;

            }
            common.showValidationErrors(res, toast);
        })
        .catch((error) => error).finally(() => loaderButton.value = false);
}

function deleteCategory(id) {
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
                store.dispatch("destroyCategory", id).then((res) => {
                    if (res) {
                        common.showToast({ title: res.message, icon: "success" });
                        document.body.style.cursor = 'default';
                        filteredCategories.value = categories.value.filter((c) => c.id != id);
                        store.commit("SET_CATEGORIES", filteredCategories.value);
                    }
                });
            }
        });
}
</script>
