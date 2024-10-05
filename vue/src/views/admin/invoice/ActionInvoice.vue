<template>
  <div class="card mx-5 mt-12 bg-white border border-gray-300 rounded-xl">
    <!-- row -->

    <div class="flex flex-wrap flex-row ">
      <div id="title-invoice" class="flex justify-between max-w-full px-4 py-4 w-full">
        <p class="text-xl font-bold mt-3 mb-5">Invoice #{{ Math.floor(Math.random()) }}</p>
        <button type="button" id="btn-invoice" @click="doAction"
          class="py-2 px-4 inline-block text-center mb-3 rounded leading-5 text-green-800 bg-white border border-green-700 hover:text-white hover:bg-green-600 hover:ring-0 hover:border-white focus:outline-none focus:ring-0">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="ltr:mr-2 rtl:ml-2 inline-block bi bi-printer mr-2" viewBox="0 0 16 16">
            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"></path>
            <path
              d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z">
            </path>
          </svg>{{ actionButton }}
        </button>
      </div>

      <div class="flex-shrink max-w-full px-4 w-full mb-6" id="invoice" v-if="!loading">
        <div class="p-6 bg-white white:bg-gray-800 rounded-lg shadow-lg">
          <div class="flex justify-between items-center pb-4 border-b border-gray-200 white:border-gray-700 mb-3">
            <div class="flex flex-col gap-3">
              <div class="text-3xl flex items-center gap-2 font-bold mb-1">
                <img class="w-[10rem] h-[8rem] rounded-md ltr:mr-2 rtl:ml-2" src="@/assets/images/mahali.PNG" /><span>Al
                  Mobarka</span>
              </div>
              <p v-if="user.default_shipping_address" class="text-sm -mt-4 ml-10">
                {{ user.default_shipping_address.address }}<br />{{ user.default_shipping_address.city }}, {{
                  user.default_shipping_address.postal_code }}, {{ user.default_shipping_address.country }}
              </p>
            </div>
            <div class="text-4xl uppercase font-bold text-green-800">
              Facture
            </div>
          </div>
          <div class="flex flex-row justify-between py-3">
            <div class="flex-1">
              <span>
                <strong class="text-green-700 text-lg">Bill to:</strong>
                <select @change="fillShipping($event)" id="countries" v-model="invoice.user_id"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5">
                  <option selected value="null">Choose Customer</option>
                  <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                    {{ customer.name }}
                  </option>
                </select><br />
                <div v-if="customer.length && customer[0].shippings.length">
                  {{ customer[0].shippings[0].address }}<br />
                  {{ customer[0].shippings[0].city }},
                  {{ customer[0].shippings[0].postal_code }},
                  {{ customer[0].shippings[0].country }}<br />
                </div>
                <span class="text-red-600 text-sm" v-else>No addrress included !!</span>
                <div v-if="customer.length">
                  {{ customer[0].email }}<br />
                  {{ customer[0].phone }}
                </div>
              </span>
            </div>
            <div class="flex-1">
              <div class="flex justify-between mb-2">
                <div class="flex-1 font-semibold">Facture ID#:</div>
                <div class="flex-1 ltr:text-right rtl:text-left">FAC1</div>
              </div>
              <div class="flex justify-between mb-2">
                <div class="flex-1 font-semibold">Date Facture :</div>
                <div class="flex-1 ltr:text-right rtl:text-left">
                  <div class="flex-1 ltr:text-right rtl:text-left">
                    {{ common.formatDate(new Date()) }}
                  </div>
                </div>
              </div>
              <div class="flex justify-between mb-2">
                <div class="flex-1 font-semibold">Due date:</div>
                <div class="flex-1 ltr:text-right rtl:text-left">
                  <input type="date" id="due-date" v-model="invoice.due_date"
                    class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500" />
                </div>
              </div>
              <div class="flex justify-between mb-2">
                <div class="flex-1 font-semibold">Status #:</div>
                <select id="status" v-model="invoice.status"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2">
                  <option selected value="">Choose Status</option>
                  <option value="paid">Paid</option>
                  <option value="pending">Pending</option>
                </select>
              </div>
              <div class="flex justify-between mb-2">
                <div class="flex-1 font-semibold">Payment #:</div>
                <div class="flex-1 ltr:text-right rtl:text-left">COD</div>
              </div>
            </div>
          </div>
          <div class="py-4 flex flex-col gap-4">
            <div class="flex justify-end mr-3">
              <button @click="addRow" class="bg-green-600 flex gap-2 text-center text-white p-2 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Add product
              </button>
            </div>
            <table class="table-bordered w-full ltr:text-left rtl:text-right text-gray-600">
              <thead class="border-b white:border-gray-700">
                <tr class="bg-green-800 h-9 text-white white:bg-gray-900 white:bg-opacity-20">
                  <th>Products</th>
                  <th class="text-center">Qty</th>
                  <th class="text-center">Unit price</th>
                  <th class="text-center">Amount</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, index) in rows" :key="index" class="border-b-2 border-green-500">
                  <td>
                    <div class="flex flex-wrap gap-8 m-2 flex-row items-center">
                      <select @change="findPrice($event, index)" v-model="rows[index].product_id" name="product_id[]"
                        id="products"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-[70%] p-2.5">
                        <option selected value="0">Choose Product</option>
                        <option v-for="product in products" :key="product.id" :value="product.id">
                          {{ product.product_name }}
                        </option>
                      </select>
                    </div>
                  </td>
                  <td class="text-center">
                    <input v-model="rows[index].quantity" @input="totalAmount(index)" type="text" name="quantity[]"
                      id="product-quantity"
                      class="block w-[70%] p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-sm focus:ring-blue-500 focus:border-blue-500" />
                  </td>
                  <td class="text-center">
                    <input v-model="rows[index].price" type="text" @input="totalAmount(index)" name="price[]"
                      id="product-price"
                      class="block w-[70%] p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-sm focus:ring-blue-500 focus:border-blue-500" />
                  </td>
                  <td class="text-center">
                    <input v-model="rows[index].amount" type="text" name="amount[]" id="product-amount"
                      class="block w-[70%] p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-sm focus:ring-blue-500 focus:border-blue-500" />
                  </td>
                  <td class="text-center">
                    <button @click="deleteRow(index)">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-7 h-7 text-red-800">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                      </svg>
                    </button>
                  </td>
                </tr>
              </tbody>
              <tr class="h-6"></tr>
              <tfoot>
                <tr class="mt-3">
                  <td colspan="2"></td>
                  <td class="text-center"><b>Sub-Total</b></td>
                  <td class="text-center">{{ subTotal }} DH</td>
                </tr>
                <tr>
                  <td colspan="2"></td>
                  <td class="text-center"><b>Discount</b></td>
                  <td class="text-center">DH</td>
                </tr>
                <tr>
                  <td colspan="2"></td>
                  <td class="text-center"><b>Tax</b></td>
                  <td class="text-center">DH</td>
                </tr>
                <tr>
                  <td colspan="2"></td>
                  <td class="text-center"><b>Total</b></td>
                  <td class="text-center font-bold">{{ total }} DH</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <Toast />
  </div>
</template>


<script setup>
import { ref, onMounted, computed, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import common from "../../../utils/common";
import store from "../../../store";
import { useToast } from "primevue/usetoast";
const route = useRoute();
const customers = ref([]);
const customer = ref([]);
const toast = useToast();
const user = computed(() => store.state.user.data);

const router = useRouter();
const rows = ref([{ product_id: 0, quantity: 0, price: 0, amount: 0 }]);
const invoice = ref({
  status: "",
  user_id: null,
  due_date: null,
  products: rows.value,
});
let actionButton = "Ajouter facture";
const products = ref([]);
const loading = ref(true);

onMounted(() => {
  fetchCustomers();
  fetchProducts();

  if (route.params.id) {
    getInvoice();
    actionButton = "Modifier facture";
  }



});
watch(
  rows,
  (newVal) => {
    invoice.value.products = newVal;
  },
  { deep: true }
);

function getInvoice() {
  store.dispatch("getInvoiceById", route.params.id).then((res) => {
    if (res.status === 200 && res.data) {

      customer.value = customers.value.filter((c) => c.id === res.data.user_id);

      invoice.value.id = res.data.id;
      invoice.value.user_id = res.data.customer.id;
      invoice.value.due_date = new Date().toISOString().slice(0, 10);
      invoice.value.status = "paid";
      rows.value = [];
      res.data.sells.forEach((item, index) => {
        rows.value.push({
          product_id: item.product_id,
          quantity: item.quantity,
          price: item.product.price,
          amount: 0,
        });
        totalAmount(index);
      });

    }

  });
}

function fetchCustomers() {
  store
    .dispatch("getCustomers")
    .then((res) => {
      if (res) customers.value = res.filter((c) => c.id !== store.state.user.data.id);
    })
    .catch((error) => console.error(error));
}
function fetchProducts() {
  store
    .dispatch("getProducts")
    .then((res) => {
      if (res) products.value = res;
    })
    .catch((error) => console.error(error))
    .finally(() => {
      loading.value = false;
      console.log(loading.value);
    });
}
function fillShipping(event) {
  const id = event.target.value;

  customer.value = customers.value.filter((c) => c.id == id);
}

function deleteRow(index) {
  rows.value.splice(index, 1);
}

function addRow() {
  rows.value.push({ product_id: 0, quantity: 0, price: 0, amount: 0 });
}
function doAction() {
  route.params.id ?
    updateInvoice()
    :
    storeInvoice();

}
function storeInvoice() {
  store
    .dispatch("storeInvoice", invoice.value)
    .then((res) => {
      if (res.status === 200 && res.data) {
        common.showToast({ title: res.data.message, icon: "success" });

        router.push({ name: 'invoice-details', params: { id: res.data.id } });
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

    })
    .catch((error) => console.log(error));
}

function updateInvoice() {
  invoice.value.products = rows.value;
  store
    .dispatch("updateInvoice", { invoice: invoice.value, id: invoice.value.id })
    .then((res) => {
      if (res.status === 200 && res.data)
        common.showToast({ title: res.data.message, icon: "success" });

      router.push({ name: 'invoice-details', params: { id: res.data.id } });

    })
    .catch((error) => console.log(error));
}
function findPrice(event, index) {
  const id = event.target.value;

  if (id !== "0") {
    const price = products.value.filter((p) => {
      if (p.id == id) return p;
    })[0].price;

    rows.value[index].price = price;
    totalAmount(index);
  } else rows.value[index].price = 0;
}
const subTotal = computed(() => {
  return common.formatNumber(
    rows.value
      .reduce((acc, r) => {
        return acc + parseFloat(r.amount);
      }, 0)
      .toFixed(2)
  );
});
function totalAmount(index) {
  const total =
    parseFloat(rows.value[index].price || 0) *
    parseFloat(rows.value[index].quantity || 0);
  rows.value[index].amount = total.toFixed(2);
}
const total = computed(() => {
  return 0;
});
</script>
