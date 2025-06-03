@extends('layouts.shop')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent mb-8">🎨 Navbar với Menu Động từ Database</h1>

        <div class="space-y-8">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-6 rounded-lg shadow-sm">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="text-green-500 mr-3">✨</span>
                    Giao diện mới đã được cải thiện
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">🎯 Design Features:</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                            <li>✅ Đã bỏ top bar trùng với subnav</li>
                            <li>✅ Logo với hover effect scale</li>
                            <li>✅ Search bar với gradient button và icons</li>
                            <li>✅ Menu động nằm riêng dưới navbar chính</li>
                            <li>✅ Dropdown với backdrop blur và animations</li>
                            <li>✅ <strong>Icons đã được redesign hoàn toàn</strong></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">📱 Mobile Experience:</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                            <li>Hamburger menu với smooth animations</li>
                            <li>Mobile search với focus auto</li>
                            <li>Accordion submenu với transitions</li>
                            <li>Touch-friendly button sizes</li>
                            <li>Gradient backgrounds và shadows</li>
                            <li>Custom scrollbar styling</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="border-l-4 border-blue-500 pl-4">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">🔧 Livewire Components đã tạo:</h2>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li><code class="bg-gray-100 px-2 py-1 rounded">DynamicMenu</code> - Menu động từ database</li>
                    <li><code class="bg-gray-100 px-2 py-1 rounded">SearchBar</code> - Tìm kiếm realtime</li>
                    <li><code class="bg-gray-100 px-2 py-1 rounded">CartIcon</code> - Icon giỏ hàng</li>
                    <li><code class="bg-gray-100 px-2 py-1 rounded">UserAccount</code> - Quản lý tài khoản</li>
                </ul>
            </div>

            <div class="border-l-4 border-yellow-500 pl-4">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">🚀 Routes tìm kiếm đã tạo:</h2>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li><code class="bg-gray-100 px-2 py-1 rounded">products.search</code> - /tim-kiem/san-pham</li>
                    <li><code class="bg-gray-100 px-2 py-1 rounded">posts.search</code> - /tim-kiem/bai-viet</li>
                </ul>
            </div>

            <div class="border-l-4 border-purple-500 pl-4">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">⚡ Cache & Performance:</h2>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>MenuItemObserver để auto-clear cache</li>
                    <li>ViewServiceProvider đã cập nhật với MenuItem data</li>
                    <li>Search results được cache 5 phút</li>
                    <li>Navigation data được cache 2 giờ</li>
                </ul>
            </div>

            <div class="bg-gradient-to-r from-orange-50 to-amber-50 border-l-4 border-orange-500 p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="text-orange-500 mr-3">🎨</span>
                    Icons đã được redesign hoàn toàn
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white p-4 rounded-lg border border-orange-200">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            🛒 Cart Icon - Giỏ hàng
                        </h4>
                        <ul class="text-sm space-y-1 text-gray-600">
                            <li>✨ Circular design với border và shadow</li>
                            <li>🎯 Hover effect với color transition</li>
                            <li>📊 Badge counter với gradient background</li>
                            <li>💫 Scale animation khi hover</li>
                            <li>🏷️ Enhanced tooltip với arrow</li>
                            <li>🌙 Dark mode compatibility</li>
                        </ul>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-orange-200">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            👤 User Icon - Tài khoản
                        </h4>
                        <ul class="text-sm space-y-1 text-gray-600">
                            <li>✨ Circular design với border và shadow</li>
                            <li>🎯 Different colors: blue (guest), green (logged in)</li>
                            <li>🟢 Online status indicator cho logged in</li>
                            <li>📋 Enhanced dropdown với user info header</li>
                            <li>🎨 Gradient backgrounds cho menu items</li>
                            <li>🔄 Smooth transitions và animations</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-orange-100 rounded-lg border border-orange-300">
                    <h5 class="font-semibold text-orange-700 mb-2">🎯 Test Icons:</h5>
                    <p class="text-sm text-orange-600">
                        Hover vào cart icon và user icon ở góc phải navbar để xem tooltips và effects mới!
                    </p>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="text-blue-500 mr-3">🎯</span>
                    Menu động từ Database (MenuItem Model)
                </h3>
                <div class="bg-white p-4 rounded-lg border border-blue-200">
                    <h4 class="font-semibold text-gray-700 mb-3">Cấu trúc menu hiện tại từ database:</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-3"></span>
                            <span class="font-medium">Trang chủ</span> <span class="text-gray-500">(hardcode - luôn hiển thị đầu tiên)</span>
                        </div>

                        @if(isset($menuItems) && $menuItems->count() > 0)
                            @foreach($menuItems as $item)
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-3"></span>
                                    <span class="font-medium">{{ $item->label }}</span>
                                    <span class="text-gray-500">
                                        ({{ $item->type }} - {{ $item->children->count() > 0 ? 'có submenu' : 'không có submenu' }})
                                    </span>
                                </div>
                                @if($item->children->count() > 0)
                                    <div class="ml-6 space-y-1">
                                        @foreach($item->children as $child)
                                            <div class="flex items-center">
                                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                                                <span>{{ $child->label }}</span>
                                                <span class="text-gray-500">({{ $child->type }} - menu cấp 2)</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-orange-600 font-medium">
                                ⚠️ Không có menu items trong database. Đang hiển thị menu mặc định.
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
                        <h5 class="font-semibold text-green-700 mb-2">✅ Đã tạo dữ liệu mẫu:</h5>
                        <p class="text-sm text-green-600">
                            Chạy <code class="bg-green-100 px-2 py-1 rounded">php artisan db:seed --class=MenuItemSeeder</code>
                            để tạo menu mẫu với cấu trúc parent-child.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-l-4 border-purple-500 p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="text-purple-500 mr-3">🚀</span>
                    Hướng dẫn test navbar
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Desktop Testing:</h4>
                        <ol class="list-decimal list-inside space-y-1 text-gray-600">
                            <li>Hover vào logo để xem scale effect</li>
                            <li>Click vào search bar và nhập từ khóa</li>
                            <li><strong>Hover vào menu có submenu (Giới thiệu, Sản phẩm, Tin tức) để xem dropdown</strong></li>
                            <li>Hover vào cart và user icons để xem tooltips</li>
                            <li>Kiểm tra thanh menu nằm riêng dưới navbar chính</li>
                        </ol>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Mobile Testing:</h4>
                        <ol class="list-decimal list-inside space-y-1 text-gray-600">
                            <li>Resize browser xuống mobile size</li>
                            <li>Click hamburger menu để mở mobile menu</li>
                            <li><strong>Click menu có submenu để xem accordion (Giới thiệu, Sản phẩm, Tin tức)</strong></li>
                            <li>Test search icon và cart icon</li>
                            <li>Test scroll trong mobile menu</li>
                        </ol>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-white rounded-lg border border-purple-200">
                    <h4 class="font-semibold text-purple-700 mb-2">💡 Pro Tips:</h4>
                    <ul class="list-disc list-inside space-y-1 text-gray-600 text-sm">
                        <li>Navbar có backdrop blur effect khi scroll</li>
                        <li>Search dropdown có smooth transitions</li>
                        <li>Mobile menu có body scroll lock</li>
                        <li>Tooltips chỉ hiển thị trên desktop</li>
                        <li>Gradient effects tương thích với dark mode</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
