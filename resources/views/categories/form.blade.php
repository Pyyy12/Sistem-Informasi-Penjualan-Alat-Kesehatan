<div class="mb-3">
    <label class="form-label">Nama Kategori *</label>
    <input name="name" class="form-control" required
           value="{{ old('name', $category->name) }}">
    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
    @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<button class="btn btn-primary">
    <i class="bi bi-check-lg me-1"></i> Simpan
</button>
<a href="{{ route('categories.index') }}" class="btn btn-light">Batal</a>