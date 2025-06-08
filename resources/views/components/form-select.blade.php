@props(['name', 'label', 'options' => [], 'selected' => '', 'placeholder' => '' . ($label ?? 'option')])

<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}" class="form-select form-control" {{ $attributes }}>
        @if ($selected == '')
            <option value="" disabled selected>{{ $placeholder }}</option>
        @endif
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected(old($name, $selected) == $value)>
                {{ $text }}
            </option>
        @endforeach
    </select>
    @error($name)
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
