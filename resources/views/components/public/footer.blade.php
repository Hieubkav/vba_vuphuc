<div class="bg-gray-50 border-t border-gray-100">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold text-red-700 mb-5">Liên hệ</h3>
                <div class="space-y-4 text-gray-600">
                    @php
                        $contacts = [
                            ['address','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />','address'],
                            ['hotline','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />','text'],
                            ['email','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />','text'],
                            ['working_hours','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />','text']
                        ];
                    @endphp
                    @foreach($contacts as $contact)
                        <x-footer-contact-item :icon="$contact[1]" :value="$globalSettings->{$contact[0]} ?? ''" :type="$contact[2]" />
                    @endforeach
                </div>
            </div>

            <!-- Company Info -->
            <div>
                @php $logoSrc=isset($globalSettings)&&!empty($globalSettings->logo_link)?asset('storage/'.$globalSettings->logo_link):asset('images/logo.png');$siteName=$globalSettings->site_name??'Vũ Phúc Baking';$companyName=$globalSettings->site_name??'CÔNG TY TNHH SX TM DV VŨ PHÚC'; @endphp

                <div class="h-16 mb-6 flex items-center">
                    <img src="{{ $logoSrc }}" alt="{{ $siteName }}" class="h-auto max-h-full object-contain" onerror="this.src='{{ asset('images/logo.png') }}'; this.onerror=null;">
                </div>

                <h3 class="text-lg font-semibold text-red-700 mb-3">{{ $companyName }}</h3>
                @php
                    $brandName = webDesignContent('footer', 'company_brand_name', 'VUPHUC BAKING®');
                    $businessLicense = webDesignContent('footer', 'company_business_license', 'Giấy phép kinh doanh số 1800935879 cấp ngày 29/4/2009');
                    $directorInfo = webDesignContent('footer', 'company_director_info', 'Chịu trách nhiệm nội dung: Trần Uy Vũ - Tổng Giám đốc');
                @endphp
                <p class="text-gray-600 mb-2 font-bold">{{ $brandName }}</p>
                <p class="text-gray-600 mb-3">{{ $businessLicense }}</p>
                <p class="text-gray-600 mb-3">{{ $directorInfo }}</p>
            </div>

            <!-- Policies -->
            <div>
                <h3 class="text-lg font-semibold text-red-700 mb-5">Chính sách</h3>
                <ul class="space-y-3 text-gray-600 mb-8">
                    @php
                        $policies = webDesignContent('footer', 'policies', [
                            ['title' => 'CHÍNH SÁCH & ĐIỀU KHOẢN MUA BÁN HÀNG HÓA', 'url' => '#'],
                            ['title' => 'HỆ THỐNG ĐẠI LÝ & ĐIỂM BÁN HÀNG', 'url' => '#'],
                            ['title' => 'BẢO MẬT & QUYỀN RIÊNG TƯ', 'url' => '#'],
                        ]);
                    @endphp
                    @foreach($policies as $policy)
                        <li>
                            <a href="{{ $policy['url'] ?? '#' }}" class="hover:text-red-700 transition-colors">
                                {{ $policy['title'] ?? '' }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Hiệp hội & Chứng nhận -->
                @if(isset($associations) && $associations->count() > 0)
                    <div>
                        <h4 class="text-lg font-semibold text-red-700 mb-3">Hiệp hội & Chứng nhận</h4>

                        @php
                            $associationsWithImages = $associations->filter(function($association) {
                                return !empty($association->image_link);
                            });
                            $associationsWithoutImages = $associations->filter(function($association) {
                                return empty($association->image_link);
                            });
                        @endphp

                        <!-- Hiển thị associations có ảnh -->
                        @if($associationsWithImages->count() > 0)
                            <div class="flex flex-wrap gap-3 mb-4">
                                @foreach($associationsWithImages as $association)
                                    <div class="association-item relative group">
                                        @if(!empty($association->website_link))
                                            <a href="{{ $association->website_link }}"
                                               target="_blank"
                                               title="{{ $association->name }}"
                                               class="block">
                                                <img src="{{ asset('storage/' . $association->image_link) }}"
                                                     alt="{{ $association->name }}"
                                                     class="h-12 w-auto object-contain bg-white rounded-lg p-1 border border-gray-100 hover:border-red-200 hover:shadow-md transition-all duration-300 group-hover:scale-105"
                                                     onerror="handleImageError(this)">
                                            </a>
                                        @else
                                            <img src="{{ asset('storage/' . $association->image_link) }}"
                                                 alt="{{ $association->name }}"
                                                 title="{{ $association->name }}"
                                                 class="h-12 w-auto object-contain bg-white rounded-lg p-1 border border-gray-100 hover:border-red-200 hover:shadow-md transition-all duration-300"
                                                 onerror="handleImageError(this)">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Hiển thị associations không có ảnh dưới dạng text links -->
                        @if($associationsWithoutImages->count() > 0)
                            <div class="space-y-2">
                                @foreach($associationsWithoutImages as $association)
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-building text-red-500 mr-2 text-xs"></i>
                                        @if(!empty($association->website_link))
                                            <a href="{{ $association->website_link }}"
                                               target="_blank"
                                               class="text-gray-600 hover:text-red-600 transition-colors">
                                                {{ $association->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-600">{{ $association->name }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="bg-red-700 py-4 text-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm">
                    @php
                        $copyright = webDesignContent('footer', 'copyright', '© ' . date('Y') . ' Copyright by VBA Vũ Phúc - All Rights Reserved');
                    @endphp
                    {{ $copyright }}
                </p>
            </div>
        </div>
    </div>
</div>