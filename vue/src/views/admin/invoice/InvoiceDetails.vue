<template>
    <div class="card mt-12 m-6 bg-gray-50rounded-xl">
        <!-- row -->
        <div class="flex flex-wrap flex-row">
            <div id="title-invoice" class="flex justify-between max-w-full px-4 py-4 w-full">
                <p class="text-xl font-bold mt-3 mb-5">Invoice #1089</p>
                <button type="button" id="btn-invoice" @click="exportToPDF()"
                    class="py-2 px-4 inline-block text-center mb-3 rounded leading-5 text-red-800 bg-white border border-red-700 hover:text-white hover:bg-red-600 hover:ring-0 hover:border-white  focus:outline-none focus:ring-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="ltr:mr-2 rtl:ml-2 inline-block bi bi-printer mr-2" viewBox="0 0 16 16">
                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"></path>
                        <path
                            d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z">
                        </path>
                    </svg>Export PDF
                </button>
            </div>

            <div class="flex-shrink max-w-full px-4 w-full mb-6" id="invoice" v-if="!loading">
                <div class="p-6 bg-white white:bg-gray-800 rounded-lg shadow-lg">
                    <div
                        class="flex flex-col md:flex-row sm:flex-row justify-between items-center pb-4 border-b border-gray-200  mb-3">
                        <div class="flex flex-col items-star mb-3 md:mb-0">
                            <div class="flex items-center mb-3 md:mb-0">
                                <img class="w-24 h-24 md:w-[10rem] md:h-[8rem] mr-2 ml-2"
                                    src="@/assets/images/mahali.PNG" />
                                <span class="text-3xl font-bold mb-1">Al Mobarka</span>
                            </div>
                            <p v-if="user.default_shipping_address" class="text-sm -mt-4 ml-10">
                                {{ user.default_shipping_address.address }}<br />{{ user.default_shipping_address.city
                                }}, {{ user.default_shipping_address.postal_code }}, {{
                                    user.default_shipping_address.country }}
                            </p>
                        </div>

                        <div class="text-4xl uppercase 2xl:-mt-4 md:-mt-4 sm:-mt-4 font-bold text-green-800">Facture
                        </div>

                    </div>
                    <div class="flex flex-row justify-between py-3">
                        <div class="flex-1">
                            <p v-if="invoice.customer.default_shipping_address">
                                <strong class="text-green-700 text-lg">Bill to:</strong><br />
                                {{ invoice.customer.name }}<br />
                                {{ invoice.customer.default_shipping_address.address }}<br />
                                {{ invoice.customer.default_shipping_address.city }},
                                {{ invoice.customer.default_shipping_address.postal_code }},
                                {{ invoice.customer.default_shipping_address.country }}<br />

                            </p>
                            <span class="text-red-600 text-sm" v-else>No addrress included !!</span>
                            <div v-if="invoice.customer">
                                {{ invoice.customer.email }}<br />
                                {{ invoice.customer.phone }}
                            </div>

                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 font-semibold">Facture ID#:</div>
                                <div class="flex-1 ltr:text-right rtl:text-left">
                                    FAC{{ invoice.id }}
                                </div>
                            </div>
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 font-semibold">Date Facture :</div>
                                <div class="flex-1 ltr:text-right rtl:text-left">
                                    {{ common.formatDate(invoice.created_at) }}
                                </div>
                            </div>
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 font-semibold">Due date:</div>
                                <div class="flex-1 ltr:text-right rtl:text-left">
                                    12/08/2022
                                </div>
                            </div>
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 font-semibold">Status #:</div>
                                <div class="flex-1 ltr:text-right rtl:text-left">Paid</div>
                            </div>
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 font-semibold">Payment #:</div>
                                <div class="flex-1 ltr:text-right rtl:text-left">COD</div>
                            </div>
                        </div>
                    </div>
                    <div class="py-4">
                        <table
                            class="table-bordered w-full overflow-x-scroll ltr:text-left rtl:text-right text-gray-600">
                            <thead class="border-b white:border-gray-700">
                                <tr class="bg-green-800 h-9 text-white white:bg-gray-900 white:bg-opacity-20">
                                    <th>Products</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Unit price</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in invoice.sells" :key="item.id" class="border-b-2 border-green-500">
                                    <td>
                                        <div class="flex flex-wrap gap-8 m-2 flex-row items-center">
                                            <div class="self-center">
                                                <img class="h-12 w-12 rounded-md" :src="item.product?.image" />
                                            </div>
                                            <div
                                                class="leading-5 white:text-gray-300 flex-1 ltr:ml-2 pl-4 rtl:mr-2 mb-1">
                                                {{ item.product?.product_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ item.quantity }}</td>
                                    <td class="text-center">{{ item.product?.price }} DH</td>
                                    <td class="text-center">
                                        {{ common.formatNumber(item?.total_amount) }}
                                    </td>
                                </tr>
                            </tbody>
                            <tr class="h-6"></tr>
                            <tfoot>
                                <tr class="mt-3">
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>Sub-Total</b></td>
                                    <td class="text-center">
                                        {{ common.formatNumber(invoice.total) }} DH
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>Discount</b></td>
                                    <td class="text-center">
                                        {{ common.formatNumber(totalDiscount) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>Tax</b></td>
                                    <td class="text-center">
                                        {{ common.formatNumber(taxAmount) }} DH
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>Total</b></td>
                                    <td class="text-center font-bold">
                                        {{ common.formatNumber(total) }} DH
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flex-shrink max-w-full px-4 w-full mb-6" id="invoice" v-else>
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                    <!-- Company info and title section -->
                    <div
                        class="flex flex-col md:flex-row justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700 mb-3">
                        <div class="flex items-center mb-3 md:mb-0">
                            <img class="w-24 h-24 md:w-[10rem] md:h-[8rem] mr-2 ml-2" src="@/assets/images/mahali.PNG"
                                alt="Company Logo" />
                            <span class="text-3xl font-bold mb-1">Al Mobarka</span>
                        </div>
                        <div class="text-4xl uppercase font-bold text-green-800">Facture</div>
                    </div>

                    <!-- Bill to and invoice details -->
                    <div class="flex flex-col md:flex-row justify-between py-3">
                        <div class="flex-1 mb-4 md:mb-0">
                            <p>
                                <strong class="text-green-700 text-lg">Bill to:</strong><br />
                                <Skeleton class="mb-1 md:mb-2" width="100%" height="1.2rem"></Skeleton>
                            </p>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row justify-between mb-2">
                                <div class="font-semibold">Facture ID#:</div>
                                <div class="mb-1">
                                    FAC<Skeleton width="100%" height="1.2rem"></Skeleton>
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row justify-between mb-2">
                                <div class="font-semibold">Date Facture :</div>
                                <Skeleton width="80%" height="1.2rem"></Skeleton>
                            </div>
                            <div class="flex flex-col md:flex-row justify-between mb-2">
                                <div class="font-semibold">Due date:</div>
                                <Skeleton width="80%" height="1.2rem"></Skeleton>
                            </div>
                            <div class="flex flex-col md:flex-row justify-between mb-2">
                                <div class="font-semibold">Status #:</div>
                                <div class="md:text-right">Paid</div>
                            </div>
                            <div class="flex flex-col md:flex-row justify-between mb-2">
                                <div class="font-semibold">Payment #:</div>
                                <div class="md:text-right">COD</div>
                            </div>
                        </div>
                    </div>

                    <!-- Product list table -->
                    <div class="py-4 overflow-x-auto">
                        <table class="table-bordered w-full text-gray-600 dark:text-gray-400">
                            <thead class="border-b dark:border-gray-700">
                                <tr class="bg-green-800 h-9 text-center text-white dark:bg-gray-900 dark:bg-opacity-20">
                                    <th>Products</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Unit price</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in new Array(2)" :key="index"
                                    class="border-b-2 border-green-500 text-center">
                                    <td>
                                        <div class="flex flex-wrap gap-4 items-center">
                                            <div class="self-center">
                                                <Skeleton class="w-24 md:w-40 h-24 md:h-40 m-2"></Skeleton>
                                            </div>
                                            <div class="flex-1 mt-2 md:mt-0 m-2">
                                                <Skeleton width="100%" height="1.2rem"></Skeleton>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <Skeleton width="6rem" height="1.2rem"></Skeleton>
                                    </td>
                                    <td class="text-center">
                                        <Skeleton width="10rem" height="1.2rem"></Skeleton>
                                    </td>
                                    <td class="text-center">
                                        <Skeleton width="12rem" height="1.2rem"></Skeleton>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="mt-3">
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>Sub-Total</b></td>
                                    <td class="text-center">
                                        <Skeleton width="100%" height="1.2rem"></Skeleton>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>Discount</b></td>
                                    <td class="text-center">
                                        <Skeleton width="100%" height="1.2rem"></Skeleton>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>Tax</b></td>
                                    <td class="text-center">
                                        <Skeleton width="100%" height="1.2rem"></Skeleton>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>Total</b></td>
                                    <td class="text-center font-bold">
                                        <Skeleton width="100%" height="1.2rem"></Skeleton>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>


<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import store from "../../../store";
import common from "../../../utils/common";
import html2canvas from "html2canvas";
import jsPDF from "jspdf";
const invoice = ref({});
const route = useRoute();
const loading = ref(true);
const router = useRouter();
const user = computed(() => store.state.user.data);
const pdf = ref(null);
onMounted(() => {
    fetchInvoiceById();
});
function fetchInvoiceById() {
    var id = route.params.id;

    store
        .dispatch("getInvoiceById", id)
        .then((res) => {
            if (res.status === 200 && res.data) {
                invoice.value = res.data;
                console.dir(invoice.value);
            }

        })
        .catch((error) => console.error(error))
        .finally(() => {
            loading.value = false;
        });
}
const totalDiscount = computed(() => {
    return invoice.value.sells.reduce((sum, item) => {
        const discount = item.product
            ? (item.product.discount / 100) * parseFloat(item.total_amount)
            : 0;
        return sum + discount;
    }, 0);
});

const taxAmount = computed(() => {
    const taxRate = 0.05; // 5% tax rate
    const taxableAmount = invoice.value.total - totalDiscount.value;
    return taxableAmount * taxRate;
});

const total = computed(() => {
    return invoice.value.total - totalDiscount.value + taxAmount.value;
});

// const exportToPDF = async () => {
//   const element = document.getElementById("invoice");
//   const canvas = await html2canvas(element, {
//     scale: 2,
//     useCORS: false,
//     logging: true,
//   });
//   const imgData = canvas.toDataURL("image/png");
//   const pdf = new jsPDF("p", "pt", "a4");
//   const imgProps = pdf.getImageProperties(imgData);
//   const pdfWidth = pdf.internal.pageSize.getWidth();
//   const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

//   pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
//   pdf.save(`invoice#${invoice.value.id}.pdf`);
// };
async function exportToPDF() {
    const element = document.getElementById("invoice");
    const canvas = await html2canvas(element, {
        scale: 2,
        useCORS: false,
        logging: true,
    });
    const imgData = canvas.toDataURL("image/png");
    const pdf = new jsPDF("p", "pt", "a4");
    const imgProps = pdf.getImageProperties(imgData);
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

    pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
    pdf.save(`invoice#${invoice.value.id}.pdf`);
    common.showSwal({
        title: "Success!",
        text: "Votre facture a été enregistrée avec succès.",
        icon: "success",
        showConfirmButton: false,
    });
}

</script>
