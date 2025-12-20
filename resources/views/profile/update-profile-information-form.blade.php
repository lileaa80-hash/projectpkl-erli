<p class="text-muted small">Perbarui informasi profil dan alamat email kamu.</p>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    {{-- Nama --}}
    <div class="mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text"
               name="name"
               id="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $user->name) }}"
               required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email"
               name="email"
               id="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $user->email) }}"
               required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Phone --}}
    <div class="mb-3">
        <label for="phone" class="form-label">Nomor Telepon</label>
        <input type="tel"
               name="phone"
               id="phone"
               class="form-control @error('phone') is-invalid @enderror"
               value="{{ old('phone', $user->phone) }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Address --}}
    <div class="mb-3">
        <label for="address" class="form-label">Alamat Lengkap</label>
        <textarea name="address"
                  id="address"
                  rows="3"
                  class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Simpan Informasi</button>
</form>