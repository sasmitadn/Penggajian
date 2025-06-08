<!-- resources/views/components/form-group.blade.php -->
@props(['name', 'label', 'type' => 'text', 'old' => '', 'disabled' => false])

@if ($disabled)
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <textarea type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}"
            placeholder="Enter {{ $label }}" rows="5" disabled>{{old($name, $old)}}</textarea>
        @error($name)
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
@else
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <textarea type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}"
            placeholder="Enter {{ $label }}" rows="5">{{old($name, $old)}}</textarea>
        @error($name)
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
@endif

<!-- resources/views/components/form-group.blade.php -->
