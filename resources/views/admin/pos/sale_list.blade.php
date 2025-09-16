@extends('layouts.admin.pos_layout')
@section('title')
Visitor List - Neverland
@endsection
@section('content')
    <div class="row g-3">

        <div class="col-lg-12">
            <div class="left-panel-container card">
                <div class="shopping-bag-container p-4">
                    <div class="shopping-bag">
                        <h5 class="text-uppercase">Visitor List</h5>
                        <div class="table-responsive" >
                         <table class="table table-bordered table-striped align-middle">
                                <thead class="text-uppercase text-secondary small">
                                    <tr>
                                        <th rowspan="2" class="text-center">Name</th>
                                        <th rowspan="2" class="text-center">Count</th>
                                        <th rowspan="2" class="text-center">In Time</th>
                                        <th rowspan="2" class="text-center">Out Time</th>
                                        <th rowspan="2" class="text-center">Price</th>
                                        <th colspan="3" class="text-center">Floaty</th>
                                        <th rowspan="2" class="text-center">Balance</th>
                                        <th rowspan="2" class="text-center">Payment</th>
                                        <th rowspan="2" class="text-center">Rent</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Numbers</th>
                                        <th class="text-center">Advance</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <livewire:visitor-live-table />
                            </table>
                            </div>
                    </div>
                </div>
                {{-- <div class="card-footer bg-light">
                        Please confirm that the balance of <strong>£95.00</strong> should be settled on account <strong>1413412343</strong>.
                    </div> --}}

            </div>
        </div>


    </div>

    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Larger modal -->
    <div class="modal-content shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">
          <i class="bi bi-box-seam me-2"></i> Rent Items
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
              <tr>
                <th scope="col">Item</th>
                <th scope="col">Quantity</th>
              </tr>
            </thead>
            <tbody id="rentItems">
              <!-- Items will be injected here via AJAX -->
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
@section('js')
<script>
function getRentItems(id){
event.preventDefault();
        $.ajax({
                url: "{{route('admin.rent_items_by_main_sale')}}",
                type: "POST",
                data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                    },
                    dataType: 'json',
                    success: function(res)
                        {
                        let html = "";
                            if (res.length > 0) {
                                res.forEach(item => {
                                    html += `<tr>
                                        <td>${item.name}</td>
                                        <td>${item.quantity}</td>
                                    </tr>`;
                                });
                            }
                            $("#rentItems").html(html);
                        },
                        error: function(e)
                        {
                        //    loader_off();
                        }
                });
            }
</script>
@endsection
