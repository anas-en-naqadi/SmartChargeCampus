<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SellRequest;
use App\Http\Resources\SellResource;
use App\Models\Sell;
use App\Services\SellService;
use Illuminate\Http\Request;

class SellController extends Controller
{
    private $sellService;

    public function __construct(SellService $sellService)
    {
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

    public function show(Sell $sell){
        return response()->json($sell->load('client','products'));
    }

    public function filterSellsByDates(Request $request)
    {
        $sells = $this->sellService->filterSellsByDates($request);
        return response()->json($sells, 200);
    }

    public function store(SellRequest $request)
    {
        $result = $this->sellService->storeSell($request);

        return response()->json(['message' => 'تمت عملية البيع بنجاح '], 201);
    }
}
