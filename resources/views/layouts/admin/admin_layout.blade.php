<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard || Neverland Aquatics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/favicon_no_bg.png') }}" type="image/png" />
    <style>
      .dataTables_filter {
          float: right !important;
          text-align: right;
      }
      .dataTables_length {
          float: left !important;
      }

      .logout-btn {
          background-color: #f55d5d;
          color: white;
          font-weight: 500;
          border: none;
          border-radius: 10px;
          padding: 10px 15px;
          transition: background-color 0.3s ease;
          text-align: center;
          text-decoration: none;
        }

        .logout-btn:hover {
          background-color: #e04c4c;
          color: white;
          text-decoration: none;
        }

      body {
        background-color: #f7f8fc;
        font-family: 'Cairo', sans-serif;
      }

      .sidebar {
        width: 230px;
        background-color: #f9fafc;
        height: 100vh;
        padding-top: 24px;
        border-right: 1px solid #e5e7eb;
        position: fixed;
        top: 0;
        left: 0;
      }

      .sidebar .nav-link {
        padding: 10px 15px;
        color: #555;
        font-weight: 500;
        border-radius: 12px;
        display: flex;
        align-items: center;
      }

      .sidebar .nav-link:hover,
      .sidebar .nav-link.active {
        background-color: #e6efff;
        color: #f55d5d;
      }

      .fw-semibold{
        color: #f55d5d;
      }
      .btn-primary-subtle{
         color: #f55d5d;
      }
      .btn-primary{
         background-color: #f55d5d;
      }

      .sidebar .nav-link i {
        margin-right: 10px;
      }

      .topbar {
        height: 60px;
        background-color: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 30px;
        position: fixed;
        top: 0;
        left: 230px;
        right: 0;
        z-index: 1030;
      }

      .main-content {
        margin-left: 230px;
        padding: 90px 30px 30px;
      }

      .card-box {
        border-radius: 20px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 500;
        color: #111;
      }

      .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .dashboard-title {
        font-size: 24px;
        font-weight: 600;
      }

      .is-invalid {
        border-color: red;
      }

      .dropdown-toggle::after {
        margin-left: 0.5em;
      }

      .dropdown-menu .dropdown-item:hover {
        background-color: #f8f9fa;
      }

      .dropdown-menu .dropdown-item i {
        color: #6c757d;
      }

      .sidebar {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column justify-content-between">
      <div class="sidebar-inner">
        <img src="{{ asset('assets/neverlandaquaticslogo.jpg') }}" class="ps-3 mb-4" alt="" style="width: 217px;height: 132px;">
        <ul class="nav flex-column px-2">
          <li class="nav-item mb-2">
            <a href="{{ route('home') }}" class="nav-link">
              <i class="bi bi-grid-fill"></i> Dashboard
            </a>
          </li>
          <li class="nav-item mb-2">
            <a href="{{ route('roles.index') }}" class="nav-link">
              <i class="bi bi-bar-chart-fill"></i> Roles
            </a>
          </li>
          <li class="nav-item mb-2">
            <a href="{{ route('users.index') }}" class="nav-link">
              <i class="bi bi-bar-chart-fill"></i> User
            </a>
          </li>
        </ul>
      </div>
      <div class="p-3">
          <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"class="btn logout-btn w-100 d-flex align-items-center justify-content-center">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </a>
      </div>
    </div>

    <!-- Topbar -->
    <div class="topbar py-2 px-3 bg-white shadow-sm d-flex justify-content-end align-items-center">
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="user" width="32" height="32" class="rounded-circle me-2">
          <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownUser">
          <li>
            <a class="dropdown-item" href="{{ route('password.change') }}">
              <i class="bi bi-key me-2"></i> Change password
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
          </li>
        </ul>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <main class="col-md-12 ms-sm-auto col-lg-12 content">
        @yield('content')
      </main>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
</body>
</html>
<script>
    $(document).ready(function () {
      $('#Table').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
      });

    });
  </script>