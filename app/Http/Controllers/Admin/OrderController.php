<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Notifications\NewOrderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    public function index()
    {
        $cacheKey = 'orders';
        $cacheData = getCachedData($cacheKey, function () {
        $orders = Order::with(['payment', 'customer'])->get();
        return $orders;
        });
        return response()->json($cacheData);

    }

    public function show($id)
    {
        $order = Order::with(['payment', 'items', 'customer.defaultShippingAddress'])->findOrFail($id);


        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }


        $order->customer->totalOrders = $order->customer->orders->count();
        unset($order->customer->orders);
        return response()->json($order);
    }



    public function storeItem(OrderRequest $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validated();
            CleanInputs($validatedData);
            $user = getSimpleUser();

            // Find the admin user with the role
            $admin = User::where('role', true)->firstOrFail();

            // Create the order
            $order = $user->orders->create([
                'shipping_id' => $validatedData['shipping_id'],

            ]);
            unset($validatedData['shipping_id']);
            // Associate order ID with validated data
            $validatedData['order_id'] = $order->id;

            // Create payment record
            $payment = Payment::create($validatedData);

            // Get cart items with product prices
            $cartItems = Cart::with('product:id,price')->get(['product_id', 'quantity']);

            $totalAmount = 0;

            // Create order items and calculate total amount
            foreach ($cartItems as $cart) {
                $orderItemTotalPrice = $cart->quantity * $cart->product->price;
                $totalAmount += $orderItemTotalPrice;

                $order->items()->create([
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'unit_price' => $cart->product->price,
                    'total_price' => $orderItemTotalPrice,
                ]);
            }

            // Set the total amount for the order
            $order->amount = $totalAmount;
            $order->save();

            // Clear the cart
            Cart::truncate();

            // Notify the admin about the new order
            $admin->notify(new NewOrderNotification($order, $user));

            DB::commit();

            return response()->json(['message' => 'Order created successfully']);
        } catch (\Exception $e) {
            DB::rollBack();

            // Handle the error, log it, or return an error response
            return response()->json(['message' => 'Failed to create order'], 500);
        }
    }


    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'status' => 'required|string',
        ]);

        $order->status = $validatedData['status'];
        $order->save();

        return response()->json(['message' => 'Commande a été modifié avec succès']);
    }
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(['message' => 'la commande a ete supprime avec success']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'la commande n\'est pas supprime avec success']);
        }
    }
    public function getLastOrders()
    {
        $orders = Order::latest()->limit(7)->get();
        return response()->json($orders);
    }

    public function getOrderStatus()
    {
        $status = Order::select('status')->get();
    }


    public function changeOrderStatus(Request $request)
    {

        $validatedData = $request->validate(['status'=>'required|string']);


        $order = Order::findOrFail($request->id);
        Redis::del('orders');
        if ($order) {
            $order->status = $validatedData['status'];
            $order->save();
            return response()->json(['message' => 'Status a ete change avec success'], 200);
        } else {
            return response()->json(['message' => 'Erreur !!'], 422);
        }
    }

   public function filterOrdersByDates(Request $request){
   $orders = filterByDates($request,"order");
   return response()->json($orders,200);
   }
}
