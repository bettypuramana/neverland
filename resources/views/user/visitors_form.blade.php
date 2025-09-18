@extends('layouts.user.user_layout')

@section('content')
<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-bold text-dark">VISITOR ENTRY</h5>
                </div>
                <div class="card-body">
                    <span class="text-danger" id="insertError"></span>
                    <form id="saleForm" method="POST" class="row g-3">
                        @csrf
                        <input type="hidden" name="hour_charge" id="hourCharge" value="{{ env('HOUR_CHARGE') }}">
                        <input type="hidden" name="half_hour_charge" id="halfHourCharge" value="{{ env('HALF_HOUR_CHARGE') }}">
                        <!-- Contact, Name, Location, Emergency -->
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="number" name="phone" id="phone" class="form-control" placeholder="Contact Number" >
                                <span class="text-danger" id="contactError"></span>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Name" onfocus="getInfo();" >
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="location" id="location" class="form-control" placeholder="Location">
                                <span class="text-danger" id="locationError"></span>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="emergency_contact" id="emergencyContact" class="form-control" placeholder="Emergency Contact">
                                <span class="text-danger" id="emergencyContact"></span>
                            </div>
                        </div>
                        <div class="row g-2">
                            <input type="hidden" class="form-control" name="in_time" id="inTime" value="{{ \Carbon\Carbon::now()->addMinutes(10)->format('H:i') }}">
                            <input type="hidden" class="form-control" name="end_time" id="endTime">
                            <div class="col-5">
                                <input type="number" name="members_count" id="membersCount" class="form-control" placeholder="No of Persons" onchange="getTotalPrice()">
                                <span class="text-danger" id="membersCountError"></span>
                            </div>


                            <div class="col-5">
                                <select name="hours" id="hours" class="form-select" onchange="setEndtime();getTotalPrice();">
                                    <option value="60">1 hr</option>
                                    <option value="90">1:30 hr</option>
                                    <option value="120">2 hr</option>
                                    <option value="150">2:30 hr</option>
                                    <option value="180">3 hr</option>
                                </select>
                                <span class="text-danger" id="hoursError"></span>
                            </div>

                            <div class="col-2">
                                <button type="button" class="btn btn-success w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#itemModal">
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Item List Header -->
                        <div class="mt-3 table-responsive">
                            <table class="table table-bordered align-middle" id="cartTable">
                                <thead class="table-light" >
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- Date & Total -->
                        <div class="p-3 bg-light rounded-3 shadow-sm d-flex justify-content-between">
                            <input type="hidden" name="booking_date" value="{{ date('Y-m-d') }}" readonly>
                            <input type="hidden" name="total_amount" id="totalAmount" readonly>
                            <div><strong>Date:</strong> <span id="totalBookingDate">09/16/2025</span></div>
                            <div><strong>Total Amount:</strong> <span class="text-success" id="totalAmountSpan">₹0</span></div>
                            </div>



                        <!-- Confirm -->
                        <div class="col-12">
                            <button class="btn btn-danger btn-lg w-100 fw-bold shadow-sm" onclick="insertSale()">
                                CONFIRM
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">

      <!-- Modal Header -->
      <div class="modal-header text-white rounded-top-4">
        <h5 class="modal-title fw-bold">Add Item</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body bg-light">
        <select name="add_item" id="addItem" class="form-control mb-3">
            @if (!empty($products))
                @foreach ($products as $product)
                    <option value="{{ $product->product->id }}" data-movement_id="{{ $product->id }}"
                        data-price="{{ $product->sale_price }}" data-type={{ $product->movement_type }}
                        data-name={{ $product->product->name }}>{{ $product->product->name }} ( {{ $product->movement_type }} )  -
                        ₹ {{ $product->sale_price }}
                    </option>
                @endforeach
            @endif
        </select>
        <input type="number" name="add_quantity" id="addQuantity" class="form-control mb-3" placeholder="Quantity">
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer bg-light rounded-bottom-4">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary fw-bold" onclick="addItem()">Add</button>
      </div>
    </div>
  </div>
</div>



@endsection
@section('js')
<script>
    function addItem() {
            event.preventDefault();
            let select = document.getElementById("addItem");
            let quantity = document.getElementById("addQuantity").value;
            let tableBody = document.querySelector("#cartTable tbody");

            let selected = select.options[select.selectedIndex];
            if (!selected.value || quantity <= 0) {
                alert("Please select an item and enter a valid quantity");
                return;
            }

            let item_id = document.getElementById("addItem").value;
            let name = selected.dataset.name;
            let movement_id = selected.dataset.movement_id;
            let type = selected.dataset.type;
            let price = parseFloat(selected.dataset.price);
            let totalPrice = (price * quantity).toFixed(2);

            let row = document.createElement("tr");
            row.innerHTML = `
            <td>${name}</td>
            <td>${type}<input type="hidden" name="item_id[]" value="${item_id}"><input type="hidden" name="movement_id[]" value="${movement_id}"><input type="hidden" name="type[]" value="${type}"><input type="hidden" name="itemprice[]" value="${price}"></td>
            <td>
                <div class="input-group input-group-sm" style="width: 120px;">
                    <button class="btn btn-outline-secondary" onclick="updateQty(this, -1)">-</button>
                    <input type="text" class="form-control text-center" name="quantity[]" value="${quantity}">
                    <button class="btn btn-outline-secondary" onclick="updateQty(this, 1)">+</button>
                </div>
            </td>
            <td >${totalPrice}</td>
            <td >
                <button class="btn btn-sm btn-outline-danger" onclick="removeItem(this)">
                    <i class="bi bi-x"></i>
                </button>
            </td>
        `;
            tableBody.appendChild(row);

            // reset input
            select.value = "";
            document.getElementById("addQuantity").value = "";
            getTotalPrice();
        }
        function updateQty(btn, change) {
            event.preventDefault();
            let input = btn.parentNode.querySelector("input");
            let qty = parseInt(input.value) + change;
            if (qty < 1) return;
            input.value = qty;

            // update price
            let row = btn.closest("tr");
            let pricePerUnit = parseFloat(row.querySelector("td:nth-child(4)").innerText) / (qty - change);
            row.querySelector("td:nth-child(4)").innerText = (pricePerUnit * qty).toFixed(2);

            getTotalPrice();
        }

        function removeItem(btn) {
            event.preventDefault();
            btn.closest("tr").remove();

            getTotalPrice();
        }

        function setEndtime() {
            const inTimeInput = document.getElementById("inTime");
            const hoursSelect = document.getElementById("hours");
            const endTimeInput = document.getElementById("endTime");

            if (!inTimeInput.value || !hoursSelect.value) return;

            // Parse input time
            let [inHours, inMinutes] = inTimeInput.value.split(":").map(Number);

            // Get duration in minutes
            let durationMinutes = parseInt(hoursSelect.value);

            // Create Date object for calculation
            let endDate = new Date();
            endDate.setHours(inHours);
            endDate.setMinutes(inMinutes + durationMinutes);

            // Format HH:MM
            const hh = String(endDate.getHours()).padStart(2, "0");
            const mm = String(endDate.getMinutes()).padStart(2, "0");

            endTimeInput.value = `${hh}:${mm}`;
        }
        function getTotalPrice() {
            // Base price (persons × hours × charge)
            let membersCount = parseFloat(document.getElementById("membersCount")?.value) || 0;
            let hoursValue = document.getElementById("hours")?.value || "0";
            let hourCharge = parseFloat(document.getElementById("hourCharge")?.value) || 0;
            let halfHourCharge = parseFloat(document.getElementById("halfHourCharge")?.value) || 0;

            let onePersonCharge = 0;
            let fullHours = Math.floor(hoursValue / 60);
            let remainingMinutes = hoursValue % 60;
            onePersonCharge = (fullHours * hourCharge) + (remainingMinutes >= 30 ? halfHourCharge : 0);
            let total = membersCount * onePersonCharge;

            // Add up cart items (quantity[] * itemprice[])
            const quantities = document.querySelectorAll('input[name="quantity[]"]');
            const prices = document.querySelectorAll('input[name="itemprice[]"]');

            quantities.forEach((qtyInput, index) => {
                let qty = parseFloat(qtyInput.value) || 0;
                let price = parseFloat(prices[index]?.value) || 0;
                total += qty * price;
            });

            // Update totalAmount field
            document.getElementById("totalAmount").value = total.toFixed(2);
            document.getElementById("totalAmountSpan").textContent = total.toFixed(2);
        }
        function getInfo(){
        event.preventDefault();
        $('#name').val('');
        $('#location').val('');
        $('#emergencyContact').val('');
        const phone = document.getElementById("phone").value;
        if(phone!=''){
        $.ajax({
                url: "{{route('visitor.get_info')}}",
                type: "POST",
                data: {
                        "_token": "{{ csrf_token() }}",
                        phone: phone,
                    },
                    dataType: 'json',
                    success: function(res)
                        {

                                $('#name').val(res.name);
                                $('#location').val(res.location);
                                $('#emergencyContact').val(res.emergency_contact);


                        },
                        error: function(e)
                        {
                        //    loader_off();
                        }
                });
            }
            }
            function insertSale() {
            event.preventDefault();
            let form = document.getElementById("saleForm");
            let formData = new FormData(form);

            document.querySelectorAll("p.text-danger").forEach(el => el.textContent = "");
            let isValid = true;

            const phone = document.getElementById("phone").value;
            if (phone == '') {
                document.getElementById("contactError").textContent = "Please enter contact number";
                isValid = false;
            }

            const name = document.getElementById("name").value;
            if (name == '') {
                document.getElementById("nameError").textContent = "Please enter name";
                isValid = false;
            }

            const location = document.getElementById("location").value;
            if (location == '') {
                document.getElementById("locationError").textContent = "Please enter location";
                isValid = false;
            }

            const membersCount = document.getElementById("membersCount").value;
            if (membersCount == '' || membersCount <= 0) {
                document.getElementById("membersCountError").textContent = "Please fill";
                isValid = false;
            }

            const hours = document.getElementById("hours").value;
            if (hours == '') {
                document.getElementById("hoursError").textContent = "Please select hours";
                isValid = false;
            }

            if (!isValid) return;
            $.ajax({
                url: "{{ route('visitor.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {


                    } else {
                        document.getElementById("insertError").textContent = "Something went wrong. Try again";
                    }
                },

                error: function(e) {
                    //    loader_off();
                }
            });
        }
</script>
@endsection
