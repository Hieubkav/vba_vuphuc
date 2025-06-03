<footer class="bg-gray-50 border-t border-gray-100">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Thông tin liên hệ -->
            <div>
                <h3 class="text-lg font-semibold text-red-700 mb-5">Liên hệ</h3>
                <div class="space-y-4 text-gray-600">
                    @if(isset($globalSettings) && !empty($globalSettings->address))
                        <p class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-0.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $globalSettings->address }}</span>
                        </p>
                    @endif

                    @if(isset($globalSettings) && !empty($globalSettings->hotline))
                        <p class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>{{ $globalSettings->hotline }}</span>
                        </p>
                    @endif

                    @if(isset($globalSettings) && !empty($globalSettings->email))
                        <p class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $globalSettings->email }}</span>
                        </p>
                    @endif

                    @if(isset($globalSettings) && !empty($globalSettings->working_hours))
                        <p class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $globalSettings->working_hours }}</span>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Thông tin công ty -->
            <div>
                @if(isset($globalSettings) && !empty($globalSettings->logo_link))
                    <div class="h-16 mb-6 flex items-center">
                        <img src="{{ asset('storage/' . $globalSettings->logo_link) }}"
                            alt="{{ $globalSettings->site_name ?? 'Vũ Phúc Baking' }}"
                            class="h-auto max-h-full object-contain"
                            onerror="this.src='{{ asset('images/logo.png') }}'; this.onerror=null;">
                    </div>
                @else
                    <div class="h-16 mb-6 flex items-center">
                        <img src="{{ asset('images/logo.png') }}"
                            alt="{{ isset($globalSettings) && !empty($globalSettings->site_name) ? $globalSettings->site_name : 'Vũ Phúc Baking' }}"
                            class="h-auto max-h-full object-contain">
                    </div>
                @endif

                <h3 class="text-lg font-semibold text-red-700 mb-3">
                    {{ isset($globalSettings) && !empty($globalSettings->site_name) ? $globalSettings->site_name : 'CÔNG TY TNHH SX TM DV VŨ PHÚC' }}
                </h3>
                <p class="text-gray-600 mb-2 font-bold">VUPHUC BAKING®</p>
                <p class="text-gray-600 mb-3">Giấy phép kinh doanh số 1800935879 cấp ngày 29/4/2009</p>
                <p class="text-gray-600 mb-3">Chịu trách nhiệm nội dung: Trần Uy Vũ - Tổng Giám đốc</p>

                <!-- Certification Images -->
                @if(isset($associations) && !empty($associations) && $associations->count() > 0)
                    <div class="mt-4 flex flex-wrap gap-3">
                        @foreach($associations as $association)
                            @if(!empty($association->image_link))
                                @if(!empty($association->website_link))
                                    <a href="{{ $association->website_link }}" target="_blank" title="{{ $association->name }}">
                                        <img src="{{ asset('storage/' . $association->image_link) }}"
                                             alt="{{ $association->name }}"
                                             class="h-12 hover:opacity-80 transition-opacity"
                                             onerror="this.style.display='none'">
                                    </a>
                                @else
                                    <img src="{{ asset('storage/' . $association->image_link) }}"
                                         alt="{{ $association->name }}"
                                         class="h-12"
                                         onerror="this.style.display='none'">
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Chính sách -->
            <div>
                <h3 class="text-lg font-semibold text-red-700 mb-5">Chính sách</h3>
                <ul class="space-y-3 text-gray-600">
                    <li><a href="#" class="hover:text-red-700 transition-colors">CHÍNH SÁCH & ĐIỀU KHOẢN MUA BÁN HÀNG HÓA</a></li>
                    <li><a href="#" class="hover:text-red-700 transition-colors">HỆ THỐNG ĐẠI LÝ & ĐIỂM BÁN HÀNG</a></li>
                    <li><a href="#" class="hover:text-red-700 transition-colors">BẢO MẬT & QUYỀN RIÊNG TƯ</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="bg-red-700 py-4 text-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm">
                    &copy; {{ date('Y') }} Copyright by
                    {{ isset($globalSettings) && !empty($globalSettings->site_name) ? $globalSettings->site_name : 'VUPHUC BAKING®' }}
                    - All Rights Reserved
                </p>
                {{-- <p class="text-sm mt-2 md:mt-0">Thiết kế bởi <a href="#" class="text-white hover:text-red-200">Phương Việt</a></p> --}}
            </div>
        </div>
    </div>
</footer>