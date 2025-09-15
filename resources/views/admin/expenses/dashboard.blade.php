@extends('layouts.admin.expense_layout')

@section('content')

    <div class="board">
        <div class="board-inner">
            <div class="head">
                <div class="brand">
                    <div class="brand-badge"></div>
                    <div>EXPENSE TRACKER</div>
                </div>
                <div class="title">FINANCE EXPENSES DASHBOARD</div>
                <div class="head-tools">
                    {{-- Financial Year Dropdown --}}
                    <form method="GET" action="{{ route('expenses.index') }}">
                        <select name="financial_year_id" onchange="this.form.submit()" class="form-select">
                            @foreach($financialYears as $fy)
                                <option value="{{ $fy->id }}" {{ $fy->id == $selectedFY ? 'selected' : '' }}>
                                    {{ $fy->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <div class="grid">
                <!-- Months rail -->
                <aside class="rail">
                    <div class="rail-head">
                        <i class="fa-solid "></i>
                        <div class="rail-dropdown">
                            <i class="fa-solid fa-plus toggle-btn"></i>
                            <div class="rail-menu">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#categoryModal">Add Category</a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#expenseModal">Add Expense/Income</a>
                            </div>
                        </div>
                    </div>
                    <ul class="months" id="monthsList">
                        <li class="list-group-item {{ !$selectedMonth ? 'active' : '' }}">
                            <a  style="color: #b9c2cd;" href="{{ route('expenses.index', ['financial_year_id' => $selectedFY]) }}" class="d-block text-decoration-none">
                                All
                            </a>
                        </li>

                        @forelse($months as $month)
                            <li class="list-group-item {{ $selectedMonth == $month->month_key ? 'active' : '' }}">
                                <a  style="color: #b9c2cd;" href="{{ route('expenses.index', ['financial_year_id' => $selectedFY, 'month' => $month->month_key]) }}" class="d-block text-decoration-none">
                                    {{ $month->month_label }}
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No expenses found for this year</li>
                        @endforelse
                    </ul>
                </aside>

                <!-- Content area -->
                <section class="content">
                    <!-- tiles -->
                    <div class="tiles">
    <!-- Total Income -->
                        <div class="tile">
                            <i class="fa-solid fa-wallet"></i>
                            <div>
                                <div class="lbl">Total Income</div>
                                <div class="amt">₹{{ number_format($totalIncome, 2) }}</div>
                            </div>
                        </div>

                        <!-- Category-wise Expenses -->
                        @foreach($categoryExpenses as $catId => $amount)
                            @php
                                $cat = $categories->firstWhere('id', $catId);
                            @endphp
                            <div class="tile category-tile"
                                data-id="{{ $cat->id }}"
                                data-name="{{ $cat->name }}">
                                <i class="fa-solid fa-tag"></i>
                                <div>
                                    <div class="lbl">{{ $cat ? $cat->name : 'Unknown' }}</div>
                                    <div class="amt">₹{{ number_format($amount, 2) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                    <!-- row 1: donut + category bar + subcategory bar -->
                    <div class="row1">
                        
                        <div class="card">
                            <h6>Expenses by Category</h6>
                            <canvas id="barCategory"></canvas>
                        </div>
                        <div class="card">
                            <h6>All Expenses by Category</h6>
                            <canvas id="barSubcat"></canvas>
                        </div>
                    </div>

                    <!-- row 2: small income vs expense + big monthly chart -->
                    <div class="row2">
                        <div class="card sm">
                            <h6>Total Income Vs Expenses</h6>
                            <canvas id="hBarTotals"></canvas>
                        </div>
                        <div class="card">
                            <h6>Monthly Net Income Vs Expenses</h6>
                            <canvas id="barMonthly"></canvas>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
<!-- Add Expense/Income Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expenseModalLabel">Add Expense / Income</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- Table -->
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Type</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Category</th>
              <th>Remarks</th>
              <th style="width:100px;">Action</th>
            </tr>
          </thead>
          <tbody id="expenseTableBody">

            <!-- First row (input row with save button) -->
            <tr id="newExpenseRow">
                <td>
                <select class="form-control" id="expType" required>
                    <option value="expense">Expense</option>
                    <option value="income">Income</option>
                </select>
                </td>
              <td><input type="date" class="form-control" id="expDate" required></td>
              
              <td><input type="number" class="form-control" id="expAmount" required></td>
              
              <td>
                <select class="form-control" id="expCategory" required>
                  @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                  @endforeach
                </select>
              </td>
              <td><input type="text" class="form-control" id="expRemarks"></td>
              <td><button class="btn btn-success btn-sm" id="saveExpenseBtn">Save</button></td>
            </tr>

            <!-- Existing expenses will be listed here -->
            @foreach($expenses as $exp)
            <tr id="expenseRow{{ $exp->id }}">
              <td>{{ $exp->type }}</td>
              <td>{{ $exp->date }}</td>
              <td>{{ $exp->amount }}</td>
              <td>{{ $exp->category->name }}</td>
              <td>{{ $exp->remarks }}</td>
              <td>
                <button class="btn btn-danger btn-sm deleteExpenseBtn" data-id="{{ $exp->id }}">Delete</button>
              </td>
            </tr>
            @endforeach

          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>
<!-- Category Daily Totals Modal -->
<div class="modal fade" id="categoryListModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Daily Totals - <span id="modalCategoryName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Total (₹)</th>
            </tr>
          </thead>
          <tbody id="categoryDataBody">
            <tr><td colspan="2" class="text-center">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="categoryModalLabel">Manage Categories</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Add Category Form -->
        <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="d-flex gap-2 mb-3">
                <input type="text" name="name" class="form-control" placeholder="Enter category name" required>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>

        <!-- Success Message -->
        <div id="successMessage" class="alert alert-success d-none"></div>

        <!-- Categories List -->
        <table class="table table-striped" id="categoryTable">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th style="width:100px;">Action</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody">
                @foreach($categories as $category)
                <tr id="categoryRow{{ $category->id }}">
                    <td>{{ $category->name }}</td>
                    <td>
                        @if(!in_array($category->name, ['Pool Collection', 'Item Purchase']))
                            <button class="btn btn-danger btn-sm deleteCategoryBtn" data-id="{{ $category->id }}">Delete</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
/* ---------- Shared styling for dark theme ---------- */
const darkScales = {
    x: { grid: { color: 'rgba(255,255,255,.06)' }, ticks: { color: '#cfe2f1', font: { size: 11 } } },
    y: { grid: { color: 'rgba(255,255,255,.06)' }, ticks: { color: '#cfe2f1', font: { size: 11 } } }
};
const basePlugins = {
    legend: { display:false },
    tooltip: {
        backgroundColor:'#1f2933', titleColor:'#eaf2fb', bodyColor:'#eaf2fb',
        borderWidth:1, borderColor:'rgba(255,255,255,.15)'
    }
};

/* ---------- Donut with center value ---------- */


/* ---------- Expenses by Category (with positives/negatives like the shot) ---------- */
const catLabels  = @json($categoryAllLabels);
const catIncome  = @json($categoryIncomeData);
const catExpense = @json($categoryExpenseData);

new Chart(document.getElementById('barCategory'), {
    type: 'bar',
    data: {
        labels: catLabels,
        datasets: [
            {
                label: 'Income',
                data: catIncome,
                backgroundColor: '#4da3ff', // blue
                borderRadius: 6
            },
            {
                label: 'Expense',
                data: catExpense.map(v => -v), // negative for downward bar
                backgroundColor: '#e25d5d', // red
                borderRadius: 6
            }
        ]
    },
    options: {
        responsive: true,
        scales: darkScales,
        plugins: {
            ...basePlugins,
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let value = context.raw;
                        return (value < 0 ? '-' : '') + '₹' + Math.abs(value);
                    }
                }
            }
        }
    }
});

/* ---------- Subcategory horizontal bars ---------- */
new Chart(document.getElementById('barSubcat'), {
    type: 'bar',
    data: {
        labels: @json($categoryChartLabels),
        datasets: [{
            data: @json($categoryChartData),
            backgroundColor:'#f07b6a',
            borderRadius:6
        }]
    },
    options: {
        indexAxis: 'y',
        scales: darkScales,
        plugins: basePlugins
    }
});

/* ---------- Tiny total income vs expenses (left bottom) ---------- */
    const income = {{ $totalIncome ?? 0 }};
    const expenses = {{ $totalExpenses ?? 0 }};

    new Chart(document.getElementById('hBarTotals'), {
        type: 'bar',
        data: {
            labels: ['Income','Expenses'],
            datasets: [{
                data: [income, -expenses],
                backgroundColor: ['#47c37a','#e25d5d'],
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            scales: darkScales,
            plugins: basePlugins
        }
    });

/* ---------- Monthly grouped bars ---------- */
    const monthlyIncome = @json($incomeData);
    const monthlyExpenses = @json($expenseData);

    new Chart(document.getElementById('barMonthly'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [
                { label:'Income', data: monthlyIncome, backgroundColor:'#47c37a', borderRadius:6 },
                { label:'Expenses', data: monthlyExpenses, backgroundColor:'#e25d5d', borderRadius:6 }
            ]
        },
        options: {
            scales: darkScales,
            plugins: {
                ...basePlugins,
                legend: { labels: { color:'#d7e4ef' } }
            }
        }
    });

/* ---------- Month pill active state (UI only) ---------- */
document.querySelectorAll('#monthsList li').forEach(li=>{
    li.addEventListener('click', ()=>{
        document.querySelectorAll('#monthsList li').forEach(x=>x.classList.remove('active'));
        li.classList.add('active');
    });
});

</script>
<script>
document.querySelector('.rail-dropdown .toggle-btn').addEventListener('click', function () {
    this.parentElement.classList.toggle('show');
});
</script>
<script>
// Add Category
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let form = this;
    let data = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
            'Accept': 'application/json'   // ✅ important for validation errors
        },
        body: data
    })

    .then(async res => {
        if (!res.ok) {
            // handle validation errors
            let err = await res.json();
            let msg = document.getElementById('successMessage');
            msg.textContent = err.errors?.name ? err.errors.name[0] : 'Something went wrong!';
            msg.classList.remove('d-none');
            msg.classList.replace('alert-success','alert-danger');
            throw new Error(msg.textContent);
        }
        return res.json();
    })
    .then(res => {
        if(res.success){
            // ✅ Show success message
            let msg = document.getElementById('successMessage');
            msg.textContent = res.success;
            msg.classList.remove('d-none');
            msg.classList.replace('alert-danger','alert-success');

            // ✅ Add new row to category table
            let tbody = document.getElementById('categoryTableBody');
            let newRow = document.createElement('tr');
            newRow.id = 'categoryRow' + res.category.id;
            newRow.innerHTML = `
                <td>${res.category.name}</td>
                <td>
                    <button class="btn btn-danger btn-sm deleteCategoryBtn" data-id="${res.category.id}">Delete</button>
                </td>
            `;
            tbody.prepend(newRow);

            // ✅ Clear input
            form.reset();
        }
    })
    .catch(err => console.error(err));
});



document.getElementById('categoryTableBody').addEventListener('click', function(e){
    if(e.target.classList.contains('deleteCategoryBtn')){
        if(confirm('Delete this category?')){
            let id = e.target.dataset.id;
            fetch('/admin/categories/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value }
            })
            .then(res => res.json())
            .then(res => {
                if(res.success){
                    // remove row
                    let row = document.getElementById('categoryRow' + res.id);
                    row.remove();

                    // show success message
                    let msg = document.getElementById('successMessage');
                    msg.textContent = res.success;
                    msg.classList.remove('d-none');
                    msg.classList.replace('alert-danger','alert-success');
                }
                else if(res.error){
                    // show error message
                    let msg = document.getElementById('successMessage');
                    msg.textContent = res.error;
                    msg.classList.remove('d-none');
                    msg.classList.replace('alert-success','alert-danger');
                }
            })
            .catch(err => console.error(err));
        }
    }
});

</script>

<script>
    // Save new expense
document.getElementById('saveExpenseBtn').addEventListener('click', function() {
    // Clear old errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.error-msg').forEach(el => el.remove());

    let type = document.getElementById('expType');
    let date = document.getElementById('expDate');
    let amount = document.getElementById('expAmount');
    let category = document.getElementById('expCategory');
    let remarks = document.getElementById('expRemarks');
    let token = document.querySelector('input[name=_token]').value;

    let hasError = false;

    function showError(input, message) {
        input.classList.add('is-invalid');
        let error = document.createElement('div');
        error.className = 'text-danger error-msg';
        error.innerText = message;
        input.parentNode.appendChild(error);
    }

    if (!type.value) {
        showError(type, "Type is required");
        hasError = true;
    }
    if (!date.value) {
        showError(date, "Date is required");
        hasError = true;
    }
    if (!amount.value || isNaN(amount.value)) {
        showError(amount, "Enter a valid amount");
        hasError = true;
    }
    if (!category.value) {
        showError(category, "Category is required");
        hasError = true;
    }

    // remarks not mandatory → default to "-"
    let remarksVal = remarks.value.trim() || "-";

    if (hasError) return; // stop here if invalid

    // your existing save functionality
    let data = {
        type: type.value,
        date: date.value,
        amount: amount.value,
        category_id: category.value,
        remarks: remarksVal,
        _token: token
    };

    fetch('/admin/expenses', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': data._token },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if(res.success){
            let tbody = document.getElementById('expenseTableBody');
            let newRow = document.createElement('tr');
            newRow.id = 'expenseRow' + res.expense.id;
            newRow.innerHTML = `
                <td>${res.expense.type}</td>
                <td>${res.expense.date}</td>
                <td>${res.expense.amount}</td>
                <td>${res.expense.category}</td>
                <td>${res.expense.remarks}</td>
                <td><button class="btn btn-danger btn-sm deleteExpenseBtn" data-id="${res.expense.id}">Delete</button></td>
            `;

            // insert row just after the "newExpenseRow"
            let firstRow = document.getElementById('newExpenseRow').nextSibling;
            tbody.insertBefore(newRow, firstRow);

            // clear input fields
            document.getElementById('expType').selectedIndex = 0;  
            document.getElementById('expDate').value = '';
            document.getElementById('expAmount').value = '';
            document.getElementById('expCategory').selectedIndex = 0;
            document.getElementById('expRemarks').value = '';
        }
    })

    .catch(err => console.error(err));
});


// Delete expense
document.getElementById('expenseTableBody').addEventListener('click', function(e){
    if(e.target.classList.contains('deleteExpenseBtn')){
        if(confirm('Delete this entry?')){
            let id = e.target.dataset.id;
            fetch('/admin/expenses/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value }
            })
            .then(res => res.json())
            .then(res => {
                if(res.success){
                    let row = document.getElementById('expenseRow' + res.id);
                    row.remove();
                }
            })
            .catch(err => console.error(err));
        }
    }
});

    </script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".category-tile").forEach(tile => {
        tile.addEventListener("click", function () {
            let categoryId = this.dataset.id;
            let categoryName = this.dataset.name;

            document.getElementById("modalCategoryName").innerText = categoryName;

            let tbody = document.getElementById("categoryDataBody");
            tbody.innerHTML = "<tr><td colspan='2' class='text-center'>Loading...</td></tr>";

            // Call backend
            fetch(`/expenses/category/${categoryId}/daily?financial_year_id={{ $selectedFY }}&month={{ $selectedMonth }}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        tbody.innerHTML = "<tr><td colspan='2' class='text-center'>No data found</td></tr>";
                        return;
                    }

                    tbody.innerHTML = "";
                    data.forEach(row => {
                        let formattedDate = new Date(row.day).toLocaleDateString('en-US', {
                            month: 'short',   // "Sep"
                            day: 'numeric',   // "1"
                            year: 'numeric'   // "2025"
                        });

                        tbody.innerHTML += `
                            <tr>
                                <td>${formattedDate}</td>
                                <td>₹${parseFloat(row.total).toFixed(2)}</td>
                            </tr>`;
                    });
                });

            // Show modal
            new bootstrap.Modal(document.getElementById("categoryListModal")).show();
        });
    });
});
</script>

@endsection
