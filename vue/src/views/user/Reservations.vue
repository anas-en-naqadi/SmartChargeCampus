<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div>
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                Reservations

            </h2>

        </div>
        <!-- Main Data Table -->
        <div v-if="loading">
            <DataTable :value="filteredReservations" removableSort :paginator="true"
                class="p-datatable-sites text-center -mt-5 m-6" showGridlines :rows="10" dataKey="id"
                tableStyle="min-width: 30rem;text-align:center" stripedRows
                :rowClassName="'border-t text-center border-gray-200'" filterDisplay="menu" :loading="!loading"
                responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Affichage {first} à {last} sur {totalRecords} réservations">
                <template #header>
                    <div class="flex flex-col flex-wrap md:flex-row md:justify-between w-full md:items-center gap-5">
                        <div class="relative mb-2 md:mb-0 md:mr-5">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600"></i>
                            <InputText @input="filterTable($event)" placeholder="Search" id="searchInput"
                                class="pl-10 py-2 border-gray-300" />
                        </div>

                        <form @submit.prevent="filterReservationsByDates" v-if="user.role==='admin'" class="flex flex-col md:flex-row gap-5">
                            <input v-model="date.start_date" required type="date"
                                class="xl:w-[12rem] p-2 border-gray-300  focus:border-blue-500" />
                            <input v-model="date.end_date" required type="date"
                                class="p-2 xl:w-[12rem] focus:border-blue-500 border-gray-300 " />

                            <Button type="submit" icon="pi pi-filter-slash" label="Filtrer"
                                class="p-button-outlined bg-white py-2 px-4 border border-green-500 rounded-md text-green-800 hover:text-white hover:bg-green-500" />
                        </form>

                        <Button type="button" icon="pi pi-filter-slash" label="Clear"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500 mt-2 md:mt-0"
                            @click="clearFilter()" />
                    </div>
                </template>

                <template #empty>Vous n'avez pas encore effectué de réservation</template>
                <!-- Columns -->
                <Column field="reservation_code" header="Code Reservation" class="border-b-[1px] text-center">
                </Column>
                <Column field="cne" header="CNE" class="border-b-[1px] text-center">
                </Column>

                <Column field="student_name" header="Nom Etudiant" class="border-b-[1px] text-center">
                </Column>

                <Column field="charging_station" header="Station de Charge" class="border-b-[1px] text-center" sortable>
                </Column>
                <Column field="reserved_port" header="Numero de Port" class="border-b-[1px] text-center">
                </Column>

                <Column field="payment_status" header="Statut de Paiement" class="border-b-[1px] text-center" sortable>
                    <template #body="{data}">
                        <span :class="[
                            data.payment_status === 'en_attente' ? 'bg-orange-500' :
                                data.payment_status === 'payé' ? 'bg-green-500' : 'bg-red-500',
                            'text-xs rounded-md text-white p-2 font-semibold'
                        ]">
                            {{ data.payment_status }}
                        </span>
                    </template>
                </Column>

                <Column field="payment_method" header="Méthode de Paiement" sortable class="border-b-[1px] text-center">
                </Column>
                <Column field="" header="Montant" sortable class="border-b-[1px] text-center">
                    <template #body="">
                        <span>5 DH</span>
                    </template>
                </Column>


                <Column sortable field="created_at" class="border-b-[1px] text-center" header="Date de création">
                    <template #body="{ data }">
                        <span> {{ common.formatDate(data.created_at) }} </span>
                    </template>
                </Column>
                <Column header="Actions" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <div class="flex gap-3 justify-center">

                            <button v-if="user.role==='student' && data.payment_status === 'en_attente'"
                                title="Annuler la reservation" @click="cancelReservation(data.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6 text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                </svg>

                            </button>
                            <button
                                v-else-if="user.role === 'admin' && data.payment_method === 'espèce' && data.payment_status === 'en_attente'"
                                title="Marquer comme Payer" @click="markAsPayed(data.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-7 text-green-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                </svg>

                            </button>
                            <span v-else>_</span>

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
                <Column field="reservation_code" header="Code Reservation" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="cne" header="CNE" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="student_name" header="Nom Etudiant" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="charging_station" header="Station de Charge" class="border-b-[1px] text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="payment_status" header="Statut de Paiement" class="border-b-[1px] text-center" sortable>
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="reserved_port" header="Numero de Port" class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="payment_method" header="Méthode de Paiement" sortable class="border-b-[1px] text-center">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column sortable field="created_at" class="border-b-[1px] text-center" header="Date de création">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="" header="Montant" class="border-b-[1px] text-center">
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
<Toast />
    </div>
</template>

<script setup>

import { useToast } from "primevue/usetoast";
import { ref, onMounted, computed, reactive } from "vue";
import store from "../../store";
import common from "../../utils/common";
const filteredReservations = ref([]);
const skeletonObjects = new Array(10);
const reservations = computed(() => store.state.reservations)
const loading = ref(false);
const toast  = useToast();
const user = computed(() => store.state.user.data);
const date = reactive({});
onMounted(() => {
    getReservations();

});






function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredReservations.value = reservations.value;
}



function getReservations() {
    store
        .dispatch("fetchReservations")
        .then(() =>
            filteredReservations.value = reservations.value
        )
        .catch((error) => console.error(error))
        .finally(() => {
            loading.value = true;
        });
}
function filterReservationsByDates() {
    document.body.style.cursor = 'wait'
    store.dispatch("filterByDates", date).then((res) => {
        if (res.status === 200 && res.data)
            filteredReservations.value = [...res.data];
        else
            common.showValidationErrors(res, toast);
    }).catch(error => error).finally(() => document.body.style.cursor = 'default');
}

function markAsPayed(id) {
    common
        .showSwal({
            title: "Êtes-vous sûr ?",
            text: "Vous ne pourrez pas revenir en arrière !",  // Translation of the Arabic text
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Marque paye",
        })
        .then((result) => {
            if (result.isConfirmed) {
                document.body.style.cursor = 'wait';
                store.dispatch("markAsPayed", id).then((res) => {
                    if (res) filterReservations(id, res);



                });
            }
        });

}
function cancelReservation(id) {
    common
        .showSwal({
            title: "Êtes-vous sûr ?",
            text: "Vous ne pourrez pas revenir en arrière !",  // Translation of the Arabic text
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, annuler ceci",
        })
        .then((result) => {
            if (result.isConfirmed) {
                document.body.style.cursor = "wait";
                store.dispatch("cancelReservation", id).then((res) => {
                    if (res) filterReservations(id, res);


                });
            }
        });
}

function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredReservations.value = reservations.value;
    else
        filteredReservations.value = reservations.value.filter(
            (p) =>
                p.cne.toLowerCase().includes(filter) || p.student_name.toLowerCase().includes(filter) || p.charging_station.toLowerCase().includes(filter)
        );
}

function filterReservations(id, res) {
    common.showToast({ title: res.message, icon: "success" });
    document.body.style.cursor = "default";
    const index = filteredReservations.value.findIndex(p => p.id === id);

    if (index !== -1)
        filteredReservations.value[index] = res.reservation;
}

</script>
