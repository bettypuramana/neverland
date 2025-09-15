<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductMovement;
use App\Models\Sale_main;
use App\Models\Sale_sub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
     public function index()
    {

        return view('admin.pos.sale_list');
    }
    public function sale_new()
    {
        $products = ProductMovement::get();
        return view('admin.pos.sale_new',compact('products'));
    }
    public function store(Request $request)
    {
        try {
        $user_id = Auth::id();

        $customer = Customer::where('contact', $request->phone)->first();

        if (empty($customer)) {
            $customer = new Customer;
            $customer->name = $request->name;
            $customer->location = $request->location;
            $customer->contact = $request->phone;
            $customer->emergency_contact = $request->emergency_contact;
            $customer->created_by = $user_id;
            $customer->save();
        }
            $floaty_status=0;
            $floaty_count=0;
            $floaty_advance=0;
            if($request->floaty_count!='' && $request->floaty_count>0){
                $floaty_status=1;
                $floaty_count=$request->floaty_count;
                $floaty_advance=$request->floaty_advance;
            }
            $paid_amount=0;
            if($request->payment_method){
                $paid_amount=$request->total_amount;
            }
            $sale_main = new Sale_main;
            $sale_main->customer_id = $customer->id;
            $sale_main->count = $request->members_count;
            $sale_main->in_time = $request->in_time;
            $sale_main->hours = $request->hours;
            $sale_main->end_time = $request->end_time;
            $sale_main->floaty_number = $floaty_count;
            $sale_main->floaty_advance = $floaty_advance;
            $sale_main->floaty_status = $floaty_status;
            $sale_main->date = $request->booking_date;
            $sale_main->total_amount = $request->total_amount;
            $sale_main->paid_amount = $paid_amount;
            $sale_main->payment_method = $request->payment_method;
            $sale_main->created_by = $user_id;
            $sale_main->save();
        if($request->item_id){
            foreach ($request->item_id as $index => $item_id) {
                $sale_sub = new Sale_sub;
                $sale_sub->sale_main_id = $sale_main->id;
                $sale_sub->item_id = $item_id;
                $sale_sub->movement_id = $request->movement_id[$index];
                $sale_sub->item_type = $request->type[$index];
                $sale_sub->quantity = $request->quantity[$index] ?? null;
                $sale_sub->item_price = $request->itemprice[$index];
                $sale_sub->created_by = $user_id;
                $sale_sub->save();
            }
        }
        return response()->json([
            'status'      => true,
            'message'     => 'saved successfully',
        ]);
        } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong: ' . $e->getMessage(),
        ], 500);
    }


    }
    public function rent_items_by_main_sale(Request $request)
    {
        $id=$request->input('id');

        $rentitems=Sale_sub::where('item_type','rent')->join('products', 'products.id', '=', 'sale_subs.item_id')->where('sale_main_id',$id)->select('name','quantity')->get();

        echo json_encode($rentitems);
    }
}
