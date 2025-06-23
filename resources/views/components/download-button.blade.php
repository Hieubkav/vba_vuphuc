@props([
    'media',
    'variant' => 'default', // default, compact, card
    'class' => ''
])

@php
    $fileUrl = $media->getFileUrl();
    $fileName = $media->file_name ?? $media->title ?? 'download';
    $fileSize = $media->getFormattedFileSize();
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    
    // Icon based on file type
    $iconClass = match($media->mime_type) {
        'application/pdf' => 'text-red-500',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'text-blue-500',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'text-green-500',
        'application/zip', 'application/x-rar-compressed' => 'text-purple-500',
        default => 'text-gray-500'
    };
@endphp

@if($fileUrl)
    @if($variant === 'compact')
        {{-- Compact Button --}}
        <a href="{{ $fileUrl }}" 
           download="{{ $fileName }}"
           class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors {{ $class }}">
            <svg class="w-4 h-4 mr-2 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Tải về
            @if($fileSize)
                <span class="ml-1 text-xs text-gray-500">({{ $fileSize }})</span>
            @endif
        </a>

    @elseif($variant === 'card')
        {{-- Card Style --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 {{ $class }}">
            <div class="flex items-center space-x-3">
                {{-- File Icon --}}
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 1H7a2 2 0 00-2 2v16a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                {{-- File Info --}}
                <div class="flex-1 min-w-0">
                    <h3 class="font-medium text-gray-900 truncate">{{ $media->title }}</h3>
                    @if($media->description)
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $media->description }}</p>
                    @endif
                    <div class="flex items-center space-x-2 mt-2 text-xs text-gray-500">
                        @if($fileExtension)
                            <span class="uppercase font-medium">{{ $fileExtension }}</span>
                        @endif
                        @if($fileSize)
                            <span>•</span>
                            <span>{{ $fileSize }}</span>
                        @endif
                    </div>
                </div>

                {{-- Download Button --}}
                <div class="flex-shrink-0">
                    <a href="{{ $fileUrl }}" 
                       download="{{ $fileName }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3"></path>
                        </svg>
                        Tải về
                    </a>
                </div>
            </div>
        </div>

    @else
        {{-- Default Button --}}
        <div class="bg-gray-50 rounded-lg p-4 {{ $class }}">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    {{-- File Icon --}}
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 1H7a2 2 0 00-2 2v16a2 2 0 002 2z"></path>
                        </svg>
                    </div>

                    {{-- File Info --}}
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $media->title }}</h4>
                        @if($media->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $media->description }}</p>
                        @endif
                        <div class="flex items-center space-x-2 mt-1 text-xs text-gray-500">
                            @if($fileExtension)
                                <span class="uppercase font-medium">{{ $fileExtension }}</span>
                            @endif
                            @if($fileSize)
                                <span>•</span>
                                <span>{{ $fileSize }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Download Button --}}
                <a href="{{ $fileUrl }}" 
                   download="{{ $fileName }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Tải về
                </a>
            </div>
        </div>
    @endif
@else
    {{-- No File Available --}}
    <div class="bg-gray-100 rounded-lg p-4 text-center {{ $class }}">
        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <p class="text-sm text-gray-500">File không khả dụng</p>
    </div>
@endif
