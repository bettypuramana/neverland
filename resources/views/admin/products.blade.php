@extends('layouts.admin.admin_layout')
    @section('title')  Products || Neverland Aquatics  @endsection

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
                        <th>Purchase Add</th>
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
                            <button class="btn btn-sm btn-success add-movement-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#movementModal" 
                                    data-product-id="{{ $product->id }}">
                                + Add Movement
                            </button>
                        </td>
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
<!-- Movement Modal -->
<div class="modal fade" id="movementModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form method="POST" action="{{ route('products.movements.store') }}">
            @csrf
            <input type="hidden" name="product_id" id="modalProductId">

            <div class="modal-header">
                <h5 class="modal-title">Add Product Movement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="movement-container">
                    <div class="col-md-4 mb-3">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" required>
                    </div>

                    <!-- First Row -->
                    <div class="movement-row row align-items-end mb-2">
                        <div class="col-md-2">
                            <label>Type</label>
                            <select name="type[]" class="form-control">
                                <option value="">Select</option>
                                <option value="rent">Rent</option>
                                <option value="sale">Sale</option>
                                <option value="common">Common</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Quantity</label>
                            <input type="number" name="quantity[]" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label>Buy Price</label>
                            <input type="number" step="0.01" name="buy_price[]" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label>Sale Price</label>
                            <input type="number" step="0.01" name="sale_price[]" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <!-- First row only has Add button -->
                            <button type="button" class="btn btn-success add-row">+ Add More</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Movement</button>
            </div>
        </form>
    </div>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Pass product_id to modal hidden input
    document.querySelectorAll(".add-movement-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            let productId = this.getAttribute("data-product-id");
            document.getElementById("modalProductId").value = productId;
        });
    });

    // Function to refresh available options in all selects
    function refreshSelectOptions() {
        let selectedValues = Array.from(document.querySelectorAll("select[name='type[]']"))
            .map(select => select.value)
            .filter(val => val !== "");

        document.querySelectorAll("select[name='type[]']").forEach(select => {
            Array.from(select.options).forEach(option => {
                if (option.value !== "" && selectedValues.includes(option.value) && option.value !== select.value) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        });
    }

    // Add / Remove rows
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("add-row")) {
            let container = document.getElementById("movement-container");
            let firstRow = document.querySelector(".movement-row");
            let newRow = firstRow.cloneNode(true);

            // Clear inputs & reset select
            newRow.querySelectorAll("input").forEach(input => input.value = "");
            newRow.querySelector("select").value = "";

            // Replace Add button with Remove button
            let btnCol = newRow.querySelector(".col-md-2:last-child");
            btnCol.innerHTML = `<button type="button" class="btn btn-danger remove-row">Remove</button>`;

            container.appendChild(newRow);

            refreshSelectOptions();
        }

        if (e.target.classList.contains("remove-row")) {
            e.target.closest(".movement-row").remove();
            refreshSelectOptions();
        }
    });

    // Watch for select changes to update availability
    document.addEventListener("change", function (e) {
        if (e.target.name === "type[]") {
            refreshSelectOptions();
        }
    });
});
</script>


@endsection
