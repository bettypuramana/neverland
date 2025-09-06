@extends('layouts.admin.admin_layout')

@section('content')
 <div class="d-flex justify-content-between align-items-center mb-2">
        <h2>Product Management</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">+ Add Products</a>
    </div>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
        <div class="card border-0 shadow-sm p-4">
            <table class="table table-bordered" id="Table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th>Last Purchased Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->last_purchased_date }}</td>
                        <td>
                            <a href="{{ route('products.movements', $product->id) }}" 
                            class="btn btn-sm btn-info">
                                View Movements
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
    </table>
</div>
@endsection
