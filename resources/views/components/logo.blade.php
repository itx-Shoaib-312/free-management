@props(['size' => null])
@php
    switch ($size) {
        case 'lg':
            $fontSize = "48px";
            break;
        case 'xl':
            $fontSize = "96px";
            break;

        default:
            $fontSize = "36px";
            break;
    }
@endphp
<div>
    <h1 style="font-size: {{ $fontSize }};">Logo</h1>
</div>
