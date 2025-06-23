@props([
    'media',
    'autoplay' => false,
    'controls' => true,
    'muted' => false,
    'loop' => false,
    'class' => ''
])

@php
    $isYouTube = $media->getYouTubeId();
    $isVimeo = $media->getVimeoId();
    $isUpload = $media->file_path && !$isYouTube && !$isVimeo;
@endphp

<div class="video-player {{ $class }}">
    @if($isYouTube)
        {{-- YouTube Video --}}
        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
            <iframe 
                src="https://www.youtube.com/embed/{{ $isYouTube }}{{ $autoplay ? '?autoplay=1' : '' }}"
                title="{{ $media->title }}"
                class="absolute inset-0 w-full h-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
    @elseif($isVimeo)
        {{-- Vimeo Video --}}
        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
            <iframe 
                src="https://player.vimeo.com/video/{{ $isVimeo }}{{ $autoplay ? '?autoplay=1' : '' }}"
                title="{{ $media->title }}"
                class="absolute inset-0 w-full h-full"
                frameborder="0"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
    @elseif($isUpload)
        {{-- Uploaded Video File --}}
        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
            <video 
                class="w-full h-full object-cover"
                {{ $controls ? 'controls' : '' }}
                {{ $autoplay ? 'autoplay' : '' }}
                {{ $muted ? 'muted' : '' }}
                {{ $loop ? 'loop' : '' }}
                preload="metadata"
                poster="{{ $media->getThumbnailUrl() }}">
                <source src="{{ $media->getFileUrl() }}" type="{{ $media->mime_type }}">
                Trình duyệt của bạn không hỗ trợ video HTML5.
            </video>
        </div>
    @elseif($media->embed_code)
        {{-- Custom Embed Code --}}
        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
            {!! $media->embed_code !!}
        </div>
    @else
        {{-- Fallback --}}
        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
            <div class="text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm">Video không khả dụng</p>
            </div>
        </div>
    @endif

    {{-- Video Info --}}
    @if($media->title || $media->description || $media->duration)
        <div class="mt-4 space-y-2">
            @if($media->title)
                <h3 class="font-semibold text-lg text-gray-900">{{ $media->title }}</h3>
            @endif
            
            @if($media->description)
                <p class="text-gray-600 text-sm">{{ $media->description }}</p>
            @endif
            
            <div class="flex items-center space-x-4 text-sm text-gray-500">
                @if($media->duration)
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $media->getFormattedDuration() }}
                    </span>
                @endif
                
                @if($media->file_size)
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 1H7a2 2 0 00-2 2v16a2 2 0 002 2z"></path>
                        </svg>
                        {{ $media->getFormattedFileSize() }}
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>
