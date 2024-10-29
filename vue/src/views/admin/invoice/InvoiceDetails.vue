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
                    </svg>تنزيل الفاتورة
                </button>
            </div>

            <div class="flex-shrink max-w-full px-4 w-full mb-6" id="invoice" v-if="!loading">
                <div class="p-6 bg-white white:bg-gray-800 rounded-lg shadow-lg overflow-auto">
                    <div
                        class="flex flex-col md:flex-row mt-[3%] sm:flex-row justify-between items-center pb-4 border-b border-gray-200  mb-3">
                        <div class="flex flex-col items-star mb-3 md:mb-0">
                            <div class="flex items-center mb-3 md:mb-0">
                                <img class="w-24 h-24 md:w-[10rem] md:h-[8rem] mr-2 ml-2"
                                    src="@/assets/images/mahali.PNG" />
                                <span class="text-3xl font-bold mb-1">Al Achour</span>
                            </div>

                        </div>

                        <div class="text-4xl uppercase 2xl:-mt-4 md:-mt-4 sm:-mt-4 font-bold text-green-800"> الفاتورة
                        </div>

                    </div>
                    <div class="flex flex-row justify-between my-[10%] py-3">
                        <div class="flex-1">
                            <div v-if="Object.keys(invoice.client).length" class="flex justify-between w-1/2">

                                <div class="text-xl">
                                    {{ invoice.client.name }},<br />
                                    {{ invoice.client.cni }},<br />
                                    {{ invoice.client.phone }}<br />
                                </div>
                                <strong class="text-green-700 text-xl ">: فاتورة لـ</strong>

                            </div>


                        </div>
                        <div class="flex-1 text-right">
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 ltr:text-right text-xl rtl:text-left">
                                    FAC{{ invoice.id }}
                                </div>
                                <div class="flex-1 font-semibold rtl text-xl">: رقم الفاتورة</div>

                            </div>
                            <div class="flex justify-between mb-2 text-xl">
                                <div class="flex-1 ltr:text-right rtl:text-left">
                                    {{ new Date(invoice.created_at).toLocaleDateString() }}
                                </div>
                                <div class="flex-1 font-semibold rtl">: تاريخ الفاتورة</div>

                            </div>
                            <div class="flex justify-between mb-2 text-xl">
                                <div class="flex-1 ltr:text-right rtl:text-left">
                                    {{ new Date().toLocaleDateString() }}
                                </div>
                                <div class="flex-1 font-semibold rtl"> : تاريخ الاستحقاق</div>

                            </div>
                            <div class="flex justify-between mb-2 text-xl">
                                <div class="flex-1 ltr:text-right rtl:text-left">{{ invoice.status }}</div>
                                <div class="flex-1 font-semibold rtl">: الحالة</div>

                            </div>
                            <div class="flex justify-between mb-2 text-xl">
                                <div class="flex-1 ltr:text-right rtl:text-left">{{ invoice.payment_method }}</div>
                                <div class="flex-1 font-semibold rtl">: طريقة الدفع</div>

                            </div>
                        </div>
                    </div>
                    <div class="py-4">
                        <table
                            class="table-bordered w-full overflow-x-scroll ltr:text-left rtl:text-right text-gray-600">
                            <thead class="border-b white:border-gray-700">
                                <tr class="bg-green-800 h-9 text-white white:bg-gray-900 white:bg-opacity-20">
                                    <th class="text-center text-xl p-3">المنتجات</th>
                                    <th class="text-center text-xl p-3">الكمية</th>
                                    <th class="text-center text-xl p-3">سعر الوحدة</th>
                                    <th class="text-center text-xl p-3">المبلغ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in invoice.products" :key="item.id" class="border-b-2 border-green-500">
                                    <td>
                                        <div class="flex flex-wrap gap-8 m-2 flex-row items-center">
                                            <div class="self-center">
                                                <img class="h-16 w-16 rounded-md" :src="item.image" />
                                            </div>
                                            <div
                                                class="leading-5 text-xl white:text-gray-300 flex-1 ltr:ml-2 pl-4 rtl:mr-2 mb-1 text-center">
                                                {{ item.name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center text-xl ">{{ item.stock_quantity }} Pcs</td>
                                    <td class="text-center text-xl">{{ item.selling_price }} DH</td>
                                    <td class="text-center text-xl">
                                        {{ common.formatNumber(item.selling_price * item.stock_quantity) }}
                                    </td>
                                </tr>
                            </tbody>
                            <tr class="h-6"></tr>
                            <tfoot>
                                <tr class="mt-3">
                                    <td colspan="2"></td>
                                    <td class="text-center text-xl">
                                        {{ common.formatNumber(invoice.total_price) }} DH
                                    </td>
                                    <td class="text-center text-xl"><b> : الإجمالي</b></td>

                                </tr>

                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center text-xl">
                                        {{ common.formatNumber(invoice.paid_amount) }} DH
                                    </td>
                                    <td class="text-center text-xl"><b>: المبلغ المدفوع</b></td>

                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center text-xl font-bold">
                                        {{ common.formatNumber(invoice.remaining_amount) }} DH
                                    </td>
                                    <td class="text-center text-xl"><b>: المبلغ المتبقي</b></td>

                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <hr class="border-black border-2  mt-[55%]">
                    <div class="text-center font-bold relative bottom-0 mt-2 md:text-md  sm:text-sm">
                        {{ user.company.name }}, {{ user.company.address }}
                        <br />
                        Tel: {{ user.company.phone }},
                        RC: {{ user.company.RC ?? "_" }} -
                        ICE: {{ user.company.ICE ?? "_" }} -
                        IF: {{ user.company.IF ?? "_" }}


                    </div>
                </div>
            </div>
            <div class="flex-shrink max-w-full px-4 w-full mb-6" id="invoice" v-else>
                <div class="p-6 bg-white rounded-lg shadow-lg overflow-auto">
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

                        <div class="flex-1 text-right">
                            <div class="flex justify-between mb-2">
                                <Skeleton width="80%" height="1.2rem"></Skeleton>

                                <div class="flex-1 font-semibold rtl">: رقم الفاتورة</div>

                            </div>
                            <div class="flex justify-between mb-2">
                                <Skeleton width="80%" height="1.2rem"></Skeleton>

                                <div class="flex-1 font-semibold rtl">: تاريخ الفاتورة</div>

                            </div>
                            <div class="flex justify-between mb-2">
                                <Skeleton width="80%" height="1.2rem"></Skeleton>

                                <div class="flex-1 font-semibold rtl"> : تاريخ الاستحقاق</div>

                            </div>
                            <div class="flex justify-between mb-2">
                                <Skeleton width="80%" height="1.2rem"></Skeleton>
                                <div class="flex-1 font-semibold rtl">: الحالة</div>

                            </div>
                            <div class="flex justify-between mb-2">
                                <Skeleton width="80%" height="1.2rem"></Skeleton>
                                <div class="flex-1 font-semibold rtl">: طريقة الدفع</div>

                            </div>
                        </div>
                    </div>

                    <!-- Product list table -->
                    <div class="py-4 overflow-x-auto">
                        <table class="table-bordered w-full text-gray-600 dark:text-gray-400">
                            <thead class="border-b dark:border-gray-700">
                                <tr class="bg-green-800 h-9 text-center text-white dark:bg-gray-900 dark:bg-opacity-20">
                                    <th>المنتجات</th>
                                    <th class="text-center">الكمية</th>
                                    <th class="text-center">سعر الوحدة</th>
                                    <th class="text-center">المبلغ</th>
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
                                    <td class="text-center">
                                        <Skeleton width="100%" height="1.2rem"></Skeleton>
                                    </td>
                                    <td class="text-center"><b> : الإجمالي</b></td>

                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center">
                                        <Skeleton width="100%" height="1.2rem"></Skeleton>
                                    </td>
                                    <td class="text-center"><b>: المبلغ المدفوع</b></td>

                                </tr>

                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center font-bold">
                                        <Skeleton width="100%" height="1.2rem"></Skeleton>
                                    </td>
                                    <td class="text-center"><b>: المبلغ المتبقي</b></td>

                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <hr class="border-black border-2 mt-4 ">
                    <div class="text-center mt-2">
                        <Skeleton width="100%" height="1.2rem"></Skeleton>
                        <br />
                        <Skeleton width="100%" height="1.2rem"></Skeleton>



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
const user = computed(() => store.state.user.data);
onMounted(() => {
    getInvoiceById();
});
function getInvoiceById() {
    var id = route.params.id;

    store
        .dispatch("fetchInvoiceById", id)
        .then((res) => {
            if (res.status === 200 && res.data) {
                invoice.value = res.data;
            }

        })
        .catch((error) => console.error(error))
        .finally(() => {
            loading.value = false;
        });
}





async function exportToPDF() {
document.body.style.cursor = 'wait';
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
    document.body.style.cursor = 'default';
    common.showSwal({
        title: "Success!",
        text: "Votre facture a été enregistrée avec succès.",
        icon: "success",
        showConfirmButton: false,
    });
}


</script>
