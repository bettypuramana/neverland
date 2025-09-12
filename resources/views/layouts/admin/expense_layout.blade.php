<!DOCTYPE html>
<html>
    <head>
        <title>Expense Tracker || Neverland Aquatics</title>
        <link rel="icon" href="{{ asset('assets/favicon_no_bg.png') }}" type="image/png" />
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap JS (bundle includes Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


        <style>
            .is-invalid {
                border: 1px solid red !important;
            }

                .rail-head {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    width: 100%;
                }

                .rail-head i {
                    font-size: 20px;
                    cursor: pointer;
                    color: #fff;
                }

                /* Dropdown inside sidebar */
                .rail-dropdown {
                    position: relative;
                }

                .rail-dropdown .toggle-btn {
                    font-size: 20px;
                    cursor: pointer;
                    color: #fff;
                }

                /* hidden menu */
                .rail-menu {
                    display: none;
                    position: absolute;
                    left: 40px; /* push out to the right */
                    top: 0;
                    background: #24303a;
                    border-radius: 10px;
                    min-width: 160px;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
                    z-index: 1000;
                }

                /* menu links */
                .rail-menu a {
                    display: block;
                    padding: 10px 15px;
                    color: #fff;
                    text-decoration: none;
                    font-size: 14px;
                }

                .rail-menu a:hover {
                    background: #2f3f4d;
                }

                /* when active */
                .rail-dropdown.show .rail-menu {
                    display: block;
                }

            :root{
                --board:#2f3a45;
                --board-2:#2a3540;
                --ink:#e8edf3;
                --ink-dim:#b9c2cd;
                --accent:#7dd3fc;
                --green:#47c37a;
                --red:#e25d5d;
                --orange:#f0a356;
                --blue:#4da3ff;
                --frame:#e2e8f0;
            }
            body { background:#0f1820; }
            .wrap{
                max-width:1280px;
                margin:12px auto 28px;
                padding:12px;
            }
            .board{
                background:linear-gradient(180deg, var(--board) 0%, var(--board-2) 100%);
              
                box-shadow:0 8px 26px rgba(0,0,0,.35), inset 0 0 0 1px rgba(255,255,255,.05);
                color:var(--ink);
                overflow:hidden;
            }
            .board-inner{ padding:18px 18px 22px; }
            .head{
                display:grid;
                grid-template-columns: 180px 1fr 145px;
                align-items:center;
                gap:10px;
                margin-bottom:12px;
            }
            .brand{
                display:flex; align-items:center; gap:10px; font-weight:700; letter-spacing:.5px;
                    background-color: #24303a;
                    min-width: 215px;
                    min-height: 60px;
                    border-radius: 16px;
            }
            .brand-badge{
                width:34px;height:34px;border-radius:50%;
                background: radial-gradient( circle at 30% 30%, #7dd3fc 0 35%, #1f8ad6 36% 100% );
                box-shadow:0 0 0 3px rgba(255,255,255,.08);
            }
            .title {
                text-align: center;
                font-weight: 800;
                font-size: 24px;
                letter-spacing: 1.2px;
                color: #fff; /* White text for contrast */
                
                background-color: #24303a; /* Same as .brand */
                min-width: 400px; /* You can adjust */
                min-height: 60px;
                padding: 10px 20px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto; /* Center horizontally */
                box-shadow: 0 2px 6px rgba(0,0,0,0.2); /* Optional nice effect */
            }

            .head-tools {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 12px;
                background-color: #24303a;  /* Same dark background */
                min-height: 60px;
                padding: 0 15px;
                border-radius: 16px;
            }

            .head-tools .btn-icon {
                color: #fff; /* White icons for contrast */
                font-size: 18px;
                opacity: 0.9;
                transition: all 0.2s ease-in-out;
            }

            .head-tools .btn-icon:hover {
                opacity: 1;
                transform: scale(1.1);
            }


            .grid{
                display:grid;
                grid-template-columns: 214px 1fr;
                gap:14px;
            }
            /* left months rail */
            .rail{
                background:#24303a;
                border-radius:16px;
                padding:12px 10px;
                display:flex; flex-direction:column; gap:8px;
                min-height:680px;
            }
            .rail .rail-head{
                display:flex; justify-content:space-between; align-items:center; color:var(--ink-dim);
                padding:0 6px 6px; border-bottom:1px solid rgba(255,255,255,.06);
                margin-bottom:2px;
            }
            .months{ list-style:none; padding:0; margin:0; }
            .months li{
                padding:8px 10px; margin:3px 0; border-radius:10px; color:var(--ink-dim);
                cursor:pointer; transition:.15s;
            }
            .months li:hover{ background:rgba(255,255,255,.06); color:var(--ink); }
            .months li.active{ background:rgba(125,211,252,.18); color:#dff6ff; }

            /* right content */
            .content{ display:grid; gap:14px; }
            /* rows/areas to mimic the screenshot */
            .content{
                grid-template-rows: auto auto 1fr;
                grid-template-areas:
                    "tiles"
                    "row1"
                    "row2";
            }
            .tiles{ grid-area:tiles; display:grid; grid-template-columns: repeat(6, 1fr); gap:12px; }
            .tile{
                background:#2b3742; border-radius:16px; padding:10px 12px;
                display:flex; align-items:center; gap:10px; box-shadow: inset 0 0 0 1px rgba(255,255,255,.04);
            }
            .tile i{ font-size:18px; opacity:.9 }
            .tile .lbl{ font-size:12px; color:var(--ink-dim); line-height:1.1 }
            .tile .amt{ font-weight:700; font-size:18px; letter-spacing:.4px }
            /* charts grid to match layout */
            .row1{
                grid-area:row1;
                display:grid;
                grid-template-columns: 50% 50%;
                gap:14px;
            }
            .row2{
                grid-area:row2;
                display:grid;
                grid-template-columns: 32% 68%;
                gap:14px;
            }
            .card{
                background:#28333e; border-radius:16px; padding:12px 12px 8px;
                box-shadow: inset 0 0 0 1px rgba(255,255,255,.04);
            }
            .card h6{
                margin:0 0 6px; font-weight:600; font-size:13px; letter-spacing:.3px; color:#cfe2f1;
                text-align:left;
            }
            canvas{ width:100% !important; height:280px !important; }
            .sm canvas{ height:200px !important; } /* small bottom-left card */
            
        </style>
    </head>
    <body>
            @yield('content')
            <script>
$(document).ready(function() {
    // Initialize the DataTable
    var expenseTable = $('#expenseTableBody').closest('table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        columnDefs: [
            { orderable: false, targets: 5 } // Disable ordering on "Action" column
        ]
    });

});
</script>

    </body>

</html>