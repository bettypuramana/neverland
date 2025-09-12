@extends('layouts.admin.admin_layout')
    @section('title')  Add Roles || Neverland Aquatics  @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-2">

    <h2>Add Role</h2>
     </div>
     <div class="card border-0 shadow-sm p-4">
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Assign Permissions</label>
            <div class="row">
                @foreach($permissions as $perm)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->name }}">
                            <label class="form-check-label">{{ $perm->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
