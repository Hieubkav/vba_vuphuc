<div class="space-y-4">
    <div class="text-center">
        <img 
            src="{{ $image->full_image_url }}" 
            alt="{{ $image->alt_text ?: $image->course->title }}"
            class="max-w-full h-auto rounded-lg shadow-lg mx-auto"
            style="max-height: 500px;"
        >
    </div>
    
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <strong>Mô tả:</strong>
            <p class="text-gray-600">{{ $image->alt_text ?: 'Chưa có mô tả' }}</p>
        </div>
        
        <div>
            <strong>Trạng thái:</strong>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $image->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $image->status === 'active' ? 'Hiển thị' : 'Ẩn' }}
            </span>
        </div>

        
        <div>
            <strong>Thứ tự:</strong>
            <p class="text-gray-600">{{ $image->order }}</p>
        </div>
        
        <div>
            <strong>Ngày tạo:</strong>
            <p class="text-gray-600">{{ $image->created_at->format('d/m/Y H:i') }}</p>
        </div>
        
        <div>
            <strong>Cập nhật:</strong>
            <p class="text-gray-600">{{ $image->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    @if($image->exists())
        <div class="text-center">
            <a 
                href="{{ $image->full_image_url }}" 
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                Mở ảnh gốc
            </a>
        </div>
    @endif
</div>
