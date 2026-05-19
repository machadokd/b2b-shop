<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Destinatário <span class="text-danger">*</span></label>
        <input type="text" name="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror"
            value="{{ old('recipient_name', $address?->recipient_name) }}" required>
        @error('recipient_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">NIF (opcional)</label>
        <input type="text" name="nif" class="form-control @error('nif') is-invalid @enderror"
            value="{{ old('nif', $address?->nif) }}">
        @error('nif')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Endereço <span class="text-danger">*</span></label>
        <input type="text" name="address_line" class="form-control @error('address_line') is-invalid @enderror"
            value="{{ old('address_line', $address?->address_line) }}" required>
        @error('address_line')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Código Postal <span class="text-danger">*</span></label>
        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror"
            value="{{ old('postal_code', $address?->postal_code) }}" required>
        @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Cidade <span class="text-danger">*</span></label>
        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
            value="{{ old('city', $address?->city) }}" required>
        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">País <span class="text-danger">*</span></label>
        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
            value="{{ old('country', $address?->country ?? 'Portugal') }}" required>
        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <div class="form-check">
            <input type="hidden" name="is_default" value="0">
            <input type="checkbox" name="is_default" value="1" id="is_default" class="form-check-input"
                {{ old('is_default', $address?->is_default) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_default">Morada predefinida</label>
        </div>
    </div>
</div>
