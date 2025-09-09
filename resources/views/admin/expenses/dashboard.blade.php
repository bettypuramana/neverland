{{-- resources/views/admin/expenses/dashboard.blade.php --}}
@extends('layouts.admin.expense_layout')

@section('content')


<div >
    <div class="board">
        <div class="board-inner">
            <div class="head">
                <div class="brand">
                    <div class="brand-badge"></div>
                    <div>EXPENSE TRACKER</div>
                </div>
                <div class="title">FINANCE EXPENSES DASHBOARD</div>
                <div class="head-tools">
                    <a class="btn-icon" href="#"><i class="fa-solid fa-rotate"></i></a>
                    <a class="btn-icon" href="#"><i class="fa-regular fa-display"></i></a>
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
                        @php $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; @endphp
                        @foreach ($months as $i => $m)
                            <li class="{{ $i===0 ? 'active':'' }}">{{ $m }}</li>
                        @endforeach
                    </ul>
                </aside>

                <!-- Content area -->
                <section class="content">
                    <!-- tiles -->
                    <div class="tiles">
                        <div class="tile"><i class="fa-solid fa-house"></i><div><div class="lbl">Living Expenses</div><div class="amt">$19,442</div></div></div>
                        <div class="tile"><i class="fa-solid fa-bag-shopping"></i><div><div class="lbl">Discretionary</div><div class="amt">$7,917</div></div></div>
                        <div class="tile"><i class="fa-solid fa-bus-simple"></i><div><div class="lbl">Transport</div><div class="amt">$4,245</div></div></div>
                        <div class="tile"><i class="fa-solid fa-utensils"></i><div><div class="lbl">Dining Out</div><div class="amt">$2,843</div></div></div>
                        <div class="tile"><i class="fa-solid fa-hands-holding-heart"></i><div><div class="lbl">Charity</div><div class="amt">$1,729</div></div></div>
                        <div class="tile"><i class="fa-solid fa-notes-medical"></i><div><div class="lbl">Medical</div><div class="amt">$620</div></div></div>
                    </div>

                    <!-- row 1: donut + category bar + subcategory bar -->
                    <div class="row1">
                        <div class="card">
                            <h6>Total Spend by Account</h6>
                            <canvas id="donutAccounts"></canvas>
                        </div>
                        <div class="card">
                            <h6>Expenses by Category</h6>
                            <canvas id="barCategory"></canvas>
                        </div>
                        <div class="card">
                            <h6>All Expenses by Subcategory</h6>
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
                        <button class="btn btn-danger btn-sm deleteCategoryBtn" data-id="{{ $category->id }}">Delete</button>
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
const donut = new Chart(document.getElementById('donutAccounts'), {
    type: 'doughnut',
    data: {
        labels: ['Checking', 'Credit', 'Cash'],
        datasets: [{
            data: [41, 42, 17],
            backgroundColor: ['#4da3ff','#7dd3fc','#47c37a'],
            borderWidth:0
        }]
    },
    options: {
        cutout: '70%',
        plugins: basePlugins
    },
    plugins: [{
        // draw center text
        id:'centerText',
        afterDraw(chart, args, opts){
            const {ctx, chartArea:{width,height}} = chart;
            ctx.save();
            ctx.fillStyle = '#eaf2fb';
            ctx.font = '700 22px Montserrat';
            ctx.textAlign = 'center';
            ctx.fillText('$36,795', chart.getDatasetMeta(0).data[0].x, chart.getDatasetMeta(0).data[0].y+6);
            ctx.restore();
        }
    }]
});

/* ---------- Expenses by Category (with positives/negatives like the shot) ---------- */
const catLabels = ['Salary','Living','Charity','Dining Out','Discretionary','Medical','Transport'];
const catValues = [48000,-19442,-495,-2843,-7917,-379,-4245];
const catColors = catValues.map(v => v >= 0 ? '#4da3ff' : '#e25d5d');

new Chart(document.getElementById('barCategory'), {
    type: 'bar',
    data: { labels: catLabels, datasets: [{ data: catValues, backgroundColor: catColors, borderRadius:6 }] },
    options: {
        scales: darkScales,
        plugins: basePlugins
    }
});

/* ---------- Subcategory horizontal bars ---------- */
new Chart(document.getElementById('barSubcat'), {
    type: 'bar',
    data: {
        labels: ['Taxi','Restaurant','Rent','Phone','MV Fuel','Gym','Groceries','Gifts','Gas/Electric','Furnishing','Entertainment','Donation','Doctor','Coffee','Others'],
        datasets: [{ data: [698,1528,10854,480,1747,360,7464,495,644,1155,3733,620,1179,315,1835],
            backgroundColor:'#f07b6a', borderRadius:6 }]
    },
    options: {
        indexAxis: 'y',
        scales: darkScales,
        plugins: basePlugins
    }
});

/* ---------- Tiny total income vs expenses (left bottom) ---------- */
new Chart(document.getElementById('hBarTotals'), {
    type: 'bar',
    data: {
        labels: ['Income','Expenses'],
        datasets: [{
            data: [48000, -35320],
            backgroundColor: ['#47c37a','#e25d5d'],
            borderRadius:6
        }]
    },
    options: {
        indexAxis:'y',
        scales: darkScales,
        plugins: basePlugins
    }
});

/* ---------- Monthly grouped bars ---------- */
new Chart(document.getElementById('barMonthly'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [
            { label:'Income', data:[4400,4200,4100,4300,4200,4000,3900,4100,4000,4200,4300,4400], backgroundColor:'#47c37a', borderRadius:6 },
            { label:'Expenses', data:[2900,3100,3080,3000,3200,3100,3300,3220,3190,3210,3000,3100], backgroundColor:'#e25d5d', borderRadius:6 }
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
        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value },
        body: data
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
                <td>
                    <button class="btn btn-danger btn-sm deleteExpenseBtn" data-id="${res.expense.id}">Delete</button>
                </td>
            `;

            // insert at top instead of bottom
            tbody.prepend(newRow);

            // clear input fields
            document.getElementById('expDate').value = '';
            document.getElementById('expAmount').value = '';
            document.getElementById('expRemarks').value = '';
        }
    })
    .catch(err => console.error(err));
});

// Delete Category
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

                    // optional: show success message
                    let msg = document.getElementById('successMessage');
                    msg.textContent = res.success;
                    msg.classList.remove('d-none');
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

@endsection
