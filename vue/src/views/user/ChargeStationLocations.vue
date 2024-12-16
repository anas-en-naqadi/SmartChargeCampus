<template>
    <div class="card mt-12 m-6 bg-white border border-gray-300 rounded-xl">
        <div class="p-8 flex flex-col gap-5">
            <h2 class="mb-1 text-2xl font-extrabold tracking-tight text-gray-900 ">
                Post Charging Locations
            </h2>
            <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 " role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Danger</span>
                <div>
                    <span class="font-medium">Assurez-vous que ces exigences sont remplies :</span>
                    <ul class="mt-1.5 list-disc list-inside">
                        <li>Vous avez donné l'accès à votre localisation</li>
                        <li>1 seul clic pour afficher des informations sur la station de la charge</li>
                        <li>2 clics pour voir les ports de la station de la charge choisie</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Data Table -->
        <div v-if="!loading">
            <GoogleMap :api-key="googleMapsApiKey" style="width: 100%; height: 35rem" class="p-10 rounded-md -mt-10"
                :center="center" :zoom="15" >
                <MarkerCluster>
                    <Marker v-for="(charge_station, index) in charge_stations" :key="index"
                        @dblclick="goToPorts(charge_station.id)"
                        :options="{ position: charge_station.position, label: charge_station.name, title: 'Chargez votre équipement ici' }">
                        <InfoWindow>
                            <div id="content">
                                <div id="bodyContent">
                                    <h1 class="font-bold">{{ charge_station.name }}</h1>
                                    <span>Numero de Ports: {{ charge_station.total_ports }}</span><br>
                                    <span>{{ charge_station.description }}</span><br>
                                    <span>Maroc</span>
                                </div>
                            </div>
                        </InfoWindow>
                    </Marker>
                </MarkerCluster>
            </GoogleMap>
        </div>

        <!-- Skeleton Loader while loading -->
        <div v-else class="p-10 -mt-10">
            <Skeleton width="100%" height="35rem" />
        </div>
    </div>
</template>

<script setup>
import { GoogleMap, Marker, InfoWindow, MarkerCluster } from 'vue3-google-map'
import { useRouter } from 'vue-router';
import { onMounted, reactive, ref } from 'vue';
import common from '../../utils/common';
import store from '../../store';
const googleMapsApiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
const center = ref({ lat: 0, lng:0});
const charge_stations = reactive([]);
const loading = ref(true);
const router  = useRouter();



onMounted(()=>{
    getChargeStationLocations();
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {

                // Set the center to user's location if geolocation is available
                center.value = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
            },
            (error) => {
                common.showToast({ title: "S'il vous plaît, donnez l'accès à votre localisation" ,icon:"warning"});
            }
        );
    } else {
        alert("deosnt support by your browser");
    }
})
// Loading state for skeleton loader
function getChargeStationLocations() {
    store.dispatch("fetchChargeStationLocations").then(res => {
        charge_stations.splice(0, res.length, ...res);
        formatPosition(charge_stations);
    }).catch(error => error).finally(() => loading.value = false);
}

function formatPosition(locations) {
    locations.forEach((loc) => {
        const position = { lat: parseFloat(loc.latitude), lng: parseFloat(loc.longitude) };
        loc.position = position; // Direct assignment to a plain object
        delete loc.latitude;
        delete loc.longitude;
    });
}

function goToPorts(Nport){
   var cryptedPortNumber = btoa(Nport);
    router.push({ name: 'ports', params: { portN: cryptedPortNumber }});
}

</script>
