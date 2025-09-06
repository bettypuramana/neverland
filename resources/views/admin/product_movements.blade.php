@extends('layouts.admin.admin_layout')

@section('content')
 <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>Movements for {{ $product->name }} ({{ $product->sku }})</h3>
    <div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">← Back</a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Products</a>
        </div>
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
                <th>Date</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Buy Price</th>
                <th>Sale Price</th>
            </tr>
        </thead>
        <tbody>
        @foreach($movements as $move)
            <tr>
                <td>{{ \Carbon\Carbon::parse($move->purchase_date)->format('d-m-Y') }}</td>
                <td>{{ ucfirst($move->movement_type) }}</td>
                <td>{{ $move->quantity }}</td>
                <td>{{ $move->buy_price }}</td>
                <td>{{ $move->sale_price }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
