@extends('layouts.admin.admin_layout')
@section('title')
Change Password || Neverland Aquatics
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="fw-semibold">Change Password</h4>
    </div>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
<div class="card border-0 shadow-sm p-4">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                            @error('new_password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                            @error('new_password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                        <a href="{{ url()->previous() }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>

@endsection



