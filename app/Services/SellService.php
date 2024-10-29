<?php

namespace App\Services;

use App\Http\Requests\SellRequest;
use App\Http\Resources\SellResource;
use App\Models\Client;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SellService
{
    public function __construct() {}

    public function storeSell(SellRequest $request)
    {

        try {
            DB::transaction(function () use ($request) {
                $validatedData = $request->validated();
                cleanInputs($validatedData);

                // Extract products from the request and unset from the validated data
                $products = $validatedData['products'];
                unset($validatedData['products']);

                // Initialize client and handle new or existing client logic
                $client = $this->handleClient($validatedData);

                // Attach user ID to the sell data
                $validatedData['user_id'] = getSimpleUser()?->id;
                $validatedData['client_id'] = $client->id ?? $validatedData['client_id'];
                $validatedData['status'] = $validatedData['remaining_amount'] > 0 ? 'متبقي' : 'مدفوع';
                unset($validatedData['client']);

                // Create the sell record
                $sell = Sell::create($validatedData);
                saveActivity($sell, " تمت إضافة فئة عملية بيع جديدة ", "إضافة");

                Redis::del('sells');
                // Process each product in the cart and update stock
                foreach ($products as $cartProduct) {
                    $this->processProductSale($sell, $cartProduct);
                }
            });

            return true;
        } catch (\Exception $e) {
            return false;
        }
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
        return SellResource::collection($sells);
    }



    private function processProductSale(Sell $sell, array $cartProduct): void
    {
        // Ensure the product exists and adjust stock quantity
        $product = Product::findOrFail($cartProduct['id']);
        $product->decrement('stock_quantity', $cartProduct['quantity']);
        Redis::del('products');
        // Create the record in the SellProduct pivot table
        SellProduct::create([
            'sell_id' => $sell->id,
            'product_id' => $product->id,
            'quantity' => $cartProduct['quantity']
        ]);
    }
    private function handleClient(array &$validatedData): ?Client
    {
        Redis::del('clients');

        if (!empty($validatedData['client_id'])) {
            // Update existing client credit
            $client = Client::findOrFail($validatedData['client_id']);
            $client->total_credit += $validatedData['remaining_amount'];
            $client->save();
            return $client;
        }

        if (!empty($validatedData['client'])) {
            // Create a new client
            $clientData = $validatedData['client'];
            $clientData['user_id'] = getSimpleUser()?->id;
            $clientData['role'] = 'client';
            $client = Client::create($clientData);
            $validatedData['client_id'] = $client->id;
            return $client;
        }


        return null;
    }
}
