<?php

namespace App\Services;

use App\Http\Requests\SellRequest;
use App\Models\Client;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellProduct;
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
