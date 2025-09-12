@extends('layouts.admin.admin_layout')
    @section('title')  Settlements || Neverland Aquatics  @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-2">
    <h2>Settlement</h2>
</div>

<!-- Filter Form -->
<div class="card border-0 shadow-sm p-4 mb-4">
    <form action="{{ route('settlements.index') }}" method="GET">
        <div class="row">
            <div class="col-md-3">
                <label>Select Date</label>
                <input type="date" name="date" value="{{ $date }}" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </div>
    </form>
</div>

<!-- Settlement List -->
<div class="card border-0 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center">
        <h5>Settlements for {{ $date }}</h5>
        <form action="{{ route('settlements.settle') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <button type="submit" class="btn btn-success">Settle</button>
        </form>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($settlements as $settlement)
                <tr>
                    <td>{{ $settlement->date }}</td>
                    <td>{{ $settlement->category->name }}</td>
                    <td>{{ number_format($settlement->amount, 2) }}</td>
                    <td>
                        <form action="{{ route('settlements.destroy', $settlement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No settlements found</td>
                </tr>
            @endforelse
        </tbody>
        @if($settlements->count() > 0)
        <tfoot>
            <tr>
                <th colspan="2" class="text-end">Total</th>
                <th>{{ number_format($total, 2) }}</th>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
@endsection
