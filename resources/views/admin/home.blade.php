@extends('layouts.admin.admin_layout')

@section('title') Dashboard || Neverland Aquatics @endsection

@push('styles')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <style>
        .stat-card {
            transition: transform .2s ease-in-out, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .stat-icon {
            font-size: 2.5rem;
        }
        /* Soft card background colors */
        .bg-soft-success {
            background: rgba(25, 135, 84, 0.1); /* Bootstrap success with 10% opacity */
        }
        .bg-soft-danger {
            background: rgba(220, 53, 69, 0.1);
        }
        .bg-soft-primary {
            background: rgba(13, 110, 253, 0.1);
        }
        .bg-soft-info {
            background: rgba(13, 202, 240, 0.1);
        }

        /* Text readability */
        .stat-card h3 {
            font-weight: 700;
        }

    </style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <div class="row g-4">
        <!-- Income -->
        <div class="col-md-3">
            <div class="card stat-card border-0 rounded-3 bg-soft-success h-100">
                <div class="card-body text-center">
                    <div class="mb-2 text-success">
                        <i class="fa-solid fa-wallet stat-icon"></i>
                    </div>
                    <h6 class="text-muted">Total Income (Today)</h6>
                    <h3 class="fw-bold text-success">{{ number_format($totalIncome,2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Expense -->
        <div class="col-md-3">
            <div class="card stat-card border-0 rounded-3 bg-soft-danger h-100">
                <div class="card-body text-center">
                    <div class="mb-2 text-danger">
                        <i class="fa-solid fa-arrow-trend-down stat-icon"></i>
                    </div>
                    <h6 class="text-muted">Total Expense (Today)</h6>
                    <h3 class="fw-bold text-danger">{{ number_format($totalExpense,2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Settlement -->
        <div class="col-md-3">
            <div class="card stat-card border-0 rounded-3 bg-soft-primary h-100">
                <div class="card-body text-center">
                    <div class="mb-2 text-primary">
                        <i class="fa-solid fa-handshake stat-icon"></i>
                    </div>
                    <h6 class="text-muted">Total Settlement (Today)</h6>
                    <h3 class="fw-bold text-primary">{{ number_format($totalSettlement,2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Purchases -->
        <div class="col-md-3">
            <div class="card stat-card border-0 rounded-3 bg-soft-info h-100">
                <div class="card-body text-center">
                    <div class="mb-2 text-info">
                        <i class="fa-solid fa-cart-shopping stat-icon"></i>
                    </div>
                    <h6 class="text-muted">Total Item Purchase (Today)</h6>
                    <h3 class="fw-bold text-info">{{ number_format($totalItemPurchase,2) }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Today's Settled List -->
    <div class="card mt-5 shadow-sm border-0 rounded-3">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="fa-solid fa-list-check me-2 text-primary"></i> Today's Settlement List
            </h6>
            <span class="badge bg-secondary">{{ $todaySettled->count() }} records</span>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="settledTable" class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th class="text-end">Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todaySettled as $settle)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($settle->date)->format('d-m-Y') }}</td>
                                <td>{{ $settle->category_name }}</td>
                                <td class="text-end fw-semibold">{{ number_format($settle->amount, 2) }}</td>
                                <td>
                                    <span class="badge px-3 py-2 rounded-pill {{ $settle->settled == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $settle->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    <i class="fa-solid fa-circle-info me-2"></i> No settlements today
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#settledTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@endpush
