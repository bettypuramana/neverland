@extends('layouts.admin.admin_layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2>Sales</h2>
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#salesModal">+ Sale</a>
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
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>


    <!-- Add Expense/Income Modal -->
    <div class="modal fade" id="salesModal" tabindex="-1" aria-labelledby="salesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salesModalLabel">Add Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <form>
                        <div class="row mb-2">
                            <div class="col-3">
                                <label for="">Name</label>
                                <input type="text" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-3">
                                <label for="">Location</label>
                                <input type="text" class="form-control" placeholder="Location">
                            </div>
                            <div class="col-3">
                                <label for="">Phone</label>
                                <input type="text" class="form-control" placeholder="Phone">
                            </div>
                            <div class="col-3">
                                <label for="">Count</label>
                                <input type="number" class="form-control" name="members_count" id="membersCount" onchange="showEntries()" placeholder="Count">
                            </div>
                        </div>
                        {{-- <div class="row mb-2">
                            <div class="col-3">
                                <label for="">Phone</label>
                                <input type="text" class="form-control" placeholder="Phone">
                            </div>
                            <div class="col-3">
                                <label for="">Count</label>
                                <input type="text" class="form-control" placeholder="Count">
                            </div>
                        </div> --}}
                        <div class="row mb-2"><b>Timeing</b></div>
                        <div class="row mb-2">

                            <div class="col-4">
                                <label for="">In time</label>
                                <input type="time" class="form-control" placeholder="Count">
                            </div>
                            <div class="col-4">
                                <label for="">Hours</label>
                                <select name="" id="" class="form-control">
                                    <option value="1">1 hr</option>
                                    <option value="1">1:30 hr</option>
                                    <option value="1">2 hr</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="">End Time</label>
                                <input type="text" class="form-control" placeholder="End Time" readonly>
                            </div>



                        </div>

                        <div class="row mb-2"><b>Items</b></div>
                        <div class="row mb-2">
                            <div class="col-2">
                                <p>Cap</p>
                            </div>
                            <div class="col-2">
                                <label for="">sale</label>
                                <input type="text" class="form-control" placeholder="Count">
                            </div>
                            <div class="col-2">
                                <label for="">rent</label>
                                <input type="text" class="form-control" placeholder="Count">
                            </div>
                            <div class="col-2">
                                <p>Goggles</p>
                            </div>
                            <div class="col-2">
                                <label for="">sale</label>
                                <input type="text" class="form-control" placeholder="Count">
                            </div>
                            <div class="col-2">
                                <label for="">rent</label>
                                <input type="text" class="form-control" placeholder="Count">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-2">
                                <p>Tshirt</p>
                            </div>
                            <div class="col-2">
                                <label for="">sale</label>
                                <input type="text" class="form-control" placeholder="Count">
                            </div>
                            <div class="col-2">
                                <label for="">rent</label>
                                <input type="text" class="form-control" placeholder="Count">
                            </div>
                            <div class="col-2">
                                <p>Trousers</p>
                            </div>
                            <div class="col-2">
                                <label for="">sale</label>
                                <input type="text" class="form-control" placeholder="Count">
                            </div>
                            <div class="col-2">
                                <label for="">rent</label>
                                <input type="text" class="form-control" placeholder="Count">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-2">
                                <p>Floaty</p>
                            </div>
                            <div class="col-2">
                                <label for="">Number</label>
                                <input type="text" class="form-control" placeholder="Count">
                            </div>
                            <div class="col-2">
                                <label for="">Advance</label>
                                <input type="text" class="form-control" placeholder="Advance">
                            </div>

                        </div>

                        <div class="row mb-2"><b>Payments</b></div>
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


                        </div>
                        <button type="submit" class="btn btn-success">Create</button>
        <button  class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
