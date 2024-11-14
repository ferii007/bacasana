@props([
    'active' => false
]) {{-- Hide attribute active on HTML with props --}}
<a 
    {{ $attributes }}
    class="{{ $active ? 'text-slate-400' : 'text-white hover:underline' }} text-sm font-semibold leading-6"
    aria-current="{{ $active ? 'page' : false }}"
>
    {{ $slot }}
</a>