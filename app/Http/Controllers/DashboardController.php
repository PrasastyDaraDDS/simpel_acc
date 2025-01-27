<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve query parameters
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        // Call the private method to get the payment sum
        $totalPaymentSum = $this->getPaymentSum($startDate, $endDate);
        $totalOrder = $this->getTotalOrder($startDate, $endDate);
        $totalCustomer = $this->countParticipantsByRole("customer");

        $newestProducts = $this->getNewestProducts();
        $bestSellingProducts = $this->getBestSellingProducts();

        // dd($newestProducts);

        return view('pages.dashboard.index', compact(
            'totalPaymentSum',
            'totalOrder',
            'totalCustomer',
            'newestProducts',
            'bestSellingProducts'
        ));
    }

    public function getPaymentSum($startDate = null, $endDate = null)
    {
        // Set default date range to one year from the current date if not provided
        if (!$startDate) {
            $startDate = Carbon::now()->startOfYear(); // Start of the current year
        } else {
            $startDate = Carbon::parse($startDate); // Parse the provided start date
        }

        if (!$endDate) {
            $endDate = Carbon::now()->endOfYear(); // End of the current year
        } else {
            $endDate = Carbon::parse($endDate); // Parse the provided end date
        }

        $results = Order::selectRaw('MIN(orders.id) AS oid, orders.order_type, SUM(payments.amount) AS total_amount')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('orders.order_type')
            ->get();
        // Calculate the net amount (sell - buy)
        $totalBuy = $results->where('order_type', 'buy')->sum('total_amount');
        $totalSell = $results->where('order_type', 'sell')->sum('total_amount');
        $netAmount = $totalSell - $totalBuy;

        return $netAmount;
    }

    private function getTotalOrder()
    {
        $totalOrder = Order::where('order_type', 'sell')->count();

        return $totalOrder;
    }
    private function countParticipantsByRole($roleName)
    {
        return Participant::whereHas('role', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->count();
    }

    private function getNewestProducts()
    {
        $newestProducts = Product::orderBy('created_at', 'desc')->take(5)->get();
        return $newestProducts;
    }

    private function getBestSellingProducts()
    {
        $bestSellingProducts = OrderTransaction::select('products.*', 'order_transactions.*')
            ->join('products', 'products.id', '=', 'order_transactions.product_id')
            ->join('order_transactions', 'order_transactions.id', '=', 'order_transactions.order_transaction_id')
            ->where('order_transactions.order_id', '=', 'orders.id')
            ->where('orders.order_type', '=', 'sell')
            ->groupBy('products.id')
            ->orderBy('order_transactions.quantity', 'desc')
            ->take(5);
        return $bestSellingProducts;
    }

    // private function bestSellingProduct()
    // {
    //     $soldProduct = Order
    // }
}
