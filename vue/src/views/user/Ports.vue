<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2
                class="mb-1 text-lg sm:text-xl md:text-2xl flex gap-2 items-center font-extrabold tracking-tight text-gray-900 p-8">
                Tous les ports de
                <span v-if="charge_station.name">{{ charge_station.name }}</span>
                <Skeleton v-else width="12rem" />
            </h2>

            <Button v-if="user.role === 'admin'" :disabled="!loading" @click="visible = !visible" label="+ nouveau Port"
                class="p-button-outlined h-10 bg-white py-2 2xl:px-3 px-1 mr-6 border border-black rounded-md text-black hover:text-white hover:bg-black w-full sm:w-auto" />

        </div>
        <div v-if="loading">
            <div v-if="ports.length" class="p-10 w-full flex flex-wrap -mt-12">
                <div v-for="(port, index) in ports" :key="index"
                    :class="['border  bg-white 2xl:w-[13rem] 2xl:h-[13rem] w-[10rem] h-[10rem] rounded-md border-2 flex items-center justify-center m-3 flex-col', port.status === 'disponible' ? 'border-green-500' : (port.status === 'indisponible' ? 'border-red-500' : 'border-orange-500')]"
                    :title="`Reserver Le port ${port.port_number}`">
                    <span
                        :class="[port.status === 'disponible' ? 'text-green-500' : (port.status === 'indisponible' ? 'text-red-500' : 'text-orange-500'), 'font-bold -mt-4 text-xl']">{{
                            port.port_number }}</span>
                    <div v-if="user.role === 'student'">
                        <button @click="visible = !visible; actualPort = port">
                            <svg v-if="port.status === 'indisponible'" xmlns=" http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="size-12 text-red-500 cursor-pointer">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <svg v-else-if="port.status === 'disponible'" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="size-12 text-green-500 cursor-pointer">
                                >
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none"
                                @click="markAsDisponible(port.id)" title="retourné ce port au travail"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="size-12 text-orange-500 cursor-pointer">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                        </button>
                    </div>
                    <div v-if="user.role === 'admin'" class="flex items-center flex-col gap-4">

                        <span v-if="port.status === 'disponible'" class="cursor-pointer" @click="common.showToast({
                            title: 'Ce port est déjà disponible pour réservation !', icon: 'warning'
                        });">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-10 text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                            </svg>

                        </span>
                        <span v-else-if="port.status === 'indisponible'" class="cursor-pointer"
                            title="Marquer comme disponible" @click="markAsNotReserved(port.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-10 text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                            </svg>

                        </span>
                        <span v-else title="retourné ce port au travail" @click="markAsDisponible(port.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-10 text-orange-500 cursor-pointer">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </span>
                        <div class="flex">

                            <button @click="editPort(port.id)" class="cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-10 text-blue-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button @click="deletePort(port.id)" class="cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-10 text-violet-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>



                </div>
            </div>
            <div v-else>
                <div v-if="user.role === 'admin'" class="flex gap-2 items-center pb-4 justify-center ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8 text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>

                    <span class="text-red-500">Aucun port n'a été ajouté pour le moment. Veuillez ajouter un port pour
                        continuer.</span>
                </div>
                <div v-else class="flex gap-2 items-center pb-4 justify-center ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8 text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>

                    <span class="text-red-500">Aucun port n'a été ajouté pour le moment.</span>
                </div>

            </div>




        </div>
        <div v-else class="p-10 w-full flex flex-wrap -mt-12 ">
            <div v-for="(port, index) in new Array(10)" :key="index" class="m-3">
                <Skeleton width="13rem" height="13rem" class="rounded-md" />


            </div>


        </div>
        <Dialog v-if="user.role === 'student'" v-model:visible="visible" modal
            header="Choisissez votre méthode de paiement" class="w-[50%] xl:w-[35%] md:w-[50%] sm:w-[50%]">

            <div class="flex flex-col justify-center items-center w-full gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
                <span class="font-bold text-xl -mt-5   mr-1.5">5 DH</span>


                <div class="flex gap-3 -mt-4 w-full">
                    <Buton string="Espece" @trigger-event="reserveThisPort(actualPort)" :color="'green'" />
                    <Buton :loading="false" string="En Ligne" @trigger-event="goToPaiement()" class="bg-blue-500" />
                </div>
            </div>





        </Dialog>
        <Dialog v-if="user.role === 'admin'" v-model:visible="visible" modal
            :header="port.id ? 'Modifier' : 'Ajouter' + ' Port'" class="w-[50%] xl:w-[35%] md:w-[50%] sm:w-[50%]"
            :style="{ width: '50vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <form class="max-w-md mx-auto mt-4 w-full" @submit.prevent="switchActions()">






                <div class="flex items-center gap-4 -mt-5 justify-center">
                    <div class="relative z-0 w-full group ">
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 ">Status
                        </label>
                        <select id="status" v-model="port.status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                            <option value="undefined" selected>Choisissez Une status</option>
                            <option value="disponible">Disponnible</option>
                            <option value="indisponible">Indisponible</option>
                            <option value="sous maintenance">Sous Maintenance</option>
                        </select>
                    </div>
                    <div class="relative z-0 w-full group">
                        <label for="port_number" class="block mb-2 text-sm font-medium text-gray-900 ">Numero Port
                        </label>
                        <input v-model="port.port_number" type="text" id="port_number" value="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            required />

                    </div>

                </div>

                <Buton :loading="loaderButton" :string="port.id ? 'Modifier' : 'Ajouter'" :color="'green'" />


            </form>
        </Dialog>
        <Toast />
    </div>
</template>

<script setup>
import Buton from '../../components/Button.vue';
import { computed, onMounted, reactive, ref,watch } from 'vue';
import { useRoute } from 'vue-router';
import store from '../../store';
import common from '../../utils/common';
import { useToast } from 'primevue/usetoast';

const route = useRoute();
const chargeStationId = ref(0);
const charge_station = reactive({});
const ports = reactive([]);
const toast = useToast();
const loading = ref(false);
let port = reactive({ charging_station_id: chargeStationId });
const loaderButton = ref(false);
const user = computed(() => store.state.user.data);
const visible = ref(false);
let actualPort = {};

watch(visible, (newVal) => {
    if (!newVal) {
        port = { charging_station_id: chargeStationId };
        console.log("hy")
    }
});
onMounted(() => {
    chargeStationId.value = atob(route.params.portN);
    if (chargeStationId.value) getChargeStationPorts(chargeStationId.value);
})


function getChargeStationPorts(id) {
    store.dispatch("fetchChargeStationPorts", id).then((res) => {
        ports.length = 0;
        ports.push(...res.ports);
        delete res.ports;
        Object.assign(charge_station, res);
    }).catch(error => error).finally(() => loading.value = true);
}
function reserveThisPort(port) {
    if (port.status === "disponible") {
        visible.value = false;
        common
            .showSwal({
                title: "Êtes-vous sûr ?",
                text: "Vous ne pourrez pas revenir en arrière !",  // Translation of the Arabic text
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, réservez ceci",
            })
            .then((result) => {
                if (result.isConfirmed) {
                    store.dispatch("storeReservation", { port_id: port.id }).then((res) => {
                        if (res) replacePortItem(port.id, res);

                    });
                }
            }).finally(() => document.body.style.cursor = "wait");
    } else {
        visible.value = false;
        common.showToast({ title: "Ce port est déja réservé !!", icon: "warning" });;
    }
}

function deletePort(id) {
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

                store.dispatch("destroyPort", id).then((res) => {
                    if (res) {
                        common.showToast({ title: res.message, icon: "success" });
                        const index = ports.findIndex((p) => p.id === id);
                        if (index != -1)
                            ports.splice(index, 1);
                    }
                });
            }
        }).finally(() => document.body.style.cursor = "default");
}
function updatePort() {
    loaderButton.value = true;

    store
        .dispatch("updatePort",
            port
        )
        .then((res) => {
            if (res && res.status === 200 && res.data) {
                make_changes(res, "put");
            }
            else {
                loaderButton.value = false;

                common.showValidationErrors(res, toast);
            }
        })
        .finally(() => loading.value = true);

}
function editPort(id) {
    document.body.style.cursor = 'wait';

    store
        .dispatch("editPort", id)
        .then((res) => {
            if (res.status === 200 && res.data) {
                Object.assign(port, res.data);


                visible.value = true;
            }
        })
        .finally(() => document.body.style.cursor = "default");

}
function markAsNotReserved(id) {
    visible.value = false;
    common
        .showSwal({
            title: "Ce port est déjà réservé !",
            text: "Voulez-vous ouvrir la réservation de ce port ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, ouvrir",
            cancelButtonText: "Annuler"
        })
        .then((result) => {
            if (result.isConfirmed) {
                store.dispatch("changePortStatus", id).then((res) => {
                    if (res) replacePortItem(id, res);



                });
            }
        }).finally(() => document.body.style.cursor = "default");

}
function markAsDisponible(id) {
    visible.value = false;
    common
        .showSwal({
            title: 'Êtes-vous sûr ?',
            text: 'Le statut de ce port sera mis à jour en "Disponible".',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, retourner au travail',
            cancelButtonText: 'Annuler'
        })
        .then((result) => {
            if (result.isConfirmed) {
                document.body.style.cursor = "wait";
                store.dispatch("changePortStatus", id).then((res) => {
                    if (res) replacePortItem(id, res);
                });
            }
        }).finally(() => document.body.style.cursor = "default");

}
function switchActions() {
    if (port.id) {
        updatePort(); // If there's an ID, update the charge_station
    } else {
        addPort(); // If `visible2` is true, add a new charge_station
    }
}

function replacePortItem(id, res) {
    common.showToast({ title: res.message, icon: "success" });
    const index = ports.findIndex((p) => p.id === id);
    if (index !== -1)
        ports[index] = res.port;
    document.body.style.cursor = "default";
}



function addPort() {
    visible.value = true;

    loaderButton.value = true;


    store
        .dispatch("storePort", port)
        .then((res) => {

            if (res && res.status === 201 && res.data) {

                make_changes(res, "store");
            }
            else {
                common.showValidationErrors(res, toast);
                loaderButton.value = false;
            }
        })
        .finally(() => loading.value = true);

}
function make_changes(res, method) {
    loaderButton.value = false;
    port = { charging_station_id: chargeStationId.value };

    visible.value = false;
    common.showToast({ title: res.data.message, icon: "success" });
    if (method === 'put') {
        const index = ports.findIndex(p => p.id === res.data.port.id);
        if (index !== -1)
            ports[index] = res.data.port;



    } else
        ports.push(res.data.port);


}
</script>
