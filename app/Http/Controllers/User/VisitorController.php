<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    // Show visitor entry form
    public function create()
    {
        return view('user.visitors_form');
    }

    // Save visitor entry
    public function store(Request $request)
    {
        // ✅ Store data in DB or handle as needed
        // Example: Visitor::create($request->all());

        return back()->with('success', 'Visitor entry submitted successfully!');
    }

    // Generate QR Code
    public function qr()
    {
        $url = route('visitor.form'); // QR will redirect here
        return view('user.qr', compact('url'));
    }
}