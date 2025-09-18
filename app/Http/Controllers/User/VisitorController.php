<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ProductMovement;
use App\Models\Sale_main;
use App\Models\Sale_sub;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    // Show visitor entry form
    public function create()
    {
        $products = ProductMovement::where('movement_type','!=','common')->where('quantity', '>', 0)->get();
        return view('user.visitors_form',compact('products'));
    }
    // Generate QR Code
    public function qr()
    {
        $url = route('visitor.form'); // QR will redirect here
        return view('user.qr', compact('url'));
    }
    public function get_info(Request $request)
    {
        $phone = $request->get('phone');

        $customer = Customer::where('contact', $phone)->select('name', 'contact','location','emergency_contact')->first();

        return response()->json($customer);
    }
    public function store(Request $request)
    {
        try {

        $customer = Customer::where('contact', $request->phone)->first();

        if (empty($customer)) {
            $customer = new Customer;
            $customer->name = $request->name;
            $customer->location = $request->location;
            $customer->contact = $request->phone;
            $customer->emergency_contact = $request->emergency_contact;
            $customer->created_by = 0;
            $customer->save();
        }
            $sale_main = new Sale_main;
            $sale_main->customer_id = $customer->id;
            $sale_main->count = $request->members_count;
            $sale_main->in_time = $request->in_time;
            $sale_main->hours = $request->hours;
            $sale_main->end_time = $request->end_time;
            $sale_main->floaty_number = 0;
            $sale_main->floaty_advance = 0;
            $sale_main->floaty_status = 0;
            $sale_main->date = $request->booking_date;
            $sale_main->total_amount = $request->total_amount;
            $sale_main->paid_amount = 0;
            $sale_main->created_by = 0;
            $sale_main->save();
        if($request->item_id){
            $rent_count=0;
            foreach ($request->item_id as $index => $item_id) {
                $sale_sub = new Sale_sub;
                $sale_sub->sale_main_id = $sale_main->id;
                $sale_sub->item_id = $item_id;
                $sale_sub->movement_id = $request->movement_id[$index];
                $sale_sub->item_type = $request->type[$index];
                $sale_sub->quantity = $request->quantity[$index] ?? null;
                $sale_sub->item_price = $request->itemprice[$index];
                $sale_sub->created_by = 0;
                $sale_sub->save();

                if($request->type[$index]=='sale'){
                    $qty = $request->quantity[$index];
                    $ProductMovement = ProductMovement::find($request->movement_id[$index]);

                    if ($ProductMovement) {
                        $ProductMovement->quantity -= $qty;
                        $ProductMovement->save();
                    }

                }


                if($request->type[$index]=='rent'){
                    $rent_count+=1;
                }
            }
            if($rent_count>0){
                $visitor = Sale_main::find($sale_main->id);
                $visitor->item_return_status = 0;
                $visitor->save();
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
}
