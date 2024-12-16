<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                Liste des stations de recharge
            </h2>
            <Button :disabled="loading" @click="visible1 = true" label="+ Nouvelle station"
                class="p-button-outlined h-10 bg-white py-2 px-3 mr-6 border border-black rounded-md text-black hover:text-white hover:bg-black" />

        </div>

        <!-- Main Data Table -->
        <div v-if="!loading">
            <DataTable :value="filteredChargeStations" removableSort :paginator="true"
                class="p-datatable-sites -mt-5 m-6" showGridlines :rows="10" dataKey="id" tableStyle="min-width: 30rem"
                stripedRows :rowClassName="'border-t text-center border-gray-200'" filterDisplay="menu"
                :loading="loading" responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Affichage de {first} à {last} sur {totalRecords} stations">
                <template #header>
                    <div class="flex justify-between items-center gap-5" style="display: flex">
                        <span class="relative">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600" />
                            <InputText @input="filterTable($event)" placeholder="Rechercher" id="searchInput"
                                class="pl-10 py-2 border-gray-300" />
                        </span>
                        <Button type="button" icon="pi pi-filter-slash" label="Réinitialiser"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
                            @click="clearFilter()" />
                    </div>
                </template>
                <template #empty>Aucune station de recharge trouvée</template>

                <Column field="name" header="Nom de la station" class="border-b-[1px] text-center">

                </Column>
                <Column field="description" header="Description" class="border-b-[1px] text-center">

                </Column>
                <Column field="latitude" header="Latitude" class="border-b-[1px] text-center" sortable>

                </Column>
                <Column field="longitude" header="Longitude" class="border-b-[1px] text-center" sortable>

                </Column>
                <Column field="total_ports" header="Total Ports" class="border-b-[1px] text-center" sortable>

                </Column>


                <Column sortable field="" class="border-b-[1px] text-center" header="Date de création">
                    <template #body="{ data }">
                        <span> {{ common.formatDate(data.created_at) }} </span>
                    </template>
                </Column>

                <Column header="Actions" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <div class="flex gap-3">
                            <button title="Voir les ports associés" @click="showPorts(data.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-7 text-blue-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>

                            </button>
                            <button title="Modifier cette station" @click="editCharge_station(data.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button title="Supprimer cette station" @click="deleteCharge_station(data.id)">
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

                <Column field="name" header="Nom de la station" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="description" header="Description" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="latitude" header="Latitude" class="border-b-[1px] text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="longitude" header="Longitude" class="border-b-[1px] text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="total_ports" header="Total Ports" class="border-b-[1px] text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column header="Actions" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
            </DataTable>
        </div>
        <Dialog v-model:visible="visible1" modal :header="charge_station.id ? 'Modifier' : 'Ajouter'+ ' Station'"
            class="w-[50%] xl:w-[35%] md:w-[50%] sm:w-[50%]">
            <form class="max-w-md mx-auto mt-4 w-full" @submit.prevent="switchActions()">
                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="charge_station.name" type="text" id="name"
                        class="block p-2 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="name"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                        Nom du Station</label>
                </div>


                <div class="relative z-0 w-full mb-5 group">
                    <textarea v-model="charge_station.description" id="description"
                        class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        required />
                    <label for="description"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Description

                    </label>
                </div>


                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-5 group">
                        <input v-model="charge_station.latitude" type="text" id="latitude"
                            class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="latitude"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Latitude
                        </label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input v-model="charge_station.longitude" type="text" id="longitude"
                            class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="longitude"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Longitude
                        </label>
                    </div>

                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="charge_station.total_ports" type="number" id="total_ports"
                        class="block p-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="total_ports"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:white:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Total
                        Ports
                    </label>
                </div>
                <Buton :loading="loaderButton" :string="charge_station.id ? 'Modifier' : 'Ajouter'" :color="'green'" />


            </form>
        </Dialog>
        <Toast />
    </div>
</template>


<script setup>

import { useToast } from "primevue/usetoast";
import { ref, onMounted, computed, watch, reactive } from "vue";
import store from "../../store";
import common from "../../utils/common";
import Buton from "../../components/Button.vue";
import { useRouter } from "vue-router";

const toast = useToast();
const skeletonObjects = new Array(10);
const router = useRouter();
const charge_station = reactive({});
const loaderButton = ref(false);
const chargeStations = computed(() => store.state.charge_stations);
const filteredChargeStations = ref([]);
const visible1 = ref(false);
const loading = ref(true);

onMounted(() => {
    getChargeStations();
});
function showPorts(id){
    var cryptedPortNumber = btoa(id);
    router.push({ name: 'admin-ports', params: { portN: cryptedPortNumber } });
}
function updateCharge_station() {
    loaderButton.value = true;

    store
        .dispatch("updateChargeStation",
            charge_station
            )
        .then((res) => {
            if (res && res.status === 200 && res.data) {
                make_changes(res,'put');
            }
            else {
                loaderButton.value = false;

                common.showValidationErrors(res, toast);
            }
        })
        .catch((error) => error)

}
function editCharge_station(id) {
    document.body.style.cursor = 'wait';

    store
        .dispatch("editChargeStation", id)
        .then((res) => {
            if (res.status === 200 && res.data) {
               Object.assign(charge_station,res.data);

                document.body.style.cursor = 'default';

                visible1.value = true;
            }
        })
        .catch((error) => error);

}
function switchActions() {
    if (charge_station.id) {
        updateCharge_station(); // If there's an ID, update the charge_station
    } else {
        addNewCharge_station(); // If `visible2` is true, add a new charge_station
    }
}




function addNewCharge_station() {
    visible1.value = true;

    loaderButton.value = true;


    store
        .dispatch("storeChargeStation", charge_station)
        .then((res) => {

            if (res && res.status === 201 && res.data) {

                make_changes(res,'post');
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
function make_changes(res,method) {
    loaderButton.value = false;

    visible1.value = false;
    common.showToast({ title: res.data.message, icon: "success" });
    if (method==='put') {
        const index = filteredChargeStations.value.findIndex(p => p.id === res.data.charge_station.id);
        if (index !== -1) {
            filteredChargeStations.value[index] = res.data.charge_station;

        }

    } else
        filteredChargeStations.value.push(res.data.charge_station);

    store.commit("SET_CHARGE_STATIONS", filteredChargeStations.value);



    Object.assign(charge_station, {});
}
function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredChargeStations.value = chargeStations.value;
}




function getChargeStations() {
    store
        .dispatch("fetchChargeStations")
        .then((res) => filteredChargeStations.value = res)
        .catch((error) => error)
        .finally(() => {
            loading.value = false;
        });
}

function deleteCharge_station(id) {
    common
        .showSwal({
            title: "Êtes-vous sûr ?",
            text: "Vous ne pourrez pas annuler cette action !",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, supprimez-la !",
        })

        .then((result) => {
            if (result.isConfirmed) {
                document.body.style.cursor = 'wait';

                store.dispatch("destroyChargeStation", id).then((res) => {
                    if (res) {
                        common.showToast({ title: res.message, icon: "success" });
                        filteredChargeStations.value = filteredChargeStations.value.filter((p) => p.id != id);
                        store.commit("SET_chargeStations", filteredChargeStations.value);

                        document.body.style.cursor = 'default';
                    }
                });
            }
        });
}

function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredChargeStations.value = chargeStations.value;
    else
        filteredChargeStations.value = chargeStations.value.filter(
            (p) =>
                p.description.toLowerCase().includes(filter) ||
                p.name.toLowerCase().includes(filter)

        );
}



</script>
