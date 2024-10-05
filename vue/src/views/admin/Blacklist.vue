<template>
    <div class="card mx-5 mt-12 bg-white border border-gray-300 rounded-xl">
        <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
            List of Blacklist
        </h2>
        <!-- Main Data Table -->
        <div v-if="!loading">
            <DataTable :value="filteredBlacklist" removableSort :paginator="true" class="p-datatable-sites -mt-5 m-6"
                showGridlines :rows="10" dataKey="id" tableStyle="min-width: 30rem" stripedRows
                responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Blacklist">
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
                <template #empty>No Blacklist list found.</template>

                <!-- Columns -->
                <Column field="id" class="border-b-[1px] text-center" header="ID"></Column>
                <Column field="avatar" header="Avatar" class="border-b-[1px] text-center" style="min-width: 12rem">
                    <template #body="{ data }">
                        <avatar :fullname="data.user.name" :size="63"></avatar>
                    </template>
                </Column>
                <Column field="name" class="border-b-[1px] text-center" header="Name">
                    <template #body="{ data }">
                        {{ data.user.name }}
                    </template>
                </Column>
                <Column field="email" class="border-b-[1px] text-center" header="Email">
                    <template #body="{ data }">
                        {{ data.user.email }}
                    </template>
                </Column>
                <Column field="status" class="border-b-[1px] text-center" header="Status" style="min-width: 8rem" sortable>
                    <template #body="{ data }">
                        <span class="py-1.5 px-1.5 text-xs rounded-md text-white"
                            :class="[data.user.status === 'active' ? 'bg-green-500 ' : (data.user.status === 'inactive' ? 'bg-red-500' : 'bg-gray-500')]">{{ data.user.status }}</span>
                    </template>
                </Column>
                <Column field="is_admin" class="border-b-[1px] text-center" header="Role" style="min-width: 6rem" sortable>
                    <template #body="{ data }">
                        <span class="py-1.5 px-1.5 text-xs rounded-md text-white"
                            :class="[data.user.is_admin !== 1 ? 'bg-blue-500 ' : 'bg-green-500 ']">{{ data.user.is_admin
                            !== 1 ? "user" : "admin" }}</span>
                    </template>
                </Column>

                <Column field="created_at" class="border-b-[1px] text-center" header="creer a">
                    <template #body="{ data }">
                        <span>{{ common.formatDate(data.created_at) }}</span>
                    </template>
                </Column>

                <Column header="Actions" class="border-b-[1px] text-center">
                    <template #body="{ data }">
                        <div class="flex gap-3">
                            <button @click="removeBlacklist(data.id)" title="Debloquer ce compte">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
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
                <Column field="id" header="Id">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="name" header="Avatar" class="text-center flex items-center justify-center">
                    <template #body>
                        <Skeleton shape="circle" size="5rem"></Skeleton>
                    </template>
                </Column>
                <Column field="name" header="Status">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="name" header="Role">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="email" header="Email">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="created_at" header="Register in">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>

                <Column field="" header="Actions">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
            </DataTable>
        </div>

    </div>
</template>

<script setup>
import store from "../../store";
import Avatar from "vue-avatar-component";
import { ref, onMounted } from "vue";
import common from "../../utils/common";
const blacklist = ref([]);
const visible = ref(false);
const loading = ref(false);
const filteredBlacklist = ref([]);
let userId;
let reason = "";

onMounted(() => {
    fetchBlacklist();
});

function clearFilter() {
    document.getElementById("searchInput").value = "";
    filteredBlacklist.value = users.value;
}
function fetchBlacklist() {
    loading.value = true;
    store
        .dispatch("getBlacklist")
        .then((res) => {
            blacklist.value = res;
            filteredBlacklist.value = [...blacklist.value];
        })
        .catch((error) => console.error(error))
        .finally(() => {
            loading.value = false;
        });
}

function filterTable(event) {
    const filter = event.target.value.toLowerCase();

    if (!filter) filteredUsers.value = users.value;
    else
        filteredUsers.value = users.value.filter(
            (u) =>
                u.name.toLowerCase().includes(filter) ||
                u.email.toLowerCase().includes(filter)
        );
}





function removeBlacklist(id) {
    common
        .showSwal({
            title: "هل أنت متأكد؟",
            text: "! لن تتمكن من التراجع عن هذا",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "yes, deblock!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                store.dispatch("destroyBlacklist", id).then((res) => {
                    if (res.status === 200 && res.data) {
                        fetchBlacklist();
                        common.showToast({ title: res.data.message, icon: "success" });
                    }
                });
            }
        });
}
// Define skeleton data
const skeletonObjects = new Array(10);
</script>
