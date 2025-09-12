@extends('layouts.admin.admin_layout')
    @section('title')  Roles edit || Neverland Aquatics  @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-2">

    <h2>Edit Role - {{ $role->name }}</h2>
     </div>
     <div class="card border-0 shadow-sm p-4">
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" value="{{ $role->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Assign Permissions</label>
            <div class="row">
                @foreach($permissions as $perm)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $perm->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
