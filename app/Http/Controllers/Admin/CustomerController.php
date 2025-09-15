<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function search(Request $request)
    {
        $query = $request->get('phone');

        $customers = Customer::where('contact', 'like', $query.'%')
                            ->limit(10)
                            ->get(['name', 'contact','location','emergency_contact']);

        return response()->json($customers);
    }
}
