@props(['post'])

@php $hasBuilderContent = !empty($post->content_builder) && is_array($post->content_builder); @endphp

@if($hasBuilderContent)
    <div class="builder-content space-y-8">
        @foreach($post->content_builder as $block)
            @if(isset($block['type']) && isset($block['data']))
                @switch($block['type'])
                    @case('paragraph')
                        <div class="prose prose-lg max-w-none">{!! $block['data']['content'] ?? '' !!}</div>
                        @break

                    @case('heading')
                        @php
                            $level = $block['data']['level'] ?? 'h2';
                            $text = $block['data']['text'] ?? '';
                        @endphp
                        @if($level === 'h2')
                            <h2 class="text-3xl font-bold text-gray-900 mt-12 mb-6">{{ $text }}</h2>
                        @elseif($level === 'h3')
                            <h3 class="text-2xl font-semibold text-gray-800 mt-10 mb-4">{{ $text }}</h3>
                        @elseif($level === 'h4')
                            <h4 class="text-xl font-medium text-gray-700 mt-8 mb-3">{{ $text }}</h4>
                        @endif
                        @break

                    @case('image')
                        <div class="my-8">
                            @if(!empty($block['data']['image']))
                                @php
                                    $alignment = $block['data']['alignment'] ?? 'center';
                                    $alignClass = match($alignment) {
                                        'left' => 'text-left',
                                        'right' => 'text-right',
                                        default => 'text-center'
                                    };
                                @endphp
                                <div class="{{ $alignClass }}">
                                    <img src="{{ asset('storage/' . $block['data']['image']) }}"
                                         alt="{{ $block['data']['alt'] ?? '' }}"
                                         class="max-w-full h-auto rounded-lg shadow-lg {{ $alignment === 'center' ? 'mx-auto' : '' }}"
                                         loading="lazy">
                                    @if(!empty($block['data']['caption']))
                                        <p class="text-sm text-gray-600 mt-3 italic">{{ $block['data']['caption'] }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @break

                    @case('gallery')
                        <div class="my-8">
                            @if(!empty($block['data']['images']) && is_array($block['data']['images']))
                                @php
                                    $columns = $block['data']['columns'] ?? '3';
                                    $gridClass = match($columns) {
                                        '2' => 'grid-cols-1 md:grid-cols-2',
                                        '4' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
                                        default => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'
                                    };
                                @endphp
                                <div class="grid {{ $gridClass }} gap-4">
                                    @foreach($block['data']['images'] as $image)
                                        <div class="aspect-square overflow-hidden rounded-lg">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 alt="Gallery image"
                                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                                 loading="lazy">
                                        </div>
                                    @endforeach
                                </div>
                                @if(!empty($block['data']['caption']))
                                    <p class="text-sm text-gray-600 mt-4 text-center italic">{{ $block['data']['caption'] }}</p>
                                @endif
                            @endif
                        </div>
                        @break

                    @case('video')
                        <div class="my-8">
                            @php
                                $type = $block['data']['type'] ?? 'youtube';
                                $url = $block['data']['url'] ?? '';
                                $file = $block['data']['file'] ?? '';
                                $title = $block['data']['title'] ?? '';
                                $caption = $block['data']['caption'] ?? '';
                                $autoplay = $block['data']['autoplay'] ?? false;
                            @endphp

                            <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                                @if($type === 'youtube' && $url)
                                    @php
                                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $url, $matches);
                                        $videoId = $matches[1] ?? null;
                                    @endphp
                                    @if($videoId)
                                        <iframe
                                            src="https://www.youtube.com/embed/{{ $videoId }}{{ $autoplay ? '?autoplay=1' : '' }}"
                                            title="{{ $title }}"
                                            class="absolute inset-0 w-full h-full"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    @endif
                                @elseif($type === 'vimeo' && $url)
                                    @php
                                        preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
                                        $videoId = $matches[1] ?? null;
                                    @endphp
                                    @if($videoId)
                                        <iframe
                                            src="https://player.vimeo.com/video/{{ $videoId }}{{ $autoplay ? '?autoplay=1' : '' }}"
                                            title="{{ $title }}"
                                            class="absolute inset-0 w-full h-full"
                                            frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    @endif
                                @elseif($type === 'upload' && $file)
                                    <video
                                        class="w-full h-full object-cover"
                                        controls
                                        {{ $autoplay ? 'autoplay' : '' }}
                                        preload="metadata">
                                        <source src="{{ asset('storage/' . $file) }}" type="video/mp4">
                                        Trình duyệt của bạn không hỗ trợ video HTML5.
                                    </video>
                                @endif
                            </div>

                            @if($title || $caption)
                                <div class="mt-4">
                                    @if($title)
                                        <h4 class="font-semibold text-lg text-gray-900">{{ $title }}</h4>
                                    @endif
                                    @if($caption)
                                        <p class="text-sm text-gray-600 mt-2">{{ $caption }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @break

                    @case('audio')
                        <div class="my-8">
                            @php
                                $file = $block['data']['file'] ?? '';
                                $title = $block['data']['title'] ?? '';
                                $artist = $block['data']['artist'] ?? '';
                                $caption = $block['data']['caption'] ?? '';
                                $thumbnail = $block['data']['thumbnail'] ?? '';
                            @endphp

                            @if($file)
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($thumbnail)
                                                <img src="{{ asset('storage/' . $thumbnail) }}"
                                                     alt="{{ $title }}"
                                                     class="w-16 h-16 rounded-lg object-cover">
                                            @else
                                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            @if($title)
                                                <h4 class="font-semibold text-gray-900 truncate">{{ $title }}</h4>
                                            @endif
                                            @if($artist)
                                                <p class="text-sm text-gray-600">{{ $artist }}</p>
                                            @endif
                                            @if($caption)
                                                <p class="text-sm text-gray-600 mt-1">{{ $caption }}</p>
                                            @endif

                                            <div class="mt-3">
                                                <audio class="w-full" controls preload="metadata">
                                                    <source src="{{ asset('storage/' . $file) }}" type="audio/mpeg">
                                                    Trình duyệt của bạn không hỗ trợ audio HTML5.
                                                </audio>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @break

                    @case('quote')
                        <div class="my-8">
                            @php
                                $content = $block['data']['content'] ?? '';
                                $author = $block['data']['author'] ?? '';
                                $source = $block['data']['source'] ?? '';
                                $style = $block['data']['style'] ?? 'default';
                            @endphp

                            @if($content)
                                @if($style === 'highlight')
                                    <blockquote class="relative bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-lg">
                                        <div class="absolute top-4 left-4 text-blue-500 opacity-50">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                            </svg>
                                        </div>
                                        <p class="text-lg italic text-gray-800 leading-relaxed pl-8">{{ $content }}</p>
                                        @if($author || $source)
                                            <footer class="mt-4 pl-8">
                                                <cite class="text-sm text-gray-600">
                                                    @if($author)— {{ $author }}@endif
                                                    @if($source), {{ $source }}@endif
                                                </cite>
                                            </footer>
                                        @endif
                                    </blockquote>
                                @elseif($style === 'minimal')
                                    <div class="text-center my-8">
                                        <p class="text-xl italic text-gray-700 leading-relaxed max-w-3xl mx-auto">
                                            "{{ $content }}"
                                        </p>
                                        @if($author || $source)
                                            <footer class="mt-4">
                                                <cite class="text-sm text-gray-500">
                                                    @if($author)— {{ $author }}@endif
                                                    @if($source), {{ $source }}@endif
                                                </cite>
                                            </footer>
                                        @endif
                                    </div>
                                @else
                                    <blockquote class="border-l-4 border-gray-300 pl-6 py-2 my-8">
                                        <p class="text-lg italic text-gray-700 leading-relaxed">{{ $content }}</p>
                                        @if($author || $source)
                                            <footer class="mt-3">
                                                <cite class="text-sm text-gray-600">
                                                    @if($author)— {{ $author }}@endif
                                                    @if($source), {{ $source }}@endif
                                                </cite>
                                            </footer>
                                        @endif
                                    </blockquote>
                                @endif
                            @endif
                        </div>
                        @break

                    @case('code')
                        <div class="my-8">
                            @php
                                $content = $block['data']['content'] ?? '';
                                $language = $block['data']['language'] ?? 'html';
                                $title = $block['data']['title'] ?? '';
                                $lineNumbers = $block['data']['line_numbers'] ?? true;
                            @endphp

                            @if($content)
                                <div class="bg-gray-900 rounded-lg overflow-hidden">
                                    @if($title)
                                        <div class="bg-gray-800 px-4 py-2 border-b border-gray-700">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-300">{{ $title }}</span>
                                                <span class="text-xs text-gray-500 uppercase">{{ $language }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <pre class="text-sm text-gray-100 overflow-x-auto"><code>{{ $content }}</code></pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @break

                    @case('list')
                        <div class="my-8">
                            @php
                                $type = $block['data']['type'] ?? 'bullet';
                                $items = $block['data']['items'] ?? '';
                                $title = $block['data']['title'] ?? '';
                                $itemsArray = array_filter(explode("\n", $items));
                            @endphp

                            @if($itemsArray)
                                @if($title)
                                    <h4 class="font-semibold text-lg text-gray-900 mb-4">{{ $title }}</h4>
                                @endif

                                @if($type === 'numbered')
                                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                                        @foreach($itemsArray as $item)
                                            <li class="leading-relaxed">{{ trim($item) }}</li>
                                        @endforeach
                                    </ol>
                                @elseif($type === 'checklist')
                                    <ul class="space-y-2">
                                        @foreach($itemsArray as $item)
                                            <li class="flex items-start space-x-3">
                                                <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="text-gray-700 leading-relaxed">{{ trim($item) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                                        @foreach($itemsArray as $item)
                                            <li class="leading-relaxed">{{ trim($item) }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </div>
                        @break

                    @case('divider')
                        <div class="my-8">
                            @php
                                $style = $block['data']['style'] ?? 'solid';
                                $thickness = $block['data']['thickness'] ?? 'medium';
                                $spacing = $block['data']['spacing'] ?? 'medium';

                                $thicknessClass = match($thickness) {
                                    'thin' => 'h-px',
                                    'thick' => 'h-1',
                                    default => 'h-0.5'
                                };

                                $spacingClass = match($spacing) {
                                    'small' => 'my-4',
                                    'large' => 'my-12',
                                    default => 'my-8'
                                };

                                $styleClass = match($style) {
                                    'dashed' => 'border-dashed border-t-2 border-gray-300',
                                    'dotted' => 'border-dotted border-t-2 border-gray-300',
                                    'double' => 'border-double border-t-4 border-gray-300',
                                    'gradient' => 'bg-gradient-to-r from-transparent via-gray-300 to-transparent',
                                    default => 'bg-gray-300'
                                };
                            @endphp

                            <div class="{{ $spacingClass }}">
                                @if($style === 'dashed' || $style === 'dotted' || $style === 'double')
                                    <div class="{{ $styleClass }}"></div>
                                @else
                                    <div class="{{ $thicknessClass }} {{ $styleClass }}"></div>
                                @endif
                            </div>
                        </div>
                        @break

                    @case('cta')
                        <div class="my-8">
                            @php
                                $title = $block['data']['title'] ?? '';
                                $description = $block['data']['description'] ?? '';
                                $buttonText = $block['data']['button_text'] ?? '';
                                $buttonUrl = $block['data']['button_url'] ?? '';
                                $style = $block['data']['style'] ?? 'primary';
                                $size = $block['data']['size'] ?? 'medium';

                                $buttonClass = match($style) {
                                    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
                                    'success' => 'bg-green-600 hover:bg-green-700 text-white',
                                    'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
                                    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
                                    default => 'bg-blue-600 hover:bg-blue-700 text-white'
                                };

                                $sizeClass = match($size) {
                                    'small' => 'px-4 py-2 text-sm',
                                    'large' => 'px-8 py-4 text-lg',
                                    default => 'px-6 py-3 text-base'
                                };
                            @endphp

                            @if($title && $buttonText && $buttonUrl)
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-8 text-center">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $title }}</h3>
                                    @if($description)
                                        <p class="text-gray-600 mb-6 max-w-2xl mx-auto">{{ $description }}</p>
                                    @endif
                                    <a href="{{ $buttonUrl }}"
                                       class="inline-flex items-center {{ $sizeClass }} {{ $buttonClass }} font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        {{ $buttonText }}
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                        @break

                    @default
                        {{-- Fallback cho các block type chưa được hỗ trợ --}}
                        <div class="my-4 p-4 bg-gray-100 rounded-lg">
                            <p class="text-sm text-gray-600">Loại nội dung: {{ $block['type'] ?? 'Không xác định' }}</p>
                        </div>
                @endswitch
            @endif
        @endforeach
    </div>
@elseif($post->content)
    {{-- Fallback to traditional content --}}
    <div class="prose prose-lg max-w-none">
        {!! $post->content !!}
    </div>
@else
    <div class="text-gray-500 italic">
        Chưa có nội dung.
    </div>
@endif