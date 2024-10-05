<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sell;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SellController extends Controller
{

    public function sells(){
        $cacheKey = 'sells';
        $cacheData = getCachedData($cacheKey, function () {
        $sells = Sell::latest()->with("client")->get();
        return $sells;
    });
    return response()->json($cacheData);
    }

    public function filterSellsByDates(Request $request){
        $data = $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.required' => 'تاريخ البدء مطلوب.',
            'start_date.date' => 'تاريخ البدء يجب أن يكون تاريخًا صالحًا.',
            'start_date.before_or_equal' => 'يجب أن يكون تاريخ البدء قبل أو يساوي تاريخ الانتهاء.',
            'end_date.required' => 'تاريخ الانتهاء مطلوب.',
            'end_date.date' => 'تاريخ الانتهاء يجب أن يكون تاريخًا صالحًا.',
            'end_date.after_or_equal' => 'يجب أن يكون تاريخ الانتهاء بعد أو يساوي تاريخ البدء.',
        ]);
        $sells = Sell::whereBetween('created_at', [$data['start_date'], $data['end_date']])->get();
          return response()->json($sells,200);
       }

       public function downloadInvoiceAsPdf(){
        // Pass the data to the invoice.blade.php view
        $invoice = Sell::with('products', 'client')->first();
        $pdf = Pdf::loadView('invoice',['invoice'=>$invoice]);

        // Return the generated PDF to download
        return $pdf->download('invoice_' . 1 . '.pdf');
       }
}
