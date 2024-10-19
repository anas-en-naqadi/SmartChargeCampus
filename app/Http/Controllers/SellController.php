<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SellRequest;
use App\Http\Resources\SellResource;
use App\Models\Client;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellProduct;
use App\Services\SellService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SellController extends Controller
{
    private $sellService;

    public function __construct(SellService $sellService) {
        $this->sellService = $sellService;
    }


    public function index()
    {
        $cacheKey = 'sells';
        $cacheData = getCachedData($cacheKey, function () {
            $sells = getSimpleUser()->sells()->latest()->get();
            return SellResource::collection($sells);
        });
        return response()->json($cacheData);
    }

    public function filterSellsByDates(Request $request)
    {
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
        return response()->json(SellResource::collection($sells), 200);
    }

    public function store(SellRequest $request)
    {
        $result = $this->sellService->storeSell($request);

        if($result)
        return response()->json(['message' => 'تمت عملية البيع بنجاح '], 201);
    else
     return response()->json(['message' => 'Failed to create sale.'], 500);

    }

}
