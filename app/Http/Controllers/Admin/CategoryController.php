<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Expense;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.expenses.categories', compact('categories'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        $category = Category::create($request->only('name'));

        return response()->json([
            'success' => 'Category added successfully!',
            'category' => $category
        ]);
    }


  public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Check if this category is used in expenses
        $usedInExpenses = \App\Models\Expense::where('category_id', $id)->exists();

        if ($usedInExpenses) {
            return response()->json([
                'error' => 'Cannot delete: This category is already used in expenses.'
            ], 400); // 400 Bad Request
        }

        $category->delete();

        return response()->json([
            'success' => 'Category deleted successfully!',
            'id' => $id
        ]);
    }


    // Store new expense
public function storeexp(Request $request)
{
    // ✅ Validate request
    $validated = $request->validate([
        'date' => 'required|date',
        'amount' => 'required|numeric',
        'type' => 'required|in:income,expense',
        'category_id' => 'required|exists:categories,id',
        'remarks' => 'nullable|string',
    ]);

    // ✅ Create Expense
    $expense = Expense::create($validated);

    // ✅ Return JSON response with category relationship
    return response()->json([
        'success' => 'Expense saved successfully!',
        'expense' => [
            'id'       => $expense->id,
            'date'     => $expense->date,
            'amount'   => $expense->amount,
            'type'     => $expense->type,
            'category' => $expense->category ? $expense->category->name : null,
            'remarks'  => $expense->remarks ?? '',
        ]
    ]);
}


// Delete expense
public function destroyexp($id)
{
    $exp = Expense::findOrFail($id);
    $exp->delete();
    return response()->json([
        'success' => 'Expense deleted successfully!',
        'id' => $id
    ]);
}



}
