<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settlement;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class SettlementController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        // Only fetch unsettled records
        $settlements = Settlement::whereDate('date', $date)
                                ->where('settled', 0)
                                ->get();

        $total = $settlements->sum('amount');

        return view('admin.settlements', compact('settlements', 'date', 'total'));
    }

    public function settleDay(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));

        // Mark all settlements of that day as settled
        Settlement::whereDate('date', $date)->update(['settled' => 1]);

        return redirect()->route('settlements.index', ['date' => $date])
                        ->with('success', "Settlements for {$date} have been finalized!");
    }
    public function destroy($id)
    {
        $settlement = Settlement::findOrFail($id);

        // Only allow deleting if not settled
        if ($settlement->settled == 1) {
            return redirect()->back()->with('error', 'Cannot delete a settled record!');
        }

        $settlement->delete();

        return redirect()->back()->with('success', 'Settlement deleted successfully!');
    }

    public function settledDay(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));

        // Get unsettled settlements for the date
        $settlements = Settlement::with('category')
                                ->whereDate('date', $date)
                                ->where('settled', 0)
                                ->get();

        if ($settlements->isEmpty()) {
            return redirect()->route('settlements.index', ['date' => $date])
                            ->with('error', "No unsettled settlements found for {$date}");
        }

        // Group by category_id
        $grouped = $settlements->groupBy('category_id');

        DB::transaction(function () use ($grouped, $date) {
            foreach ($grouped as $categoryId => $items) {
                $total = $items->sum('amount');
                $category = $items->first()->category;

                // Decide type based on category id
                if ($categoryId == 9) {
                    $type = 'income';
                    $remarks = 'Settlement - ' . $category->name;
                } elseif ($categoryId == 10) {
                    $type = 'expense';
                    $remarks = 'Settlement - ' . $category->name;
                } else {
                    continue;
                }

                // Check if expense already exists for this date + category
                $expense = Expense::whereDate('date', $date)
                                ->where('category_id', $categoryId)
                                ->first();

                if ($expense) {
                    $expense->amount += $total;
                    $expense->save();
                } else {
                    Expense::create([
                        'date' => $date,
                        'category_id' => $categoryId,
                        'type' => $type,
                        'remarks' => $remarks,
                        'amount' => $total,
                    ]);
                }
            }

            // Mark all as settled
            Settlement::whereDate('date', $date)->update(['settled' => 1]);
        });

        return redirect()->route('settlements.index', ['date' => $date])
                        ->with('success', "Settlements for {$date} have been settled and moved to expenses!");
    }


}
