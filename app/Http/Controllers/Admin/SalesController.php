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
        $products = ProductMovement::where('movement_type','!=','common')->get();
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
            $rent_count=0;
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
    public function rent_items_by_main_sale(Request $request)
    {
        $id=$request->input('id');

        $rentitems=Sale_sub::where('item_type','rent')->join('products', 'products.id', '=', 'sale_subs.item_id')->join('product_movements', 'product_movements.id', '=', 'sale_subs.movement_id')->where('sale_main_id',$id)->select('products.name','sale_subs.quantity','sale_subs.id as sub_id','sale_subs.item_id','sale_subs.movement_id','product_movements.sale_price','product_movements.sale_rent_price')->get();

        echo json_encode($rentitems);
    }
    public function convert_to_sale(Request $request)
    {
        try {
        $user_id = Auth::id();
            $request->id;
            $request->convert_quantity;
            $request->item_id;
            $request->sale_price;
            $request->sale_rent_price;

            $ProductMovement = ProductMovement::find($request->movement_id);

            if ($ProductMovement) {
                $ProductMovement->quantity -= $request->convert_quantity;
                $ProductMovement->save();
            }
            $salesub = Sale_sub::find($request->sub_id);
            if ($salesub) {
                $salesub->quantity -= $request->convert_quantity;
                if ($salesub->quantity <= 0) {
                    $salesub->delete();
                } else {
                    $salesub->save();
                }
            }
            $newsaleSub = new Sale_sub;
            $newsaleSub->sale_main_id = $request->id;
            $newsaleSub->item_id      = $request->item_id;
            $newsaleSub->movement_id  = $request->movement_id;
            $newsaleSub->item_type    = 'sale';
            $newsaleSub->quantity     = $request->convert_quantity;
            $newsaleSub->item_price   = $request->sale_rent_price; // or $request->sale_rent_price if needed
            $newsaleSub->created_by   = $user_id;
            $newsaleSub->save();

            $salemain = Sale_main::find($request->id);
            if ($salemain) {
                $salemain->total_amount -= ($request->sale_price * $request->convert_quantity);
                $salemain->total_amount += ($request->sale_rent_price * $request->convert_quantity);
            $salemain->save();
            }
        return response()->json([
            'status'      => true,
            'message'     => 'Updated successfully',
        ]);
        } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong: ' . $e->getMessage(),
        ], 500);
    }


    }
    public function confirm_return(Request $request)
    {
        try {
            $user_id = Auth::id();

            $salemain = Sale_main::find($request->id);
            if ($salemain) {
                $salemain->item_return_status = 1;
                $salemain->save();
            }
            return response()->json([
                'status'      => true,
                'message'     => 'Retrun updated successfully',
            ]);
            } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
    }


    }
    public function edit($id)
    {
        $products = ProductMovement::where('movement_type','!=','common')->get();
        $mainsale=Sale_main::where('id',$id)->first();
        return view('admin.pos.sale_edit',compact('mainsale','products'));
    }
    public function update(Request $request, $id)
    {
        try {
        $user_id = Auth::id();

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
            $sale_main = Sale_main::find($id);
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

            $oldsubids=Sale_sub::where('sale_main_id', $id)->pluck('id')->toArray();
            $newsubids=$request->sub_id ?? [];
            $subIdToDelete = array_diff($oldsubids, array_filter($newsubids));
            if (!empty($subIdToDelete)) {
                $subsToDelete = Sale_sub::whereIn('id', $subIdToDelete)->get();

                foreach ($subsToDelete as $sub) {
                    // If deleted item was a sale, return qty back to stock
                    if ($sub->item_type == 'sale') {
                        $ProductMovement = ProductMovement::find($sub->movement_id);
                        if ($ProductMovement) {
                            $ProductMovement->quantity += $sub->quantity; // restore stock
                            $ProductMovement->save();
                        }
                    }
                }

                Sale_sub::whereIn('id', $subIdToDelete)->delete();
            }
        if($request->item_id){
            $rent_count=0;
            foreach ($request->item_id as $index => $item_id) {

                $sub_id = $request->sub_id[$index] ?? null;
                if ($sub_id) {
                    $sale_sub = Sale_sub::find($sub_id);
                } else {
                    $sale_sub = new Sale_sub();
                    $sale_sub->sale_main_id = $id;
                    $sale_sub->created_by = $user_id;
                }
                $sale_sub->item_id = $item_id;
                $sale_sub->movement_id = $request->movement_id[$index];
                $sale_sub->item_type = $request->type[$index];
                $sale_sub->quantity = $request->quantity[$index] ?? null;
                $sale_sub->item_price = $request->itemprice[$index];
                $sale_sub->created_by = $user_id;
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
