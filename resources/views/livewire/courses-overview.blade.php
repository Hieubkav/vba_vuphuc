<div>
    <!-- Preload Critical Images for Performance -->
    @if($isLoaded && !empty($criticalImages))
        @foreach($criticalImages as $image)
            <link rel="preload" as="image" href="{{ $image }}">
        @endforeach
    @endif

    <!-- Structured Data for SEO -->
    @if($isLoaded && !empty($structuredData))
        <script type="application/ld+json">
            {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endif

    @if(!$isLoaded)
        <x-skeleton-loader type="courses-overview" :count="3" />
    @else
        <!-- Main Content với Performance Optimization -->
        <div class="courses-overview">
            @include('components.storefront.courses-overview', [
                'courseCategories' => $courseCategoriesData,
                'courseStats' => $realStats
            ])
        </div>
    @endif
</div>

@push('scripts')
<script src="{{ asset('js/courses-overview.js') }}"></script>
@endpush
