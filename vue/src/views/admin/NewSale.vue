<template>
    <div class="flex -ml-[1rem]  w-full 2xl:flex-nowrap flex-wrap h-full gap-6 items-center justify-center">
        <!-- products container -->
        <div class="flex h-[130vh] flex-col border  w-full gap-6 mt-[2rem] rounded-md shadow-md bg-white p-6">
            <!-- search input-->
            <div>

                <form class="max-w-lg mx-auto">
                    <div class="flex">

                        <select @change="filterByCategory($event)" name="" id=""
                            class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 white:bg-gray-700 white:hover:bg-gray-600 white:focus:ring-gray-700 white:text-white white:border-gray-600">
                            <option value="null">جميع الفئات <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg></option>
                            <option v-for="(category, index) in categories" :value="category" :key="index">{{ category }}
                            </option>
                        </select>

                        <div class="relative w-full">
                            <input type="text" id="search-dropdown" @input="searchByName($event)"
                                class="block p-2.5 w-full z-20 text-sm text-gray-900 text-right pr-8 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 white:bg-gray-700 white:border-s-gray-700  white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:border-blue-500"
                                placeholder="البحث حسب اسم المنتجات"  />

                        </div>
                    </div>
                </form>

            </div>
            <!-- products grid -->
            <div v-if="products.length"
                class="grid grid-cols-2 overflow-y-auto overflow-x-hidden   sm:grid-cols-3 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
                <button @click="addProductToCart(product.id)" v-for="(product, index) in filteredProducts" :key="index"
                    class="bg-white rounded-lg shadow-xl flex w-48 h-auto flex-col items-center hover:bg-blue-100 transition duration-200 transform hover:scale-105">
                    <img :src="product.image" class="w-48 h-36 object-cover  rounded-md" alt="Product Image" />
                </button>
            </div>
            <div v-else
                class="grid grid-cols-2 overflow-y-auto overflow-x-hidden   sm:grid-cols-3 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
                <button @click="addProductToCart(product.id)" v-for="(product, index) in filteredProducts" :key="index"
                    class="bg-white rounded-lg shadow-xl flex w-48 h-auto flex-col items-center hover:bg-blue-100 transition duration-200 transform hover:scale-105">
                    <Skeleton width="12rem" height="9rem"></Skeleton>
                </button>
            </div>
        </div>
        <!--total container-->
        <div class="h-full">
            <div class="font-sans md:max-w-4xl max-md:max-w-xl mx-auto w-[53vh] bg-white shadow-md border rounded-md py-4 mt-10">
                <div class="flex flex-col ">
                    <div class="md:col-span-2 bg-white p-4 rounded-md">
                        <h2 class="text-2xl font-bold text-gray-800">Cart</h2>
                        <hr class="border-black mt-4 mb-8" />

                        <div v-if="carts.length"
                            class="space-y-4 w-full overflow-y-auto overflow-x-hidden max-h-[45vh] px-4">
                            <div v-for="product in carts" :key="product.id"
                                class="flex w-full mr-4 flex-col  items-center gap-4">

                                <div class=" w-full flex items-center gap-4">
                                    <div class="w-24 h-20 shrink-0 bg-white p-2 rounded-md">
                                        <img :src="product.image" class="w-full h-full object-cover rounded-md" />
                                    </div>

                                    <div>
                                        <div class="flex justify-between w-[25vh] items-center gap-2">
                                            <h3 class="text-base font-bold text-gray-800">{{ product.name }}</h3>

                                            <h4 class="text-base font-bold text-gray-800">{{ product.selling_price }} DH
                                            </h4>
                                        </div>

                                        <div class="flex gap-4 mt-4 w-full justify-between items-center">

                                            <button @click="deleteProductFromCart(product.id)"
                                                class="text-xs text-red-500 cursor-pointer mt-0.5">Remove</button>

                                            <div
                                                class="flex items-center gap-2 text-center px-2.5 py-1.5 border border-gray-300 text-gray-800 text-xs outline-none bg-transparent rounded-md">
                                                <svg @click="decrementQuantity(product.id)"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="w-2.5 fill-current cursor-pointer" viewBox="0 0 124 124">
                                                    <path
                                                        d="M112 50H12C5.4 50 0 55.4 0 62s5.4 12 12 12h100c6.6 0 12-5.4 12-12s-5.4-12-12-12z"
                                                        data-original="#000000"></path>
                                                </svg>

                                                <input v-model="product.quantity" type="text" value="1"
                                                    class="w-5 h-5 text-center" />
                                                <svg @click="incrementQuantity(product.id)"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="w-2.5 fill-current cursor-pointer" viewBox="0 0 42 42">
                                                    <path
                                                        d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z"
                                                        data-original="#000000"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>



                        </div>
                        <div v-else class="text-center">
                            لا يوجد منتج مضاف
                        </div>
                    </div>

                    <div class="bg-white rounded-md p-4 md:sticky top-0 flex flex-col gap-2">

                        <div class="flex gap-4 items-center text-right">
                            <form class="max-w-sm mx-auto w-full ">
                                <label for="first_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 white:text-white"> طريقة الدفع

                                </label>
                                <select v-model="sell.payment_method" id="payment_method"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500">
                                    <option value="null" selected>طريقة الدفع</option>
                                    <option value="شيك">شيك</option>
                                    <option value="نقدًا">نقدًا</option>
                                    <option value="كريدي">كريدي</option>
                                </select>
                            </form>
                            <div v-if="sell.payment_method == 'شيك'" class="w-full">
                                <label for="first_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 white:text-white">تاريخ دفع
                                    الشيك
                                </label>
                                <input v-model="sell.check_date" type="date" id="first_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                    placeholder="John" required />
                            </div>
                        </div>

                        <div class="flex gap-4 text-right">
                            <div class="w-full">
                                <label for="debt"
                                    class="block mb-2 text-sm font-medium text-gray-900 white:text-white ml-auto">الدين
                                </label>
                                <input v-model="remaining_amount" type="text" id="debt"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 w-full focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                    placeholder="John" required />
                            </div>

                            <div class="w-full">
                                <label for="first_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 white:text-white">الدفع
                                </label>
                                <input v-model="sell.paid_amount" type="text" id="first_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 w-full focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                    placeholder="John" required />
                            </div>

                        </div>

                        <div class="flex justify-between my-2">

                            <div class="flex gap-4">
                                <div class="flex items-center me-4">
                                    <input v-model="hasDebt" id="bordered-radio-3" type="radio" value="yes"
                                        name="bordered-radio1"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                                    <label for="bordered-radio-3"
                                        class="ms-2 text-sm font-medium text-gray-900">نعم</label>
                                </div>
                                <div class="flex items-center">
                                    <input v-model="hasDebt" id="bordered-radio-4" type="radio" value="no"
                                        name="bordered-radio1"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                                    <label for="bordered-radio-4" class="ms-2 text-sm font-medium text-black">لا</label>
                                </div>

                            </div>
                            <label for="inline-radio" class="ms-2 text-sm font-medium text-black "> هل الزبون لديه دين
                                ؟</label>

                        </div>
                        <div v-if="hasDebt == 'yes'">
                            <div class="flex justify-between my-2">

                                <div class="flex gap-4">
                                    <div class="flex items-center me-4">
                                        <input v-model="isClient" id="bordered-radio-2" type="radio" value="yes"
                                            name="bordered-radio"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  ">
                                        <label for="bordered-radio-2"
                                            class="ms-2 text-sm font-medium text-gray-900 ">نعم</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input v-model="isClient" id="bordered-radio-1" type="radio" value="no"
                                            name="bordered-radio"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  ">
                                        <label for="bordered-radio-1"
                                            class="ms-2 text-sm font-medium text-black ">لا</label>
                                    </div>
                                </div>
                                <label for="inline-radio" class="ms-2 text-sm font-medium text-black ">زبون جديد
                                    ؟</label>

                            </div>

                            <div v-if="isClient == 'yes'" class="w-full flex flex-col gap-4 pt-2 text-right">
                                <div class="flex gap-4 ">
                                    <div>
                                        <label for="name"
                                            class="block mb-2 text-sm font-medium text-gray-900 white:text-white">إسم
                                            الزبون
                                        </label>
                                        <input v-model="client.name" type="text" id="name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                            placeholder="أنس..." required />
                                    </div>

                                    <div>
                                        <label for="phone"
                                            class="block mb-2 text-sm font-medium text-gray-900 white:text-white">رقم
                                            الهاتف
                                        </label>
                                        <input v-model="client.phone" type="text" id="phone"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                            placeholder="+212 658...." required />
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div>
                                        <label for="cni"
                                            class="block mb-2 text-sm font-medium text-gray-900 white:text-white">رقم
                                            البطاقة
                                        </label>
                                        <input v-model="client.cni" type="text" id="cni"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                            placeholder="M30802..." required />
                                    </div>
                                    <div>
                                        <label for="total_credit"
                                            class="block mb-2 text-sm font-medium text-gray-900 white:text-white"> مجموع
                                            الدين
                                        </label>
                                        <input v-model="client.total_credit" type="text" id="total_credit"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                            placeholder="2093.43" required />
                                    </div>
                                </div>
                            </div>
                            <form v-else-if="isClient == 'no'" class="max-w-sm mx-auto mt-5 w-full">

                                <select v-model="sell.client_id" id="clients"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500">
                                    <option selected> الزبائن</option>
                                    <option v-for="client in clients" :value="client.id" :key="client.id">{{ client.name
                                        }}
                                    </option>
                                </select>
                            </form>
                        </div>



                        <div class="flex flex-wrap gap-4 text-base font-bold">Total <span class="ml-auto">{{
                            common.formatNumber(sell.total_price) }} درهم</span></div>
                               <div v-if="sell.remaining_amount <0" class="flex flex-wrap gap-4 text-base font-bold">Exchange <span class="ml-auto">{{
                            common.formatNumber(remaining_amount) }} درهم</span></div>

                        <Button @trigger-event="addNewSell()" string="إضافة" color="green" :loading="loaderButton"
                           />

                    </div>
                </div>
            </div>

        </div>
        <Toast />
    </div>
</template>

<script setup>
import store from "../../store";
import common from "../../utils/common";
import Button from "../../components/Button.vue";
import { computed, ref, onMounted, watch } from "vue";
import { useToast } from "primevue/usetoast";
const products = computed(() => store.state.products);
const carts = ref([]);
const toast = useToast();
const client = ref({});
const sell = ref({payment_method:null,client_id:null,remaining_amount:0,paid_amount:0});
const hasDebt = ref("");
const loaderButton = ref(false);
const categories = computed(() => {
    if (products.value.length) {
        var categoryNames = products?.value
            .map((p) => p.category_name) // Extract category names
            .filter((name, index, self) => name && self.indexOf(name) === index); // Filter out duplicates and ensure name is not empty
    }


    return categoryNames;
});

const clients = computed(() => store.state.clients);
const isClient = ref("");
const filteredProducts = ref([]);
watch(products, (newValue) => {
    filteredProducts.value = newValue;
});
sell.value.total_price = computed(() =>
    carts.value.reduce((acc, c) => acc + (parseFloat(c.selling_price) * (c.quantity || 1)), 0)
);
const remaining_amount = computed(() => {
    const totalValue = parseFloat(sell.value.total_price) || 0; // Ensure total is a number
    const paymentValue = parseFloat(sell.value.paid_amount) || 0; // Ensure payment is a number
    const result = totalValue - paymentValue
    client.value.total_credit = parseFloat(common.formatNumber(result));
    return common.formatNumber(result);
});
watch(remaining_amount,(newValue)=>{

    if(parseFloat(newValue) <0){

        sell.value.change = newValue;
        sell.value.remaining_amount = Math.max(0,parseFloat(newValue));

    }
else
sell.value.remaining_amount = parseFloat(newValue);

})
function addNewSell(){
    loaderButton.value = true;
   const clientData =  client.value;
   console.log(client.value);
   sell.value.check = sell.value.check == 'شيك' ? true  :false;
   sell.value.change = parseFloat(sell.value.remaining_amount) >=0 ? 0 : parseFloat(sell.value.remaining_amount) ;
      store.dispatch("storeSell",{products :carts.value,client :clientData,sell:sell.value}).then((res)=>{
        if(res && res.status === 201 && res.data){
            common.showToast({ title: res.data.message, icon: "success" });
             sell.value ={payment_method:null,client_id:null};
             client.value = {};
             carts.value = [];
            isClient.value="";
            hasDebt.value = "";

        }

            else
            common.showValidationErrors(res,toast);
    }).finally(()=>loaderButton.value=false);
}

function filterByCategory(event) {
    const category = event.target.value;
    if (category === "null")
        filteredProducts.value = [...products.value];
    else
        filteredProducts.value = products.value.filter((p) => p.category_name === category);
}


function addProductToCart(id) {
    const product = products.value.find((p) => p.id == id);
    if (product) {
        const cartProduct = { ...product, quantity: 1 };
        carts.value.push(cartProduct);
    }

}
function deleteProductFromCart(id) {
    const index = carts.value.findIndex((p) => p.id === id);
    if (index !== -1) {
        carts.value.splice(index, 1); // Use .splice() to remove the item at the given index
    }
}

function incrementQuantity(id) {
    const index = carts.value.findIndex((p) => p.id === id);
    if (index !== -1) {
        carts.value[index].quantity++; // Increase the quantity of the product in the cart
    }
}
function searchByName(event) {
    const query = event.target.value.trim().toLowerCase(); // Ensure query is trimmed and lowercased
    filteredProducts.value = products.value.filter((p) =>
        p.name.toLowerCase().includes(query)
    );
}

function decrementQuantity(id) {
    const index = carts.value.findIndex((p) => p.id === id);
    if (index !== -1) {
        if (carts.value[index].quantity > 1) {
            carts.value[index].quantity--; // Decrease the quantity if it's greater than 1
        } else {
            // Optionally remove the product if the quantity is 1 and the user tries to decrement
            carts.value.splice(index, 1);
        }
    }
}

onMounted(() => {
    store.dispatch("getProducts");
    store.dispatch("fetchClients");
})
</script>
