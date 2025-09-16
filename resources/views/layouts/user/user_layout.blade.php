<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Section</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Modal Theme */
        #itemModal .modal-header {
        background-color: #007b91; /* your theme color */
        color: #ffffff;
        }

        #itemModal .btn-primary {
        background-color: #007b91; 
        border-color: #007b91;
        font-weight: bold;
        }

        #itemModal .btn-primary:hover {
        background-color: #005f6b; /* darker shade */
        border-color: #005f6b;
        }

        #itemModal .modal-content {
        border-radius: 1rem; /* smooth rounded corners */
        border: none;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

    </style>
</head>
<body class="bg-light text-dark">

    <div class="min-vh-100 d-flex flex-column">
        <!-- Header -->
        <header class="bg-white shadow-sm p-3">
            <!-- Add navbar / logo if needed -->
        </header>

        <!-- Main Content -->
        <main class="flex-fill p-3">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
