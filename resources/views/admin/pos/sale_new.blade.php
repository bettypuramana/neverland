@extends('layouts.admin.pos_layout')
@section('title')
Visitor Entry - Neverland
@endsection
@section('content')
    <div class="row g-3">

        <div class="col-lg-8">
            <div class="left-panel-container card">
                <div class="shopping-bag-container p-4">
                    <div class="shopping-bag">
                        <h5 class="text-uppercase mb-4">Visitor Entry</h5>
                        <form id="saleForm">
                            @csrf
                            <input type="hidden" name="hour_charge" id="hourCharge" value="{{ env('HOUR_CHARGE') }}">
                            <div class="row mb-2">
                                <div class="col-3">
                                    <label for="">Contact</label>
                                    <input type="number" class="form-control" placeholder="Contact" name="phone"
                                        id="phone" autocomplete="off">
                                        <ul id="contactList" class="list-group" style="position: absolute; z-index: 999;"></ul>
                                    <p class="text-danger" id="contactError"></p>
                                </div>
                                <div class="col-3">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" placeholder="Name" id="name"
                                        name="name">
                                    <p class="text-danger" id="nameError"></p>
                                </div>
                                <div class="col-3">
                                    <label for="">Location</label>
                                    <input type="text" class="form-control" placeholder="Location" name="location"
                                        id="location">
                                    <p class="text-danger" id="locationError"></p>
                                </div>

                                <div class="col-3">
                                    <label for="">Emergency Contact</label>
                                    <input type="number" class="form-control" name="emergency_contact"
                                        id="emergencyContact" placeholder="Emergency Contact">
                                </div>
                            </div>



                            {{-- <div class="row mb-2"><b>Items</b></div> --}}
                            <div class="row mb-5">
                                <table class="table align-middle" id="cartTable">
                                    <thead class="text-uppercase text-secondary small">
                                        <tr>
                                            <th>Item</th>
                                            <th>type</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>


                            <div class="row mb-3">
                                <div class="col-2">
                                    <p>Floaty</p>
                                </div>
                                <div class="col-2">
                                    <label for="">Number</label>
                                    <input type="number" class="form-control" placeholder="Number" name="floaty_count">
                                </div>
                                <div class="col-2">
                                    <label for="">Advance</label>
                                    <input type="number" class="form-control" placeholder="Advance" name="floaty_advance">
                                </div>

                            </div>
                            {{-- <div class="row mb-2"><b>Timeing</b></div> --}}
                            <div class="row mb-2">
                                <div class="col-3">
                                    <label for="">No of Persons</label>
                                    <input type="number" class="form-control" name="members_count" id="membersCount"
                                        placeholder="No of Persons" onchange="getTotalPrice()">
                                    <p class="text-danger" id="membersCountError"></p>
                                </div>
                                <div class="col-3">
                                    <label for="">In Time</label>
                                    <input type="time" class="form-control" name="in_time" id="inTime"
                                        onchange="setEndtime();">
                                    <p class="text-danger" id="inTimeCountError"></p>
                                </div>
                                <div class="col-3">
                                    <label for="">Hours</label>
                                    <select name="hours" id="hours" onchange="setEndtime();getTotalPrice();"
                                        class="form-control">
                                        <option value="1">1 hr</option>
                                        <option value="1:30">1:30 hr</option>
                                        <option value="2">2 hr</option>
                                    </select>
                                    <p class="text-danger" id="hoursError"></p>
                                </div>
                                <div class="col-3">
                                    <label for="">End Time</label>
                                    <input type="time" class="form-control" name="end_time" id="endTime" readonly>
                                </div>



                            </div>
                            {{-- <div class="row mb-2"><b>Payments</b></div>
                            <div class="row mb-3">

                                    <div class="col-4">
                                    <label for="">Date</label>
                                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="">Total Amount</label>
                                    <input type="text" class="form-control" placeholder="0.00" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="">Advance Amount</label>
                                    <input type="text" class="form-control" placeholder="Amount" >
                                </div>


                            </div> --}}
                        </form>
                    </div>
                </div>
                {{-- <div class="card-footer bg-light">
                            Please confirm that the balance of <strong>£95.00</strong> should be settled on account <strong>1413412343</strong>.
                        </div> --}}

            </div>
        </div>

        <div class="col-lg-4">
            <div class="right-panel">

                <div class="card flex-grow-1 p-4 mb-2">

                    <div class="row mb-2">
                        <div class="col">
                            <select name="add_item" id="addItem" class="form-control">
                                <option value="">Item</option>
                                @if (!empty($products))
                                    @foreach ($products as $product)
                                        <option value="{{ $product->product->id }}" data-movement_id="{{ $product->id }}"
                                            data-price="{{ $product->sale_price }}" data-type={{ $product->movement_type }}
                                            data-name={{ $product->product->name }}>{{ $product->product->name }} (
                                            {{ $product->quantity }} ) - {{ $product->movement_type }} -
                                            {{ $product->sale_price }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Quantity" name="add_quantity"
                                id="addQuantity">
                        </div>
                    </div>
                    <div class="d-grid mb-3">
                        <button class="btn btn-success text-uppercase" onclick="addItem()">Add</button>
                    </div>


                    <hr>
                    <p class="text-success" id="insertSuccess"></p>
                    <p class="text-danger" id="insertError"></p>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-6 col-form-label">Date</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control-plaintext" name="booking_date"
                                value="{{ date('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-6 col-form-label">Total Amount</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control-plaintext" name="total_amount" id="totalAmount"
                                placeholder="0.00" readonly>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="staticEmail" class="col-sm-6 col-form-label">Payment Method</label>
                        <div class="col-sm-6 d-flex">
                            <div>
                                <input type="radio" name="payment_method" value="G pay" class="form-check-input"
                                    placeholder="0.00" readonly>
                                <label for="">G pay</label>
                            </div>
                            <div>
                                <input type="radio" name="payment_method" value="Cash" class="form-check-input"
                                    placeholder="0.00" readonly>
                                <label for="">Cash</label>
                            </div>

                        </div>
                    </div>



                    <div class="d-grid">
                        <button class="btn btn-danger btn-lg text-uppercase py-3" onclick="insertSale()">Confirm</button>
                    </div>

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

            if (!inTimeInput.value) return;

            // Parse in_time
            let [inHours, inMinutes] = inTimeInput.value.split(":").map(Number);

            // Parse hours select
            let selected = hoursSelect.value; // e.g., "1" or "1:30"
            let addHours = 0,
                addMinutes = 0;

            if (selected.includes(":")) {
                const parts = selected.split(":");
                addHours = parseInt(parts[0]);
                addMinutes = parseInt(parts[1]);
            } else {
                addHours = parseInt(selected);
            }

            // Calculate end time
            let endDate = new Date();
            endDate.setHours(inHours + addHours);
            endDate.setMinutes(inMinutes + addMinutes);

            // Format as HH:MM
            const hh = String(endDate.getHours()).padStart(2, "0");
            const mm = String(endDate.getMinutes()).padStart(2, "0");

            endTimeInput.value = `${hh}:${mm}`;
        }

        function insertSale() {
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
                document.getElementById("membersCountError").textContent = "Please enter number of persons";
                isValid = false;
            }

            const inTime = document.getElementById("inTime").value;
            if (inTime == '') {
                document.getElementById("inTimeCountError").textContent = "Please select in time";
                isValid = false;
            }

            const hours = document.getElementById("hours").value;
            if (hours == '') {
                document.getElementById("hoursError").textContent = "Please select hours";
                isValid = false;
            }

            if (!isValid) return;
            const extraFields = document.querySelectorAll(`
                    input[name="booking_date"],
                    input[name="total_amount"],
                    input[name="payment_method"]:checked
                `);

            extraFields.forEach(input => {
                formData.append(input.name, input.value);
            });

            $.ajax({
                url: "{{ route('admin.sales.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {

                        document.getElementById("insertSuccess").textContent = response.message;
                        document.querySelector("input[name='total_amount']").value = "";
                        document.querySelectorAll("input[name='payment_method']").forEach(el => el.checked =
                            false);
                        // reset form
                        form.reset();
                        $("#cartTable tbody").empty();
                    } else {
                        document.getElementById("insertError").textContent = "Something went wrong. Try again";
                    }
                },

                error: function(e) {
                    //    loader_off();
                }
            });
        }

        function getTotalPrice() {
            // Base price (persons × hours × charge)
            let membersCount = parseFloat(document.getElementById("membersCount")?.value) || 0;
            let hoursValue = document.getElementById("hours")?.value || "0";
            let hourCharge = parseFloat(document.getElementById("hourCharge")?.value) || 0;

            let hours = 0;
            if (hoursValue.includes(":")) {
                // Handle "H:MM" format
                const [h, m] = hoursValue.split(":").map(Number);
                hours = h + (m / 60);
            } else {
                hours = parseFloat(hoursValue) || 0;
            }

            let total = membersCount * hours * hourCharge;

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
        }

        $(document).ready(function() {
            $('#phone').on('keyup', function() {
                let query = $(this).val();
                $('#name').val('');
                $('#location').val('');
                $('#emergencyContact').val('');
                if (query.length > 0) {
                    $.ajax({
                        url: '{{ route('customer.search') }}',
                        type: 'GET',
                        data: {
                            phone: query
                        },
                        success: function(data) {
                            let list = $('#contactList');
                            list.empty();
                            if (data.length > 0) {

                                data.forEach(function(customer) {
                                    list.append(
                                        '<li class="list-group-item list-group-item-action" data-name="' +
                                        customer.name + '">' + customer.contact +
                                        ' - ' + customer.name +' - ' + customer.location +' - ' + customer.emergency_contact + '</li>');
                                });
                            }
                            // else {
                            //     list.append(
                            //     '<li class="list-group-item">No results found</li>');
                            // }
                        }
                    });
                } else {
                    $('#contactList').empty();
                }
            });

            // Click on list item
            $(document).on('click', '#contactList li', function() {
                $('#phone').val($(this).text().split(' - ')[0]);
                $('#name').val($(this).text().split(' - ')[1]);
                $('#location').val($(this).text().split(' - ')[2]);
                $('#emergencyContact').val($(this).text().split(' - ')[3]);
                $('#contactList').empty();

            });
        });
    </script>
@endsection
