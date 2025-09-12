<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Financial_year;

class ExpenseController extends Controller
{
    /**
     * Show the Expense Dashboard.
     */
   public function index(Request $request)
{
    $categories = Category::orderBy('id', 'desc')->get();

    // All financial years
    $financialYears = Financial_year::orderBy('start_date', 'desc')->get();

    // Default financial year
    $defaultFY = Financial_year::where('is_current', 1)->first();

    // Selected FY (from dropdown or default)
    $selectedFY = $request->get('financial_year_id', $defaultFY->id);

    $fy = Financial_year::find($selectedFY);

    // --- Expenses query for this FY ---
    $expensesQuery = Expense::whereBetween('date', [$fy->start_date, $fy->end_date]);

    // Apply month filter if present
    $selectedMonth = $request->get('month'); // e.g. "2025-09"
    if ($selectedMonth) {
        $expensesQuery->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$selectedMonth]);
    }

    $expenses = $expensesQuery->orderBy('id', 'desc')->get();

    // Distinct months for this FY (for pills UI)
    $months = Expense::selectRaw("DISTINCT DATE_FORMAT(date, '%Y-%m') as month_key, DATE_FORMAT(date, '%M') as month_label")
        ->whereBetween('date', [$fy->start_date, $fy->end_date])
        ->orderBy('month_key')
        ->get();

    // --- Real summary data ---
    $totalIncome   = $expenses->where('type', 'income')->sum('amount');
    $totalExpenses = $expenses->where('type', 'expense')->sum('amount');

    // --- Category-wise expense totals (for barSubcat) ---
    $categoryExpenses = $expenses->where('type', 'expense')
        ->groupBy('category_id')
        ->map(fn($row) => $row->sum('amount'));

    $expenseOnlyCategories = $categories->filter(fn($cat) => isset($categoryExpenses[$cat->id]));

    $categoryChartLabels = $expenseOnlyCategories->pluck('name')->toArray();
    $categoryChartData   = $expenseOnlyCategories->map(fn($cat) => $categoryExpenses[$cat->id])->toArray();

    // --- Monthly income & expense for whole year (chart data) ---
    $monthly = Expense::selectRaw("
            MONTH(date) as month_num,
            SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as income,
            SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as expense
        ")
        ->whereBetween('date', [$fy->start_date, $fy->end_date])
        ->groupBy('month_num')
        ->orderBy('month_num')
        ->get();

    $incomeData = [];
    $expenseData = [];
    for ($m = 1; $m <= 12; $m++) {
        $incomeData[]  = $monthly->firstWhere('month_num', $m)->income  ?? 0;
        $expenseData[] = $monthly->firstWhere('month_num', $m)->expense ?? 0;
    }

    // --- Category Income vs Expense totals (for barCategory) ---
    $categoryTotals = $expenses
        ->groupBy(['type','category_id'])
        ->map(fn($rowsByType) => $rowsByType->map(fn($rows) => $rows->sum('amount')));

    $categoryAllLabels   = $categories->pluck('name')->toArray(); // ✅ all categories
    $categoryIncomeData  = $categories->map(fn($cat) => $categoryTotals['income'][$cat->id]  ?? 0)->toArray();
    $categoryExpenseData = $categories->map(fn($cat) => $categoryTotals['expense'][$cat->id] ?? 0)->toArray();

    return view('admin.expenses.dashboard', compact(
        'categories',
        'expenses',
        'financialYears',
        'selectedFY',
        'months',
        'totalIncome',
        'totalExpenses',
        'categoryExpenses',
        'selectedMonth',
        'incomeData',
        'expenseData',

        // For barSubcat
        'categoryChartLabels',
        'categoryChartData',

        // For barCategory
        'categoryAllLabels',
        'categoryIncomeData',
        'categoryExpenseData',
    ));
}




}
