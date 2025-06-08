<!-- resources/views/components/form-group.blade.php -->
@props(['name', 'label', 'type' => 'text', 'old' => '', 'disabled' => false])

@if ($disabled)
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <input type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}"
            value="{{ old($name, $old) }}" placeholder="Masukan {{ $label }}" disabled />
        @error($name)
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
@else
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <input type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}"
            value="{{ old($name, $old) }}" placeholder="Masukan {{ $label }}" />
        @error($name)
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
@endif

<!-- resources/views/components/form-group.blade.php -->
