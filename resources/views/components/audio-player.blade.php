@props([
    'media',
    'autoplay' => false,
    'controls' => true,
    'loop' => false,
    'class' => ''
])

<div class="audio-player {{ $class }}">
    @if($media->file_path)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            {{-- Audio Player --}}
            <div class="flex items-center space-x-4">
                {{-- Thumbnail/Icon --}}
                <div class="flex-shrink-0">
                    @if($media->getThumbnailUrl())
                        <img src="{{ $media->getThumbnailUrl() }}" 
                             alt="{{ $media->title }}" 
                             class="w-16 h-16 rounded-lg object-cover">
                    @else
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Audio Info & Controls --}}
                <div class="flex-1 min-w-0">
                    @if($media->title)
                        <h3 class="font-semibold text-gray-900 truncate">{{ $media->title }}</h3>
                    @endif
                    
                    @if($media->description)
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $media->description }}</p>
                    @endif

                    {{-- Audio Element --}}
                    <div class="mt-3">
                        <audio 
                            class="w-full"
                            {{ $controls ? 'controls' : '' }}
                            {{ $autoplay ? 'autoplay' : '' }}
                            {{ $loop ? 'loop' : '' }}
                            preload="metadata">
                            <source src="{{ $media->getFileUrl() }}" type="{{ $media->mime_type }}">
                            Trình duyệt của bạn không hỗ trợ audio HTML5.
                        </audio>
                    </div>
                </div>
            </div>

            {{-- Audio Metadata --}}
            @if($media->duration || $media->file_size)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <div class="flex items-center space-x-4">
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

                        {{-- Download Button --}}
                        @if($media->getFileUrl())
                            <a href="{{ $media->getFileUrl() }}" 
                               download="{{ $media->file_name }}"
                               class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Tải về
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @else
        {{-- Fallback --}}
        <div class="bg-gray-100 rounded-lg p-6 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
            </svg>
            <p class="text-sm text-gray-500">Audio không khả dụng</p>
        </div>
    @endif
</div>
