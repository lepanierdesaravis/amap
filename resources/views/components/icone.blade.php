@props(['icon' => null, 'color' => null, 'size' => '2rem'])
{!! \App\Support\Icons::badge($icon, $color, $size, (string) $attributes->get('class')) !!}
