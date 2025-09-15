<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/favicon_no_bg.png') }}" type="image/png" />
    @livewireStyles
    <style>
        /* A few custom styles to override Bootstrap defaults and match the specific design */
        body, .pos-wrapper {
            height: 100vh;
            overflow: hidden;
            background-color: #f0f2f5;
        }
        .pos-wrapper {
            display: flex;
            flex-direction: column;
        }
        main {
            flex-grow: 1;
            overflow: hidden;
        }
        .left-panel-container, .right-panel, .shopping-bag-container {
            display: flex;
    flex-direction: column;
    /* height: 100%; */
    /* flex: 2; */
    max-height: 80vh;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
        }
        .shopping-bag {
            flex-grow: 1;
            /* overflow-y: auto; */
        }
        .numpad .btn {
            font-size: 1.5rem;
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }
    </style>
</head>
<body>

<div class="pos-wrapper">

    <header class="navbar navbar-expand navbar-dark bg-dark flex-shrink-0">
        <div class="container-fluid">
            <div class="navbar-nav">
                <a class="nav-link text-white" href="{{ route('home') }}"><i class="bi bi-arrow-left me-2"></i>Dashboard</a>
            </div>
            {{-- <span class="navbar-text small">
                10-Sep-2025 13:16:33
            </span> --}}
            <livewire:current-time />
        </div>
    </header>
<main class="container-fluid py-3">
    @yield('content')
     <div class="row">
        <div class="card-footer p-2">
                        <div class="d-grid gap-1 grid-cols-4">
                            <style>
                                .grid-cols-4 {
                                    grid-template-columns: repeat(4, 1fr);
                                }
                            </style>
                            <a href="{{route('admin.sales.new')}}" class="btn btn-dark rounded-0 text-uppercase py-3">Sale</a>
                            <a href="{{route('admin.sales.index')}}" class="btn btn-dark rounded-0 text-uppercase py-3">Sale list</a>
                        </div>
                    </div>
                    </div>
    </main>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
@yield('js')
</body>
</html>
