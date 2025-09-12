@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #0077b6, #0096c7, #00b4d8);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        background: #ffffff;
        border-radius: 25px;
        box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.2);
        padding: 1.5rem;
        width: 684px;            
        height: 650px;
    }



    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .login-header img {
        width: 80px;
        margin-bottom: 15px;
    }

    .login-header h2 {
        color: #0077b6;
        font-weight: bold;
        font-size: 2rem;
    }

    .form-label {
        font-weight: 600;
    }

    .form-control {
        height: 60px;
        border-radius: 12px;
        padding: 15px 20px;
        font-size: 1.1rem;
    }

    .btn-primary {
        background-color: #0077b6;
        border: none;
        border-radius: 14px;
        padding: 18px;
        font-weight: 600;
        font-size: 1.2rem;
        width: 100%;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background-color: #023e8a;
    }


    .btn-link {
        color: #00b4d8;
        text-decoration: none;
    }

    .btn-link:hover {
        color: #0077b6;
        text-decoration: underline;
    }
</style>

<div class="login-card">
    <div class="login-header">
        <img src="{{ asset('assets/favicon_no_bg.png') }}" alt="Neverland Aquatics Logo">
        <h2>Welcome Back</h2>
        <p class="text-muted">Login to continue</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                   {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
        </div>

        <button type="submit" class="btn btn-primary">
            {{ __('Login') }}
        </button>

        <div class="mt-4 text-center">
            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </div>
    </form>
</div>
@endsection
