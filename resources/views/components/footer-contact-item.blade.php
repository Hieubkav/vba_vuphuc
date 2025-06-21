@props(['icon', 'value', 'type' => 'text'])

@if(!empty($value))
    <p class="flex {{ $type === 'address' ? 'items-start' : 'items-center' }}">
        <svg class="h-5 w-5 mr-3 {{ $type === 'address' ? 'mt-0.5' : '' }} text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            {!! $icon !!}
        </svg>
        <span>{{ $value }}</span>
    </p>
@endif
