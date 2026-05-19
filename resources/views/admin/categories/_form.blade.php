<div class="mb-3">
    <label class="form-label">Nome <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $category?->name) }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-4">
    <label class="form-label">Categoria Pai</label>
    <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
        <option value="">— Sem categoria pai —</option>
        @foreach ($parents as $parent)
        <option value="{{ $parent->id }}"
            {{ old('parent_id', $category?->parent_id) == $parent->id ? 'selected' : '' }}>
            {{ $parent->name }}
        </option>
        @endforeach
    </select>
    @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
