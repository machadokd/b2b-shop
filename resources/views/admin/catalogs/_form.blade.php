<div class="mb-3">
    <label class="form-label">Nome <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $catalog?->name) }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Descrição</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $catalog?->description) }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3 form-check">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" id="is_active"
        class="form-check-input" {{ old('is_active', $catalog?->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Ativo</label>
</div>

<div class="mb-4">
    <label class="form-label">Produtos associados</label>
    <div class="border rounded p-3" style="max-height:300px;overflow-y:auto;">
        @foreach ($products as $product)
        <div class="form-check">
            <input type="checkbox" name="products[]" value="{{ $product->id }}"
                id="product_{{ $product->id }}" class="form-check-input"
                {{ in_array($product->id, old('products', $catalog?->products->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
            <label class="form-check-label" for="product_{{ $product->id }}">
                {{ $product->name }} <small class="text-muted">({{ $product->sku }})</small>
            </label>
        </div>
        @endforeach
    </div>
</div>
