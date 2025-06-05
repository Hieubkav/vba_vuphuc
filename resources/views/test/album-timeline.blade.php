@extends('layouts.shop')

@section('title', 'Test Album Timeline Component')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Test Header -->
    <div class="bg-white shadow-sm py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Test Album Timeline Component</h1>
            <p class="text-gray-600 mt-2">Kiểm tra giao diện timeline album với dữ liệu thực</p>
        </div>
    </div>

    <!-- Album Timeline Component -->
    <section class="py-12 md:py-16 bg-gray-50">
        @include('components.storefront.album-timeline')
    </section>
</div>
@endsection
