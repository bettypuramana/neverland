<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductMovement;
use Auth;
use Carbon\Carbon;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $products = Product::with('movements') // eager load movements if needed
        ->get()
        ->map(function($product) {
            // Get last purchased date from movements (if any)
            $lastPurchase = $product->movements()
                //->where('type', 'purchase')
                ->latest('purchase_date')
                ->first();

            $product->last_purchased_date = $lastPurchase
    ? Carbon::parse($lastPurchase->purchase_date)->format('d-m-Y')
    : '-';
            return $product;
        });

        return view('admin.products', compact('products'));
    }
    public function movements($id)
    {
        $product = Product::findOrFail($id);
        $movements = $product->movements()->orderBy('purchase_date', 'desc')->get();

        return view('admin.product_movements', compact('product', 'movements'));
    }

    public function create()
    {
        return view('admin.add_products');
    }

    public function store(Request $request)
{
    $user_id = Auth::id();

    // Validate
    $request->validate([
        'product_name'   => 'required|string|max:255',
        'sku'            => 'nullable|string|max:100',
        'purchase_date'  => 'required|date',
        'type.*'         => 'required|string|in:rent,sale,common',
        'quantity.*'     => 'required|integer|min:1',
        'buy_price.*'    => 'required|numeric|min:0',
        'sale_price.*'   => 'nullable|numeric|min:0',
    ]);

    // 🔹 Check if product already exists by name
    $product = Product::where('name', $request->product_name)->first();

    if ($product) {
        // ✅ Update product if already exists
        $product->sku = $request->sku ?? $product->sku;
        $product->updated_by = $user_id;
        $product->save();
    } else {
        // ✅ Insert new product
        $product = new Product();
        $product->name = $request->input('product_name');
        $product->sku = $request->input('sku');
        $product->opening_stock = 0;
        $product->created_by = $user_id;
        $product->save();
    }

    // 🔹 Insert into product_movements for each row
    foreach ($request->type as $index => $type) {
      
        $ProductMovement = new ProductMovement();
        $ProductMovement->product_id = $product->id;
        $ProductMovement->purchase_date = $request->purchase_date;
        $ProductMovement->movement_type = $type;
        $ProductMovement->buy_price = $request->buy_price[$index];
        $ProductMovement->sale_price = $request->sale_price[$index] ?? null;
        $ProductMovement->quantity = $request->quantity[$index];
        $ProductMovement->created_by = $user_id;
        $ProductMovement->save();
    }

    return redirect()->route('products.index')->with('success', 'Product saved successfully!');
}
    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        $products = Product::where('name', 'LIKE', "%{$query}%")
                    ->limit(10)
                    ->get(['id', 'name', 'sku']); // return id + sku + name

        return response()->json($products);
    }

}
