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
                    <form action="{{ route('visitor.store') }}" method="POST" class="row g-3">
                        @csrf
                        <!-- Contact, Name, Location, Emergency -->
                        <div class="row g-2">
                            <div class="col-md-3"><input type="text" name="contact" class="form-control" placeholder="Contact" required></div>
                            <div class="col-md-3"><input type="text" name="name" class="form-control" placeholder="Name" required></div>
                            <div class="col-md-3"><input type="text" name="location" class="form-control" placeholder="Location"></div>
                            <div class="col-md-3"><input type="text" name="emergency_contact" class="form-control" placeholder="Emergency Contact"></div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-3"><input type="number" name="persons" class="form-control" placeholder="No of Persons"></div>
                            <div class="col-md-3">
                                <select name="hours" class="form-select">
                                    <option value="1">1 hr</option>
                                    <option value="1.5">1.5 hrs</option>
                                    <option value="2">2 hrs</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-success w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#itemModal">
                                    + Add Item
                                </button>
                            </div>
                        </div>

                        <!-- Item List Header -->
                        <div class="mt-3 table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="itemList"></tbody>
                            </table>
                        </div>
                        <!-- Date & Total -->
                        <div class="p-3 bg-light rounded-3 shadow-sm d-flex justify-content-between">
                            <div><strong>Date:</strong> 09/16/2025</div>
                            <div><strong>Total Amount:</strong> <span class="text-success">₹0</span></div>
                            </div>



                        <!-- Confirm -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold shadow-sm">
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
        <input type="text" id="itemName" class="form-control mb-3" placeholder="Item Name">
        <input type="number" id="itemQty" class="form-control mb-3" placeholder="Quantity">
        <input type="number" id="itemPrice" class="form-control mb-3" placeholder="Price">
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer bg-light rounded-bottom-4">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary fw-bold" onclick="addItem()" data-bs-dismiss="modal">Add</button>
      </div>
    </div>
  </div>
</div>


<script>
    let totalAmount = 0;

    function addItem() {
        const name = document.getElementById("itemName").value;
        const qty = parseFloat(document.getElementById("itemQty").value);
        const price = parseFloat(document.getElementById("itemPrice").value);

        if (name && qty && price) {
            const row = document.createElement("tr");
            const itemTotal = qty * price;
            row.innerHTML = `
                <td>${name}</td>
                <td>${qty}</td>
                <td>₹${price}</td>
                <td><strong>₹${itemTotal}</strong></td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this, ${itemTotal})">❌</button></td>
            `;
            document.getElementById("itemList").appendChild(row);

            totalAmount += itemTotal;
            document.getElementById("totalAmount").innerText = totalAmount;

            document.getElementById("itemName").value = "";
            document.getElementById("itemQty").value = "";
            document.getElementById("itemPrice").value = "";
        }
    }

    function removeItem(button, itemAmount) {
        button.closest('tr').remove();
        totalAmount -= itemAmount;
        document.getElementById("totalAmount").innerText = totalAmount;
    }
</script>
@endsection
