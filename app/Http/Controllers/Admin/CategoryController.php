<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.expenses.categories', compact('categories'));
    }

   public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = Category::create($request->only('name'));

        return response()->json([
            'success' => 'Category added successfully!',
            'category' => $category
        ]);
    }

  public function destroy($id)
    {
        $category = Category::findOrFail($id); // fetch manually
        $category->delete();

        return response()->json([
            'success' => 'Category deleted successfully!',
            'id' => $id
        ]);
    }


}
