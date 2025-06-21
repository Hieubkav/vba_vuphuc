@props(['title'])
<div class="bg-white/95 backdrop-blur-sm border border-white/20 rounded-xl p-5">
    @if($title)<h3 class="text-base font-semibold text-gray-900 mb-3 font-montserrat">{{ $title }}</h3>@endif
    {{ $slot }}
</div>
