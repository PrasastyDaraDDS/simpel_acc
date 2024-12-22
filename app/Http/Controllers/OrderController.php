<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $orders = Order::paginate(10);
        $orders_buy = Order::where('order_type', 'buy')->paginate(10);
        $orders_sell = Order::where('order_type', 'sell')->paginate(10);
        return view('pages.orders.index', compact('orders', 'orders_buy', 'orders_sell'));
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

        return view('pages.orders.create', compact('categories','products', 'customers', 'order_statuses'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        // $request->validate([
        //     'participant_id' => 'required|numeric',
        //     'product_id' => 'required|numeric',
        //     'quantity' => 'required|numeric',
        //     // 'order_date' => 'required|date',
        //     'order_status_id' => 'required|numeric',
        // ]);

        // Prepare data for the order
        $orderData = $request->only('product_id', 'quantity', 'order_type', 'order_date', 'order_status_id');

        // Set participant_id to null if order_type is 'buy'
        if ($request->input('order_type') === 'buy') {
            $orderData['participant_id'] = null; // Set participant_id to null
        } else {
            $orderData['participant_id'] = $request->input('participant_id'); // Keep the original participant_id
        }


        $product = Product::findOrFail($request->input('product_id'));

        // Check if the quantity exceeds the available stock
        if ($request->input('quantity') > $product->stock) {
            return redirect()->back()->withErrors(['quantity' => 'Quantity cannot exceed the available stock.']);
        }

        // Create the order
        $order = Order::create($orderData);


        // Create multiple payments and associate them with the order
        $paymentDates = $request->input('payment_date');
        $amounts = $request->input('amount');
        $amountShippings = $request->input('amount_shipping');
        $amountOverheads = $request->input('amount_overhead');

        for ($i = 0; $i < count($paymentDates); $i++) {
            // Prepare payment data
            $paymentData = [
                'payment_date' => $paymentDates[$i],
                'amount' => $amounts[$i],
                'order_id' => $order->id, // Add the order_id to the payment
            ];

            // Set shipping and overhead amounts to null if not provided
            $paymentData['amount_shipping'] = !empty($amountShippings[$i]) ? $amountShippings[$i] : null;
            $paymentData['amount_overhead'] = !empty($amountOverheads[$i]) ? $amountOverheads[$i] : null;

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
        $order = Order::find($id);
        $products = Product::all();
        $customers = Participant::where('participant_role_id', 2)->get();
        $order_statuses = OrderStatus::all();
        return view('pages.orders.edit', compact('order', 'products', 'customers', 'order_statuses'));
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
        $amountShippings = $request->input('amount_shipping');
        $amountOverheads = $request->input('amount_overhead');

        for ($i = 0; $i < count($paymentDates); $i++) {
            // Prepare payment data
            $paymentData = [
                'payment_date' => $paymentDates[$i],
                'amount' => $amounts[$i],
                'order_id' => $order->id, // Add the order_id to the payment
            ];

            // Set shipping and overhead amounts to null if not provided
            $paymentData['amount_shipping'] = !empty($amountShippings[$i]) ? $amountShippings[$i] : null;
            $paymentData['amount_overhead'] = !empty($amountOverheads[$i]) ? $amountOverheads[$i] : null;

            // Create the payment
            Payment::create($paymentData);
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
