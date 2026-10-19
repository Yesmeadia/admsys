@props(['name', 'class' => ''])

@php
    $cleanName = trim($name);
    // Strip fa- or fas- or far- or bi- prefixes if passed loosely
    if (str_starts_with($cleanName, 'fa-')) {
        $cleanName = substr($cleanName, 3);
    }
    if (!str_starts_with($cleanName, 'fas-') && !str_starts_with($cleanName, 'far-') && !str_starts_with($cleanName, 'fab-')) {
        $brandIcons = ['wpforms', 'facebook', 'facebook-f', 'google', 'twitter', 'youtube', 'whatsapp', 'linkedin', 'github', 'instagram'];
        if (in_array($cleanName, $brandIcons)) {
            $cleanName = 'fab-' . $cleanName;
        } else {
            $cleanName = 'fas-' . $cleanName;
        }
    }
@endphp

<x-dynamic-component :component="$cleanName" {{ $attributes->merge(['class' => 'bi-icon ' . $class]) }} />
