<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight  text-gray-900 p-8">
                سجلات النشاط
            </h2>

        </div>

        <!-- Main Data Table -->

        <div v-if="!loading">
            <DataTable :value="filteredActivities" removableSort :paginator="true" class="p-datatable-sites -mt-5 m-6 "
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
                <template #empty>لم يتم العثور على أي سجلات.</template>
                <!-- Columns -->
                <Column field="" header="اسم المستخدم" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span>{{ data.causer.name }}</span>
                    </template>
                </Column>
                <Column field="description" header="الحدث " class="border-b-[1px] text-center">

                </Column>
                <Column field="log_name" header=" العملية" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span>{{ data.log_name }}</span>
                    </template>
                </Column>



                <Column field="" header="جهاز" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span>{{ data.properties }}</span>
                    </template>
                </Column>

                <Column field="created_at" header="تاريخ العملية" sortable class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <span>{{ common.formatDate(data.created_at) }}</span>
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
                            <InputText :disabled="loading" placeholder="بحث" class="pl-10 py-2" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="مسح"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                            @click="clearFilter()" />
                    </div>
                </template>

                <Column field="" header="اسم المستخدم" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="description" header="الحدث " class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="log_name" header=" العملية" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>



                <Column field="" header="جهاز" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="created_at" header="تاريخ العملية" sortable class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>


            </DataTable>
        </div>


    </div>
</template>


<script setup>
import common from "../../utils/common";
import { ref, onMounted } from "vue";
import store from "../../store";
const activities = ref([]);
const loading = ref(true);
const filteredActivities = ref([]);

onMounted(() => {
    getActivities();
});

function getActivities() {
  store
    .dispatch("fetchActivities")
    .then((res) => {
    if(res && res.status ===200 && res.data){
        activities.value = res.data;
        filteredActivities.value = activities.value;
        console.log(filteredActivities);
    }
    })
    .catch((error) => console.error(error))
    .finally(() => {
      loading.value = false;
    });
}

function clearFilter() {
  document.getElementById("searchInput").value = "";
  filteredActivities.value = activities.value;
}
function filterTable(event) {
  const filter = event.target.value.toLowerCase();

  if (!filter) filteredActivities.value = activities.value;
  else
    filteredActivities.value = activities.value.filter((a) =>
      a.causer.name.toLowerCase().includes(filter) ||
      a.log_name.toLowerCase().includes(filter) ||
        a.description.toLowerCase().includes(filter)


    );
}



// Define skeleton data
const skeletonObjects = new Array(10);
</script>
