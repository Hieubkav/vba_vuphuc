@props([
    'post',
    'showTitle' => true,
    'class' => ''
])

@php
    $mediaItems = $post->media()->active()->orderBy('order')->get();
    $hasMedia = $mediaItems->count() > 0;
@endphp

@if($hasMedia)
    <div class="post-media-gallery {{ $class }}">
        @if($showTitle)
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">📁 Tài liệu & Media</h3>
                <p class="text-sm text-gray-600">{{ $mediaItems->count() }} tệp đính kèm</p>
            </div>
        @endif

        <div class="space-y-6">
            @foreach($mediaItems as $media)
                <div class="media-item">
                    @if($media->isVideo())
                        {{-- Video Player --}}
                        <x-video-player :media="$media" class="w-full" />

                    @elseif($media->isAudio())
                        {{-- Audio Player --}}
                        <x-audio-player :media="$media" class="w-full" />

                    @elseif($media->isDocument() || $media->isDownload())
                        {{-- Download Button --}}
                        <x-download-button :media="$media" variant="card" class="w-full" />

                    @elseif($media->isEmbed())
                        {{-- Embed Content --}}
                        <div class="embed-container">
                            @if($media->title)
                                <h4 class="font-semibold text-lg text-gray-900 mb-3">{{ $media->title }}</h4>
                            @endif
                            
                            @if($media->embed_code)
                                <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                                    {!! $media->embed_code !!}
                                </div>
                            @elseif($media->embed_url)
                                <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                                    @if($media->getYouTubeId())
                                        <iframe 
                                            src="https://www.youtube.com/embed/{{ $media->getYouTubeId() }}"
                                            title="{{ $media->title }}"
                                            class="absolute inset-0 w-full h-full"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    @elseif($media->getVimeoId())
                                        <iframe 
                                            src="https://player.vimeo.com/video/{{ $media->getVimeoId() }}"
                                            title="{{ $media->title }}"
                                            class="absolute inset-0 w-full h-full"
                                            frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    @else
                                        <div class="flex items-center justify-center h-full">
                                            <div class="text-center text-gray-500">
                                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                </svg>
                                                <p class="text-sm">Nội dung nhúng không khả dụng</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            @if($media->description)
                                <p class="text-sm text-gray-600 mt-3">{{ $media->description }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Media Statistics --}}
        @if($mediaItems->count() > 1)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    @php
                        $stats = [
                            'videos' => $mediaItems->where('media_type', 'video')->count(),
                            'audios' => $mediaItems->where('media_type', 'audio')->count(),
                            'documents' => $mediaItems->where('media_type', 'document')->count(),
                            'downloads' => $mediaItems->where('media_type', 'download')->count(),
                        ];
                        $totalSize = $mediaItems->sum('file_size');
                    @endphp

                    @if($stats['videos'] > 0)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['videos'] }}</div>
                            <div class="text-sm text-gray-600">Video{{ $stats['videos'] > 1 ? 's' : '' }}</div>
                        </div>
                    @endif

                    @if($stats['audios'] > 0)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $stats['audios'] }}</div>
                            <div class="text-sm text-gray-600">Audio{{ $stats['audios'] > 1 ? 's' : '' }}</div>
                        </div>
                    @endif

                    @if($stats['documents'] > 0)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['documents'] }}</div>
                            <div class="text-sm text-gray-600">Tài liệu</div>
                        </div>
                    @endif

                    @if($stats['downloads'] > 0)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $stats['downloads'] }}</div>
                            <div class="text-sm text-gray-600">Download{{ $stats['downloads'] > 1 ? 's' : '' }}</div>
                        </div>
                    @endif

                    @if($totalSize > 0)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-600">
                                @php
                                    $bytes = $totalSize;
                                    $units = ['B', 'KB', 'MB', 'GB'];
                                    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                                        $bytes /= 1024;
                                    }
                                    echo round($bytes, 1) . ' ' . $units[$i];
                                @endphp
                            </div>
                            <div class="text-sm text-gray-600">Tổng dung lượng</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endif
