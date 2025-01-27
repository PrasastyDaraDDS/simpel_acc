<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderTransaction;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $orders = Order::paginate(10);
        $orders_buy = Order::where('order_type', 'buy')->with('order_transactions.product')->with('payments.payment_method')->paginate(10);
        $orders_sell = Order::where('order_type', 'sell')->with('order_transactions.product')->with('payments.payment_method')->paginate(10);
        $methods = PaymentMethod::all();
        return view('pages.orders.index', compact('orders', 'orders_buy', 'orders_sell', 'methods'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = ProductCategory::all();
        $products = Product::all();
        $customers = Participant::where('participant_role_id', 2)->get();
        $order_statuses = OrderStatus::all();
        $methods = PaymentMethod::all();

        return view('pages.orders.create', compact('methods', 'categories', 'products', 'customers', 'order_statuses'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // dd($request);
        $validator = Validator::make($request->all(), rules: [
            'participant_id' => 'required_if:order_type,sell|numeric',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'order_date' => 'required|date',
            'order_status_id' => 'required|numeric',
            'payment_date.*' => 'required|date', // Validate each payment date
            'amount.*' => 'required|numeric', // Validate each amount
            'payment_method_id.*' => 'required|numeric', // Validate each payment method
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $orderData = $request->only('participant_id', 'order_type', 'order_date', 'order_status_id', 'shipping_cost');

        // Set participant_id to null if order_type is 'buy'
        if ($request->input('order_type') === 'buy') {
            $orderData['participant_id'] = null; // Set participant_id to null
        } else {
            $orderData['participant_id'] = $request->input('participant_id'); // Keep the original participant_id
        }


        // Create the order
        $order = Order::create($orderData);

        // Loop through the products and save them
        for ($i = 0; $i < count($request->product_id); $i++) {
            // Find the product
            $product = Product::findOrFail($request->product_id[$i]);

            // Check if the quantity exceeds the available stock
            if ($order->order_type === 'sell') {
                $product->stock -= $request->quantity[$i]; // Subtract the ordered quantity from the stock
                if ($request->quantity[$i] > $product->stock) {
                    return redirect()->back()->withErrors(['quantity' => 'Quantity cannot exceed the available stock for product: ' . $product->name]);
                }
            } else {
                $product->stock += $request->quantity[$i];
            }
            $product->save(); // Save the updated product

            // Create a new order item or whatever your logic is
            OrderTransaction::create([
                'order_id' => $order->id, // Assuming you have an order ID to associate with
                'product_id' => $request->product_id[$i],
                'quantity' => $request->quantity[$i],
            ]);
        }

        // Create multiple payments and associate them with the order
        $paymentDates = $request->input('payment_date');
        $amounts = $request->input('amount');
        $paymentMethods = $request->input('payment_method_id');

        for ($i = 0; $i < count($paymentDates); $i++) {
            // Prepare payment data
            $paymentData = [
                'payment_date' => $paymentDates[$i],
                'amount' => $amounts[$i],
                'payment_method_id' => $paymentMethods[$i],
                'order_id' => $order->id, // Add the order_id to the payment
            ];
            // Create the payment
            Payment::create($paymentData);
        }


        return redirect()->route('orders.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = ProductCategory::all();
        $products = Product::all();
        $customers = Participant::where('participant_role_id', 2)->get();
        $order_statuses = OrderStatus::all();
        $methods = PaymentMethod::all();
        $order = Order::findOrFail($id);

        $order_visibility = "supplier";
        if ($order->order_type == 'sell') {
            $order_visibility = "customer";
        }
        $order_statuses = OrderStatus::where('order_visibility', 'all')->orWhere('order_visibility', $order_visibility)->get();
        return view('pages.orders.edit', compact('methods', 'categories', 'products', 'customers', 'order_statuses', 'order', 'order_statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        // Validate the incoming request data
        // $request->validate([
        //     'participant_id' => 'required_if:order_type,sell|numeric',
        //     'product_id' => 'required|numeric',
        //     'quantity' => 'required|numeric',
        //     'order_date' => 'required|date',
        //     'order_status_id' => 'required|numeric',
        //     'payment_date.*' => 'required|date', // Validate each payment date
        //     'amount.*' => 'required|numeric', // Validate each amount
        //     'amount_shipping.*' => 'nullable|numeric', // Allow shipping amount to be nullable
        //     'amount_overhead.*' => 'nullable|numeric', // Allow overhead amount to be nullable
        // ]);

        // Prepare data for the order
        $orderData = $request->only('product_id', 'quantity', 'order_type', 'order_date', 'order_status_id');

        // Set participant_id to null if order_type is 'buy'
        if ($request->input('order_type') === 'buy') {
            $orderData['participant_id'] = null; // Set participant_id to null
        } else {
            $orderData['participant_id'] = $request->input('participant_id'); // Keep the original participant_id
        }

        // Update the order
        $order->update($orderData);

        // Clear existing payments
        $order->payments()->delete(); // Remove existing payments

        // Create multiple payments and associate them with the order
        $paymentDates = $request->input('payment_date');
        $amounts = $request->input('amount');
        $paymentMethods = $request->input('payment_method_id');

        for ($i = 0; $i < count($paymentDates); $i++) {
            $payment = Payment::create([
                'payment_date' => $paymentDates[$i],
                'amount' => $amounts[$i],
                'payment_method_id' => $paymentMethods[$i],
                'order_id' => $order->id,
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        //
        $order = Order::findOrFail($id);

        // Delete the product
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
