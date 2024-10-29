<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body dir="rtl" class="bg-white">

    <div class="card mt-12 m-6 bg-gray-50 rounded-xl">
        <!-- row -->
        <div class="flex flex-wrap flex-row">


            <div class="flex-shrink max-w-full px-4 w-full mb-6" id="invoice">
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <div class="flex flex-col md:flex-row sm:flex-row justify-between items-center pb-4 border-b border-gray-200 mb-3">
                        <div class="flex flex-col items-start mb-3 md:mb-0">
                            <div class="flex items-center mb-3 md:mb-0">
                                <img class="w-24 h-24 md:w-[10rem] md:h-[8rem] mr-2 ml-2" src="../../vue/src/assets/images/mahali.jpg" />
                                <span class="text-3xl font-bold mb-1">Al Mobarka</span>
                            </div>
                            <p class="text-sm -mt-4 ml-10">
                                Anas<br />
                                El jadida,
                                24 000,
                                hay el matar
                            </p>
                        </div>

                        <div class="text-4xl uppercase font-bold text-green-800">فاتورة</div>
                    </div>

                    <div class="flex flex-row justify-between py-3">
                        <div class="flex-1">
                            <p>
                                <strong class="text-green-700 text-lg">فاتورة لـ:</strong><br />
                                {{ $invoice->client->name ?? 'اسم العميل غير متوفر' }}<br />

                            </p>
                            <div>
                                {{ $invoice->client->email ?? 'لا يوجد بريد إلكتروني' }}<br />
                                {{ $invoice->client->phone ?? 'لا يوجد رقم هاتف' }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 font-semibold">رقم الفاتورة:</div>
                                <div class="flex-1 ltr:text-right rtl:text-left">FAC{{ $invoice->id }}</div>
                            </div>
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 font-semibold">تاريخ الفاتورة:</div>
                                <div class="flex-1 ltr:text-right rtl:text-left">{{ optional($invoice->created_at)->format('Y-m-d') }}</div>
                            </div>
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 font-semibold">تاريخ الاستحقاق:</div>
                                <div class="flex-1 ltr:text-right rtl:text-left"></div>
                            </div>
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 font-semibold">الحالة:</div>
                                <div class="flex-1 ltr:text-right rtl:text-left">{{ $invoice->status }}</div>
                            </div>
                            <div class="flex justify-between mb-2">
                                <div class="flex-1 font-semibold">طريقة الدفع:</div>
                                <div class="flex-1 ltr:text-right rtl:text-left">{{ $invoice->check && 'check'  }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="py-4">
                        <table class="table-auto w-full text-gray-600">
                            <thead class="border-b">
                                <tr class="bg-green-800 h-9 text-white">
                                    <th>المنتجات</th>
                                    <th class="text-center">الكمية</th>
                                    <th class="text-center">سعر الوحدة</th>
                                    <th class="text-center">المبلغ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->products as $item)
                                <tr class="border-b-2 border-green-500">
                                    <td>
                                        <div class="flex flex-wrap gap-8 m-2 flex-row items-center">
                                            <div class="self-center">
                                                <img class="h-12 w-12 rounded-md" src="{{ $item->image }}" />
                                            </div>
                                            <div class="leading-5 flex-1 ltr:ml-2 pl-4 rtl:mr-2 mb-1">
                                                {{ $item->name ?? 'اسم المنتج غير متوفر' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->stock_quantity }}</td>
                                    <td class="text-center">{{ $item->selling_price }} DH</td>
                                    <td class="text-center">{{ $item->selling_price *$item->stock_quantity  }} DH</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="flex mt-6 flex-col justify-right gap-2 mr-3">
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>الإجمالي :</b></td>
                                    <td class="text-center">{{ number_format($invoice->total_price, 2) }} DH</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>المبلغ المدفوع :</b></td>
                                    <td class="text-center">{{ number_format($invoice->paid_amount, 2) }} DH</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-center"><b>المبلغ المتبقي :</b></td>
                                    <td class="text-center">{{ number_format($invoice->remaining_amount, 2) }} DH</td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
