<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use App\Models\Sell;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;

class InvoiceController extends Controller
{

    private $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }
    public function index()
    {
        $cacheKey = 'invoices';
        $cacheData = getCachedData($cacheKey, function () {
        // Retrieve invoices with sells and customer relationship
        $invoices = Invoice::get()
            ->map(function ($invoice) {
                // Add additional attributes to the invoice object
                $invoice->sell_count = $invoice->sells->count();
                $invoice->customer_name = $invoice->customer->name;
                return $invoice;
            });

        return $invoices;
        });
        return response()->json($cacheData);

    }



    public function store(InvoiceRequest $request)
    {
        $validatedData = $request->validated();

      $response =  $this->invoiceService->storeInvoice($validatedData);
      Redis::del('categories');
        return $response;

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $invoice = Invoice::with(['sells.product', 'customer.defaultShippingAddress'])
            ->findOrFail($id);
            $invoice->sells->each(function ($sell) {
                $sell->product->image = URL::to($sell->product->image);
            });


        return $invoice ?   response()->json($invoice) : response()->json(['message', 'something went wrong !!'], 500);
    }


    public function update(InvoiceRequest $request)
    {

        $validatedData = $request->validated();

    $response =  $this->invoiceService->updateInvoice($validatedData,$request->id);
    Redis::del('categories');

    return $response;
    }


    public function customers()
    {
        $customers = User::with('shippings')->latest()->get();
        return response()->json($customers);
    }
    public function destroy($id)
    {
        Sell::where('invoice_id', $id)->delete();
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return redirect()->back();
    }

    public function filterInvoicesByDates(Request $request){
        $invoice = filterByDates($request,"invoice");
   return response()->json($invoice,200);
       }
}
