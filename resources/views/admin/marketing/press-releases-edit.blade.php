@extends('admin.layouts.app')

@section('title', 'Edit Press Release')
@section('description', 'Edit press release')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit Press Release
                    </h4>
                    <a href="{{ route('admin.marketing.press-releases') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.marketing.press-releases.update', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $pressRelease->title ?? '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Release Date <span class="text-danger">*</span></label>
                                <input type="date" name="release_date" class="form-control @error('release_date') is-invalid @enderror" value="{{ old('release_date', $pressRelease->release_date ?? date('Y-m-d')) }}" required>
                                @error('release_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $pressRelease->category ?? '') }}">
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Author</label>
                                <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author', $pressRelease->author ?? '') }}">
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Excerpt</label>
                                <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" rows="2">{{ old('excerpt', $pressRelease->excerpt ?? '') }}</textarea>
                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Content <span class="text-danger">*</span></label>
                                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="15" required>{{ old('content', $pressRelease->content ?? '') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Featured Image</label>
                                @if(isset($pressRelease) && $pressRelease->featured_image)
                                    <div class="mb-2">
                                        <img src="{{ $pressRelease->featured_image }}" alt="Featured" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                @endif
                                <input type="file" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" accept="image/*">
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft" {{ old('status', $pressRelease->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $pressRelease->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status', $pressRelease->status ?? '') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $pressRelease->meta_title ?? '') }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="2">{{ old('meta_description', $pressRelease->meta_description ?? '') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.marketing.press-releases') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Press Release</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('content');
</script>
@endpush






