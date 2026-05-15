<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $newCustomers = \App\Models\User::where('created_at', '>=', now()->subDays(30))->count();

        // Line Chart: Orders over last 7 days
        $orderData = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $lineChartLabels = [];
        $lineChartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $lineChartLabels[] = now()->subDays($i)->format('M d');
            $lineChartData[] = $orderData->firstWhere('date', $date)->count ?? 0;
        }

        // Circle Chart: Products per Category
        $categories = Category::withCount('products')->get();
        $circleChartLabels = $categories->pluck('name');
        $circleChartData = $categories->pluck('products_count');

        return view('dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'newCustomers',
            'lineChartLabels',
            'lineChartData',
            'circleChartLabels',
            'circleChartData'
        ));
    }
}
