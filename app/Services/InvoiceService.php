<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Sell;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class InvoiceService
{
    public function __construct()
    {
    }

    public function storeInvoice(array $validatedData): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Create the invoice
            $invoice = new Invoice();
            $invoice->user_id = getSimpleUser()->id;
            $invoice->to_customer = $validatedData['user_id'];
            $invoice->total = 0;

            $invoice->status = $validatedData['status'];
            $invoice->due_date = $validatedData['due_date'];
            $invoice->save();

            // Loop through products and create associated sells
            $totalInvoiceAmount = 0;
            foreach ($validatedData['products'] as $product) {
                $sale = new Sell();
                $sale->user_id = $validatedData['user_id']; // Replace with actual user ID logic
                $sale->total_amount = floatval(str_replace(',', '', $product['amount']));
                $sale->product_id = $product['product_id'];
                $sale->quantity = $product['quantity'];
                $sale->invoice_id = $invoice->id; // Associate sell with the invoice
                $sale->save();

                $totalInvoiceAmount += $sale->total_amount;
            }

            // Update total amount for the invoice
            $invoice->total = $totalInvoiceAmount;
            $invoice->save();
            Redis::del('invoices');
            DB::commit();
            return response()->json(['id'=>$invoice->id,'message' => 'New invoice added successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateInvoice(array $validatedData, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::find($id);

            if (!$invoice) {
                throw new \Exception("Invoice not found");
            }

            // Delete existing sells associated with the invoice
            $invoice->sells()->delete();

             $invoice->user_id = getSimpleUser()->id;
             $invoice->to_customer = $validatedData['user_id'];
             $invoice->total = 0;
             $invoice->status = $validatedData['status'];
             $invoice->due_date = $validatedData['due_date'];
             $invoice->save();
            // Process updated products and create new sells
            $totalInvoiceAmount = 0;
            foreach ($validatedData['products'] as $product) {
                $sale = new Sell();
                $sale->user_id = $validatedData['user_id']; // Replace with actual user ID logic
                $sale->total_amount = floatval(str_replace(',', '', $product['amount']));
                $sale->product_id = $product['product_id'];
                $sale->quantity = $product['quantity'];
                $sale->invoice_id = $invoice->id;
                $sale->save();

                $totalInvoiceAmount += $sale->total_amount;
            }

            // Update total amount for the invoice
            $invoice->total = $totalInvoiceAmount;
            $invoice->save();
            Redis::del('invoices');

            DB::commit();
            return response()->json(['message' => 'Invoice updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
