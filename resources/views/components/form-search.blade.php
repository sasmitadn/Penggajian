@props(['name', 'label', 'options' => [], 'selected' => '', 'placeholder' => 'Pilih ' . ($label ?? 'option')])

<div class="form-group">
    <label for="{{ $name }}" style="margin-bottom: 9px">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}" class="tom-select" {{ $attributes }}>
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

@once
    @push('js')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll('.tom-select').forEach(el => {
                    new TomSelect(el, {
                        create: false,
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
