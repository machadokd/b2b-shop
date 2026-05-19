<div class="row g-3 mb-4">
    <div class="col-md-8">
        <label class="form-label">Nome <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $product?->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">SKU <span class="text-danger">*</span></label>
        <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
            value="{{ old('sku', $product?->sku) }}" required>
        @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Categoria <span class="text-danger">*</span></label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">Selecionar...</option>
            @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $product?->category_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Preço (€) <span class="text-danger">*</span></label>
        <input type="number" name="price" step="0.01" min="0.01"
            class="form-control @error('price') is-invalid @enderror"
            value="{{ old('price', $product?->price) }}" required>
        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Stock <span class="text-danger">*</span></label>
        <input type="number" name="stock" min="0"
            class="form-control @error('stock') is-invalid @enderror"
            value="{{ old('stock', $product?->stock ?? 0) }}" required>
        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Descrição</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $product?->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Imagem</label>
        @if ($product?->image)
        <div class="mb-2">
            <img src="{{ Storage::url($product->image) }}" alt="Imagem atual" style="max-height:80px;" class="rounded border">
        </div>
        @endif
        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input"
                {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Produto ativo</label>
        </div>
    </div>
</div>
