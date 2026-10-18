<div class="mb-3">
    <label class="form-label">Nama Pelanggan *</label>
    <input name="name" class="form-control" required
           value="{{ old('name', $customer->name) }}">
    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="row g-3">
    <div class="col-12 col-md-6">
        <label class="form-label">Telepon</label>
        <input name="phone" class="form-control"
               value="{{ old('phone', $customer->phone) }}">
        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email', $customer->email) }}">
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="address" class="form-control" rows="4">{{ old('address', $customer->address) }}</textarea>
        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary">Simpan Pelanggan</button>
    <a href="{{ route('customers.index') }}" class="btn btn-light">Batal</a>
</div>