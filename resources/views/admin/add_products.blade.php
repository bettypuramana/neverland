@extends('layouts.admin.admin_layout')
@section('title')  Add Products || Neverland Aquatics  @endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-2">
    <h2>Add Products</h2>
</div>

<div class="card border-0 shadow-sm p-4">
    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <!-- Top Section -->
        <div class="row mb-3">
            <div class="col-md-4 position-relative">
                <label for="product_name">Product Name</label>
                <input type="text" name="product_name" id="product_name" class="form-control product-autocomplete" autocomplete="off" required>
                <!-- Autocomplete dropdown -->
                <ul id="product_suggestions" class="list-group position-absolute w-100" style="z-index:1000; display:none;"></ul>
            </div>

            <div class="col-md-4">
                <label for="sku">SKU</label>
                <input type="text" name="sku" id="sku" class="form-control">
            </div>

            <div class="col-md-4">
                <label for="purchase_date">Purchase Date</label>
                <input type="date" name="purchase_date" class="form-control" required>
            </div>
        </div>

        <!-- Dynamic Section -->
        <div id="movement-container">
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
                <div class="col-md-2 rent-price"  style="display:none;">
                    <label>Rent Damage Price</label>
                    <input type="number" name="rent_damage_price[]" class="form-control" step="0.01">
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-success add-row">+ Add More</button>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Save Product</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let container = document.getElementById("movement-container");

    // 🔹 Refresh select options to prevent duplicates
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

    // 🔹 Add / Remove Row
    container.addEventListener("click", function(e) {
        if (e.target.classList.contains("add-row")) {
            let row = e.target.closest(".movement-row");
            let clone = row.cloneNode(true);

            // Clear cloned row inputs
            clone.querySelectorAll("input").forEach(input => input.value = "");
            clone.querySelector("select").value = "";

            // Hide Rent Damage Price in new row
            clone.querySelector(".rent-price").style.display = "none";

            // Change Add button → Remove
            let btn = clone.querySelector("button");
            btn.classList.remove("btn-success", "add-row");
            btn.classList.add("btn-danger", "remove-row");
            btn.textContent = "Remove";

            // Add event listener for type select in cloned row
            clone.querySelector("select[name='type[]']").addEventListener('change', function() {
                const rentPriceInput = this.closest('.movement-row').querySelector('.rent-price');
                if(this.value === 'rent') {
                    rentPriceInput.style.display = 'block';
                } else {
                    rentPriceInput.style.display = 'none';
                    rentPriceInput.querySelector('input').value = '';
                }
            });

            container.appendChild(clone);

            refreshSelectOptions();
        }


        if (e.target.classList.contains("remove-row")) {
            e.target.closest(".movement-row").remove();
            refreshSelectOptions();
        }
    });

    // 🔹 Update selects when value changes
    document.addEventListener("change", function(e) {
        if (e.target.name === "type[]") {
            refreshSelectOptions();
        }
    });

    // 🔹 Autocomplete search (after 4 letters)
    $('#product_name').on('keyup', function(){
        let query = $(this).val();
        if(query.length >= 4){
            $.ajax({
                url: "{{ route('products.autocomplete') }}",
                type: "GET",
                data: {query: query},
                success: function(data){
                    let list = $('#product_suggestions');
                    list.empty().show();
                    if(data.length > 0){
                        $.each(data, function(index, product){
                            list.append('<li class="list-group-item suggestion-item" data-id="'+product.id+'" data-sku="'+product.sku+'">'+product.name+'</li>');
                        });
                    } else {
                        list.hide();
                    }
                }
            });
        } else {
            $('#product_suggestions').hide();
        }
    });

    // 🔹 Click on suggestion
    $(document).on('click', '.suggestion-item', function(){
        $('#product_name').val($(this).text());
        $('#sku').val($(this).data('sku'));
        $('#product_suggestions').hide();
    });
});

document.querySelectorAll('select[name="type[]"]').forEach(select => {
    select.addEventListener('change', function() {
        const rentPriceInput = this.closest('.movement-row').querySelector('.rent-price');
        if(this.value === 'rent') {
            rentPriceInput.style.display = 'block';
        } else {
            rentPriceInput.style.display = 'none';
            rentPriceInput.querySelector('input').value = '';
        }
    });
});

</script>


@endsection
