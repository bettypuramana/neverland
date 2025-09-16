<?php

namespace App\Http\Controllers;
use App\Models\Expense;
use App\Models\Settlement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = Carbon::today();

        // ✅ Income & Expense (per day)
        $totalIncome = Expense::where('type', 'income')
            ->whereDate('date', $today)
            ->sum('amount');

        $totalExpense = Expense::where('type', 'expense')
            ->whereDate('date', $today)
            ->sum('amount');

        $totalSettlement = Settlement::whereDate('date', $today)
            ->where('settled', 1)
            ->sum('amount');

        // ✅ Today’s settled list (only settled)
         $todaySettled = Settlement::join('categories', 'settlements.category_id', '=', 'categories.id')
            ->whereDate('settlements.date', $today)
            ->select(
                'settlements.date',
                'settlements.amount',
                'settlements.settled',
                'categories.name as category_name'
            )
            ->get()
            ->map(function ($item) {
                // Add readable status
                $item->status_label = $item->settled == 1 ? 'Settled' : 'Not Settled';
                return $item;
            });

        // ✅ Total Item Purchase Today (from settlement table)
        $totalItemPurchase = Settlement::whereDate('date', $today)
            ->where('category_id', 10)
            ->sum('amount');


        return view('admin.home', compact(
            'totalIncome',
            'totalExpense',
            'totalSettlement',
            'totalItemPurchase',
            'todaySettled'
        ));
    }
}
