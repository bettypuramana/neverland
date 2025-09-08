@extends('layouts.admin.expense_layout')

@section('content')
<div class="board">
    <div class="board-inner">
        <h2 class="title">Manage Categories</h2>

        {{-- Add Category Form --}}
        <form action="{{ route('categories.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="flex gap-2">
                <input type="text" name="name" class="form-control" placeholder="Enter category name" required>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Categories List --}}
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th style="width:100px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
