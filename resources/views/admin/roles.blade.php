@extends('layouts.admin.admin_layout')
    @section('title')  Roles || Neverland Aquatics  @endsection

@section('content')
 <div class="d-flex justify-content-between align-items-center mb-2">
        <h2>Role Management</h2>
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">+ Add Role</a>
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
                <th>Role</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>
                    @foreach ($role->permissions as $perm)
                        <span class="badge bg-info text-dark">{{ $perm->name }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this role?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
