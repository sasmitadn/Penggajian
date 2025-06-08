<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>
    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">
        <thead>
            <tr>
                @foreach ($labels as $item)
                    <th
                        style="background-color: #177dff; color: white; padding: 8px; border: 1px solid #dddddd; text-align: left;">
                        {{ $item }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $item)
                <tr>
                    @foreach ($item as $val)
                        <td style="padding: 8px; border: 1px solid #dddddd;">{{ $val }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
