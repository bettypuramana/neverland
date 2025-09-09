<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Expense;


class ExpenseController extends Controller
{
    /**
     * Show the Expense Dashboard.
     */
    public function index()
    {
        // for now using dummy data
        $summary = [
            'living'        => 19442,
            'discretionary' => 7917,
            'transport'     => 4245,
            'dining'        => 2843,
            'charity'       => 1729,
            'medical'       => 620,
        ];
        $categories = Category::all();
        $expenses = Expense::latest()->get();
        return view('admin.expenses.dashboard', compact('summary','categories','expenses'));
    }
}
