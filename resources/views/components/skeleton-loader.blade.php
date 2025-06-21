@props(['type' => 'default', 'count' => 3])

@if($type === 'courses-overview')
    <div class="container mx-auto px-4 relative z-10 courses-overview">
        <!-- Header Skeleton -->
        <div class="flex flex-col items-center text-center mb-12">
            <div class="skeleton-advanced w-48 h-8 mb-4 rounded-full"></div>
            <div class="skeleton-advanced w-96 h-12 mb-6 rounded-lg" style="animation-delay: 0.1s;"></div>
            <div class="skeleton-advanced w-full max-w-3xl h-6 rounded-lg" style="animation-delay: 0.2s;"></div>
        </div>
        
        <!-- Stats Skeleton -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12 w-full max-w-4xl mx-auto">
            @for($i = 0; $i < 4; $i++)
            <div class="text-center">
                <div class="skeleton-advanced w-16 h-10 mb-2 mx-auto rounded" style="animation-delay: {{ $i * 0.1 }}s;"></div>
                <div class="skeleton-advanced w-20 h-4 mx-auto rounded" style="animation-delay: {{ $i * 0.1 + 0.05 }}s;"></div>
            </div>
            @endfor
        </div>

        <!-- Course Cards Skeleton -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            @for($i = 0; $i < $count; $i++)
            <div class="course-card-glass rounded-3xl overflow-hidden shadow-xl" style="animation-delay: {{ $i * 0.2 }}s;">
                <div class="skeleton-advanced h-16"></div>
                <div class="skeleton-advanced aspect-[4/3]"></div>
                <div class="p-8 space-y-6">
                    <div class="skeleton-advanced h-6 w-3/4"></div>
                    <div class="skeleton-advanced h-4 w-full"></div>
                    <div class="skeleton-advanced h-4 w-2/3"></div>
                    <div class="flex flex-wrap gap-4">
                        <div class="skeleton-advanced h-8 w-24"></div>
                        <div class="skeleton-advanced h-8 w-16"></div>
                    </div>
                    <div class="flex gap-3">
                        <div class="skeleton-advanced h-12 flex-1"></div>
                        <div class="skeleton-advanced h-12 flex-1"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
@endif
