

<template>
    <div class="card mx-5 mt-12 bg-white border border-gray-300 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-gray-900 p-8">
                لائحة الزبناء
            </h2>

        </div>
        <!-- Main Data Table -->
        <div v-if="!loading">
            <DataTable @rowExpand="totalOwn" :value="filteredClients" v-model:expandedRows="expandedRows" removableSort
                :paginator="true" class="p-datatable-sites -mt-5 m-6" showGridlines :rows="10" dataKey="id"
                tableStyle="min-width: 30rem" stripedRows responsiveLayout="scroll" breakpoint="960px"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="عرض {first} إلى {last} من {totalRecords} الزبناء">
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
                <template #empty>لا يوجد عملاء</template>
                <Column expander style="width: 5rem" />


                <Column field="name" headerStyle="" class="border-b-[1px] text-center" header="إسم الزبون">
                </Column>
                <Column field="cni" class="border-b-[1px] w-[5rem] text-center" header="رقم بطاقة الهوية ">
                </Column>
                <Column field="phone" class="border-b-[1px] w-[5rem] text-center" header="رقم الهاتف">
                </Column>

                <Column field="created_at" class="border-b-[1px] text-center w-full m-auto " header="أضيف في"  sortable>
                    <template #body="{ data }">
                        <span>{{ common.formatDate(data.created_at) }}</span>
                    </template>
                </Column>

                <template #expansion="{data}">
                    <div class="p-2.5">

                        <div :class="[
    data.sells.length ? 'mb-4' : '-mb-10' ,
    'flex',
    'justify-between',
    'items-center',
    '-mt-6',

]">

                            <p class="mb-4 text-2xl flex font-extrabold p-8">


                                <span class="text-2xl font-bold text-blue-800">{{ common.formatNumber(total) }}
                                    DH</span>
                                <span class="ml-1"> : إجمالي الدين</span>
                            </p>
                            <p class="mb-4 text-2xl flex font-extrabold p-8">


                                <span class="text-2xl font-bold text-blue-800">{{ common.formatNumber(TotalBought) }}
                                    DH</span>
                                <span class="ml-1">: مجموع الحساب
                                    </span>
                            </p>
                        </div>
                        <DataTable v-if="data.sells.length" :value="data.sells" removableSort :paginator="true"
                            class="p-datatable-sites -mt-5 m-6 " showGridlines :rows="10" dataKey="id"
                            tableStyle="min-width: 30rem;" stripedRows
                            :rowClassName="'border-t text-center border-gray-200'" filterDisplay="menu"
                            responsiveLayout="scroll" breakpoint="960px"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            currentPageReportTemplate="عرض {first} إلى {last} من أصل {totalRecords} مبيعات">
                            <template #header>
                                <div
                                    class="flex flex-col flex-wrap md:flex-row md:justify-between w-full md:items-center gap-5">


                                    <div class="flex flex-col md:flex-row gap-5">
                                        <input v-model="date.start_date" type="date"
                                            class="xl:w-[12rem] p-2 border-gray-300  focus:border-blue-500" />
                                        <input v-model="date.end_date" type="date"
                                            class="p-2 xl:w-[12rem] focus:border-blue-500 border-gray-300 " />

                                        <Button type="button" icon="pi pi-filter-slash" label="فلترة"
                                            class="p-button-outlined bg-white py-2 px-4 border border-green-500 rounded-md text-green-800 hover:text-white hover:bg-green-500"
                                            @click="filterSells()" />
                                    </div>

                                    <Button type="button" icon="pi pi-filter-slash" label="إزالة"
                                        class="p-button-outlined bg-white py-2 px-4 border border-blue-500 rounded-md text-blue-800 hover:text-white hover:bg-blue-500 mt-2 md:mt-0"
                                        @click="clearFilter()" />
                                </div>
                            </template>
                            <template #empty>.لا توجد مبيعات</template>
                            <!-- Columns -->
                            <Column field="client" class="border-b-[1px] text-center" header="اسم الزبون">
                                <template #body="{data}">
                                    <span>{{ data.client.name }}</span>
                                </template>
                            </Column>

                            <Column field="total_price" class="border-b-[1px] text-center" header="مجموع الحساب"
                                sortable>
                                <template #body="{ data }">
                                    <span>{{ common.formatNumber(data.total_price) }} درهم</span>
                                </template>
                            </Column>

                            <Column field="paid_price" header="الدفع" sortable>
                                <template #body="{ data }">
                                    <span>{{ common.formatNumber(data.paid_price) }} درهم</span>
                                </template>
                            </Column>
                            <Column field="remaining_amount" class="border-b-[1px] text-center" header="الدين" sortable>
                                <template #body="{ data }">
                                    <span>{{ common.formatNumber(data.remaining_amount) }} درهم</span>
                                </template>
                            </Column>
                            <Column field="status" class="border-b-[1px] text-center" header="الحالة" sortable>
                                <template #body="{ data }">
                                    <span :class="[
                                        data.status === 'مدفوع' ? 'bg-green-500' :
                                            (data.status === 'متبقي' ? 'bg-orange-500' : 'bg-red-500'),
                                        'text-sm', 'text-white', 'rounded-md', 'px-1.5', 'py-1'
                                    ]">
                                        {{ data.status }}
                                    </span> </template>
                            </Column>
                            <Column field="check" class="border-b-[1px] text-center" header="الشيك" sortable>
                                <template #body="{ data }">
                                    <span>{{ data.check ? "نعم" : "لا"}}</span>
                                </template>
                            </Column>
                            <Column field="check" class="border-b-[1px] text-center" header="تاريخ دفع الشيك" sortable>
                                <template #body="{ data }">
                                    <span>{{ data.check ? common.formatDate(data.check_date) : "-" }}</span>
                                </template>
                            </Column>

                            <Column header="الطباعة" class="border-b-[1px] text-center">
                                <template #body="{ data }">
                                    <div class="flex gap-3">

                                        <button title="Edit this product" @click="editProduct(data.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                        <button title="Delete this product" @click="deleteProduct(data.id)">
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
                </template>

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
                <Column field="name" header="إسم الزبون">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="phone" headerStyle="text-align:center" class="border-b-[1px] text-center"
                    header="رقم الهاتف">
                    <template #body>
                        <Skeleton></Skeleton>
                    </template>
                </Column>
                <Column field="name" header="أضيف في" class="text-center flex items-center justify-center">
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
import { ref, onMounted } from "vue";
import common from "../../utils/common";
import { useToast } from "primevue/usetoast";
const clients = ref([]);
const user = ref({ is_admin: " ", name: "", email: "", phone: "" });
const visible1 = ref(false);
const visible2 = ref(false);
const loading = ref(false);

const filteredClients = ref([]);
const expandedRows = ref([]);

const toast = useToast();

const date = ref({});
const total = ref(0);
const TotalBought = ref(0);
onMounted(() => {
  fetchClients();
});
function totalOwn(event){
    total.value =0;
    TotalBought.value = 0
    const user = event.data;
    total.value = user.sells.reduce((acc, sell) => acc + sell.remaining_amount, 0);
    TotalBought.value = user.sells.reduce((acc, sell) => acc + sell.total_price, 0);

}
function clearFilter() {
  document.getElementById("searchInput").value = "";
  filteredClients.value = clients.value;
}
function fetchClients() {
  loading.value = true;
  store
    .dispatch("fetchClients")
    .then((res) => {
      clients.value = res;
      filteredClients.value = [...clients.value];
    })
    .catch((error) => console.error(error))
    .finally(() => {
      loading.value = false;
    });
}

function filterTable(event) {
  const filter = event.target.value.toLowerCase();

  if (!filter) filteredClients.value = clients.value;
  else
    filteredClients.value = clients.value.filter(
      (u) =>
        u.cni.toLowerCase().includes(filter) ||
        u.name.toLowerCase().includes(filter) ||
        u.phone.toLowerCase().includes(filter)
    );
}

function changeStatus(id, status) {
  common
    .showSwal({
        title: "هل أنت متأكد؟",
        text: "! لن تتمكن من التراجع عن هذا",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: `yes, ${
        status === "inactive" ? "deactivate" : "active"
      } it !`,
    })
    .then((result) => {
      if (result.isConfirmed) {
        store
          .dispatch("changeUserStatus", { user_id: id, status: status })
          .then((res) => {
            if (res.status === 200 && res.data) {
              fetchClients();
              common.showToast({ title: res.data.message, icon: "success" });
            } else if (res.response.status === 500)
              common.showToast({
                title: res.response.data.message,
                icon: "error",
              });
          });
      }
    });
}

function showDialog(id) {
  visible1.value = true;
  userId = id;
}


function newUser() {
  store.dispatch("storeUser",user.value).then((res) => {
    console.log(res)
    if (res.status === 200 && res.data) {
      fetchClients();
      visible2.value = false ;
      common.showToast({ title: res.data.message, icon: "success" });
    }
    if (res.response && res.response.status === 422) {
      [...Object.values(res.response.data.errors)].forEach((e) => {
        toast.add({
          severity: "error",
          summary: "Error",
          detail: e[0],
          life: 3000,
        });
      });
    }
  });
}
// Define skeleton data
const skeletonObjects = new Array(10);
</script>
