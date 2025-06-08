@props(['name', 'label', 'options' => [], 'selected' => ''])

<div class="form-group">
    <label class="form-label">{{ $label }}</label>
    <div class="selectgroup w-100 mt-1">
        @foreach ($options as $value => $text)
            <label class="selectgroup-item">
                <input type="radio" name="{{ $name }}" value="{{ $value }}" class="selectgroup-input"
                    @checked(old($name, $selected) == $value) />
                <span class="selectgroup-button">{{ $text }}</span>
            </label>
        @endforeach
    </div>
    @error($name)
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
