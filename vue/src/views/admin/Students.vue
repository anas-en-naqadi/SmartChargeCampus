<template>
    <div class="card mx-5 mt-12 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                Etudiants
            </h2>

        </div>
        <!-- Main Data Table -->
        <div v-if="!loading">
            <DataTable :value="filteredStudents" removableSort :paginator="true" class="p-datatable-sites -mt-5 m-6"
                showGridlines :rows="10" dataKey="id" tableStyle="min-width: 30rem" stripedRows
                responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Affichage de {first} à {last} sur {totalRecords} étudiants">
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
                <template #empty>Aucun étudiant disponible</template>

                <Column field="cne" class="border-b-[1px] text-center" header="CNE">
                </Column>
                <Column field="name" header="Nom Complet" style="min-width: 12rem" class="border-b-[1px] text-center">
                </Column>
                <Column field="email" class="border-b-[1px] text-center" header="Email">
                </Column>
                <Column field="phone_number" class="border-b-[1px] text-center" header="Numéro de Téléphone">
                </Column>
                <Column field="university" class="border-b-[1px] text-center" header="Université" sortable>
                </Column>
                <Column field="sector" class="border-b-[1px] text-center" header="Filière" sortable>
                </Column>
                <Column field="year_of_study" class="border-b-[1px] text-center" style="min-width: 11rem"
                    header="Année d'Étude" sortable>
                </Column>




                <Column field="created_at" class="border-b-[1px] text-center w-full m-auto " header="Creer le" sortable>
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
                            <InputText disabled placeholder="Search" class="pl-10 py-2" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="إزالة"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500" />
                    </div>
                </template>

                <Column field="cne" class="border-b-[1px] text-center" style="min-width: 10rem" header="CNE">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="name" class="border-b-[1px] w-[5rem] text-center" style="min-width: 12rem"
                    header="Nom Complet">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="email" class="border-b-[1px] w-[5rem] text-center" header="Email">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="phone_number" class="border-b-[1px] w-[5rem] text-center" header="Numéro de Téléphone">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="university" class="border-b-[1px] w-[5rem] text-center" header="Université">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="sector" class="border-b-[1px] w-[5rem] text-center" header="Filière">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="year_of_study" class="border-b-[1px] w-[5rem] text-center" header="Année d'Étude">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="created_at" class="border-b-[1px] w-[5rem] text-center" header="Creer le">
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
const students = computed(() => store.state.students);
const loading = ref(false);

const filteredStudents = ref([]);

const toast = useToast();

const date = ref({});
onMounted(() => {
    getStudents();
});

function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredStudents.value = students.value;
}
function getStudents() {
    loading.value = true;
    store
        .dispatch("fetchStudents")
        .then((res) =>
            filteredStudents.value = res
        )
        .catch((error) => console.error(error))
        .finally(() =>
            loading.value = false
        );
}

function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredStudents.value = students.value;
    else
        filteredStudents.value = students.value.filter(
            (c) =>
                c.cne?.toLowerCase().includes(filter) ||
                c.name?.toLowerCase().includes(filter) ||
                c.email?.toLowerCase().includes(filter) ||
                c.phone_number?.toLowerCase().includes(filter)



        );
}






// Define skeleton data
const skeletonObjects = new Array(10);


</script>
