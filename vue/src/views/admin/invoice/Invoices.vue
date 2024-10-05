<template>
  <div class="card mx-5 mt-12 bg-white border border-gray-300 rounded-xl">
    <div class="flex justify-between items-center mb-4">
      <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
        List of invoices
      </h2>
      <router-link
        :to="{name:'action-invoice'}"
        class="p-button-outlined h-10 bg-white py-2 px-3 mr-6 border border-black rounded-md text-black hover:text-white hover:bg-black"
      >+ nouveau facture</router-link>
    </div>
    <!-- Main Data Table -->
    <div v-if="!loading">
      <DataTable
        :value="filteredInvoices"
        removableSort
        :paginator="true"
        class="p-datatable-sites -mt-5 m-6"
        showGridlines
        :rows="10"
        dataKey="id"
        tableStyle="min-width: 30rem"
        stripedRows
        filterDisplay="menu"
        responsiveLayout="scroll"
        breakpoint="960px"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} invoices"
      >
      <template #header>
                    <div class="flex flex-col flex-wrap md:flex-row md:justify-between w-full md:items-center gap-5">
                        <div class="relative mb-2 md:mb-0 md:mr-5">
                            <i
                                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600"></i>
                            <InputText @input="filterTable($event)" placeholder="Search" id="searchInput"
                                class="pl-10 py-2 border-gray-300" />
                        </div>

                        <div class="flex flex-col md:flex-row gap-5">
                            <input v-model="date.start_date" type="date"  class="xl:w-[12rem] p-2 border-gray-300  focus:border-blue-500" />
                            <input v-model="date.end_date" type="date" class="p-2 xl:w-[12rem] focus:border-blue-500 border-gray-300 " />

                            <Button type="button" icon="pi pi-filter-slash" label="Filter"
                                class="p-button-outlined bg-white py-2 px-4 border border-green-500 rounded-md text-green-800 hover:text-white hover:bg-green-500"
                                @click="filterInvoices()" />
                        </div>

                        <Button type="button" icon="pi pi-filter-slash" label="Clear"
                            class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500 mt-2 md:mt-0"
                            @click="clearFilter()" />
                    </div>
                </template>
        <template #empty>No invoices list found.</template>

        <!-- Columns -->
        <Column
          field="id"
          class="border-b-[1px] text-center"
          header="ID"
        ></Column>
        <Column
          field="customer_name"
          class="border-b-[1px] text-center"
          header="Creer a"
        ></Column>

        <Column
          field="sell_count"
          class="border-b-[1px] text-center"
          header="Nombre de Produit"
        >
    </Column>
        <Column field="total" class="border-b-[1px] text-center" header="Total">
          <template #body="{ data }">
            <span>{{ common.formatNumber(data.total) }} DH</span>
          </template>
        </Column>
        <Column
          field="created_at"
          class="border-b-[1px] text-center"
          header="creer a"
        >
          <template #body="{ data }">
            <span>{{ common.formatDate(data.created_at) }}</span>
          </template>
        </Column>

        <Column header="Actions" class="border-b-[1px] text-center">
          <template #body="{ data }">
            <div class="flex gap-3">
              <button @click="showInvoice(data.id)" title="Voir cette facture">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-6 h-6 text-blue-500"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                  />
                </svg>
              </button>
              <button
                @click="editInvoice(data.id)"
                title="Modifier cette facture"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-6 h-6 text-green-500"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                  />
                </svg>
              </button>

              <button
                @click="deleteInvoice(data.id)"
                title="Supprimer cetter facture"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-6 h-6 text-red-500"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                  />
                </svg>
              </button>
            </div>
          </template>
        </Column>
      </DataTable>
    </div>
    <!-- Skeleton Data Table -->
    <div v-else>
      <DataTable
        :value="skeletonObjects"
        class="p-datatable-sites -mt-5 m-6"
        tableStyle="min-width: 30rem"
        :rowClassName="'border-t text-center border-gray-200'"
      >
        <template #header>
          <div
            class="flex justify-between items-center gap-5"
            style="display: flex"
          >
            <span class="relative">
              <i
                class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 white:text-surface-600"
              />
              <InputText placeholder="Search" class="pl-10 py-2" />
            </span>
            <Button
              type="button"
              icon="pi pi-filter-slash"
              label="Clear"
              class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500"
            />
          </div>
        </template>
        <Column field="id" header="Id">
          <template #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column field="customer_name" header="Creer a">
          <template #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column field="sell_count" header="Nombre de Produit">
          <template #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column field="total" header="Total">
          <template #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column field="created_at" header="Creer le">
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
    <Toast />
  </div>
</template>

<script setup>
import store from "../../../store";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import common from "../../../utils/common";
import { useToast } from "primevue/usetoast";
const invoices = ref([]);
const router = useRouter();
const loading = ref(false);
const date = ref({});
const toast =useToast();
const filteredInvoices = ref([]);
onMounted(() => {
  fetchInvoices();
});
function filterInvoices(){
    store.dispatch("filterByDates",{date:date.value,type:"invoice"}).then((res)=>{

        if(res.status ===200 && res.data )
        filteredInvoices.value =[...res.data];
        if (res.response && res.response.status === 422){

[...Object.values(res.response.data.errors)].forEach((e) => {
      toast.add({
        severity: "error",
        summary: "Error",
        detail: e[0],
        life: 3000,
      });
    });
    }
    }).catch(error=>error);
}
function fetchInvoices() {
  loading.value = true;
  store
    .dispatch("getInvoices")
    .then((res) => {
      invoices.value = res;
      filteredInvoices.value = [...res];
    })
    .catch((error) => console.error(error))
    .finally(() => {
      loading.value = false;
    });
}
function clearFilter() {
  document.getElementById("searchInput").value = "";
  date.value ={};
  filteredInvoices.value = invoices.value;
}
function filterTable(event) {
  const filter = event.target.value.toLowerCase();

  if (!filter) filteredInvoices.value = invoices.value;
  else
    filteredInvoices.value = invoices.value.filter(
      (i) =>  i.customer_name.toLowerCase().includes(filter)
    );
}



function deleteInvoice(id) {
  common
    .showSwal({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "yes, delete it !",
    })
    .then((result) => {
      if (result.isConfirmed) {
        console.log("Product deleted:", id);
        common.showSwal({
          title: "Success!",
          text: "this account has been deleted successfully .",
          icon: "success",
          showConfirmButton: false,
        });
      }
    });
}
function showInvoice(id) {
  router.push({ name: "invoice-details", params: { id: id } });
}
function editInvoice(id) {
  router.push({ name: "edit-invoice", params: { id: id } });
}
// Define skeleton data
const skeletonObjects = new Array(10);
</script>
