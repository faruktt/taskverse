@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Service Categories')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Marketplace Categories</h1>
        <p>Manage skill and service classifications for buyer search & seller gig creation</p>
    </div>
</div>

<div class="gig-form-layout">
    <!-- Categories List -->
    <div class="card">
        <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">category</span> Existing Categories ({{ $categories->count() }})</div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Category Name</th>
                            <th>Slug</th>
                            <th>Gigs Count</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:{{ $category->color }}22;border:1px solid {{ $category->color }}55;display:flex;align-items:center;justify-content:center;color:{{ $category->color }};">
                                        <span class="material-icons-round" style="font-size:20px;">{{ $category->icon }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight:600;">{{ $category->name }}</div>
                                </td>
                                <td>
                                    <span style="font-family:monospace;font-size:0.8rem;color:var(--text-muted);">{{ $category->slug }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $category->gigs_count }} Gigs</span>
                                </td>
                                <td style="text-align:right;">
                                    <form method="POST" action="{{ route('admin.category.delete', $category) }}" onsubmit="return confirm('Delete this category? Gigs attached will remain.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Category">
                                            <span class="material-icons-round" style="font-size:16px;">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center" style="padding:3rem;color:var(--text-muted);">
                                    No categories created yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Category Form -->
    <div class="card">
        <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">add_circle</span> Add Category</div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-error"><span class="material-icons-round">error</span>{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. AI & Machine Learning" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Google Material Icon Name</label>
                    <input type="text" name="icon" class="form-control" placeholder="e.g. psychology, code, brush" value="{{ old('icon', 'category') }}" required>
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.35rem;">Use any Material Icons Round name (e.g. code, palette, campaign)</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Color Hex</label>
                    <input type="text" name="color" class="form-control" placeholder="#6c63ff" value="{{ old('color', '#6c63ff') }}" required>
                </div>
                <button type="submit" class="btn btn-primary w-full" style="justify-content:center;margin-top:0.5rem;">
                    <span class="material-icons-round">save</span> Create Category
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
