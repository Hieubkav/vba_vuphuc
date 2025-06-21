{{--
    Course Group Card Component
    Hiển thị thông tin nhóm học tập với design đẹp mắt
--}}

<div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100 h-full flex flex-col">
    <div class="p-6 flex-1 flex flex-col">
        <!-- Group Type Badge & Status -->
        <div class="flex items-center justify-between mb-4">
            <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full
                {{ $group->group_type === 'facebook' ? 'bg-blue-50 text-blue-700' : '' }}
                {{ $group->group_type === 'zalo' ? 'bg-blue-50 text-blue-600' : '' }}
                {{ $group->group_type === 'telegram' ? 'bg-blue-50 text-blue-500' : '' }}
                {{ !in_array($group->group_type, ['facebook', 'zalo', 'telegram']) ? 'bg-gray-50 text-gray-600' : '' }}">
                <i class="{{ $group->group_type_icon }} mr-1"></i>
                {{ $group->formatted_group_type }}
            </span>
            
            @if($group->isFull())
                <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                    <i class="fas fa-users mr-1"></i>Đầy
                </span>
            @else
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <i class="fas fa-check-circle mr-1"></i>Mở
                </span>
            @endif
        </div>

        <!-- Group Name -->
        <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-red-600 transition-colors duration-300 font-montserrat">
            {{ $group->name }}
        </h3>

        <!-- Description -->
        @if($group->description)
        <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed font-open-sans flex-1">
            {{ $group->description }}
        </p>
        @endif

        <!-- Group Stats -->
        <div class="flex items-center justify-between text-sm text-gray-500 mb-4 pt-2 border-t border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-users mr-1 text-gray-400"></i>
                <span class="font-medium">
                    {{ number_format($group->current_members) }}
                    @if($group->max_members)
                        / {{ number_format($group->max_members) }}
                    @endif
                </span>
                <span class="ml-1">thành viên</span>
            </div>
            
            @if($group->max_members)
            <div class="flex items-center">
                <div class="w-16 bg-gray-200 rounded-full h-1.5 mr-2">
                    <div class="bg-red-500 h-1.5 rounded-full transition-all duration-300" 
                         style="width: {{ min(100, ($group->current_members / $group->max_members) * 100) }}%"></div>
                </div>
                <span class="text-xs">{{ round(($group->current_members / $group->max_members) * 100) }}%</span>
            </div>
            @endif
        </div>

        <!-- Join Button -->
        <div class="mt-auto">
            @if($group->group_link)
                <a href="{{ $group->group_link }}" 
                   target="_blank"
                   rel="noopener noreferrer"
                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-all duration-300 group-hover:shadow-md font-open-sans
                   {{ $group->isFull() ? 'opacity-75 cursor-not-allowed' : '' }}"
                   {{ $group->isFull() ? 'onclick="return false;"' : '' }}>
                    
                    @if($group->group_type === 'facebook')
                        <i class="fab fa-facebook mr-2"></i>
                        {{ $group->isFull() ? 'Nhóm đã đầy' : 'Tham gia Facebook' }}
                    @elseif($group->group_type === 'zalo')
                        <i class="fas fa-comments mr-2"></i>
                        {{ $group->isFull() ? 'Nhóm đã đầy' : 'Tham gia Zalo' }}
                    @elseif($group->group_type === 'telegram')
                        <i class="fab fa-telegram mr-2"></i>
                        {{ $group->isFull() ? 'Nhóm đã đầy' : 'Tham gia Telegram' }}
                    @else
                        <i class="fas fa-users mr-2"></i>
                        {{ $group->isFull() ? 'Nhóm đã đầy' : 'Tham gia nhóm' }}
                    @endif
                    
                    @if(!$group->isFull())
                        <i class="fas fa-external-link-alt ml-2 text-xs opacity-75"></i>
                    @endif
                </a>
            @else
                <div class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-500 font-medium rounded-lg cursor-not-allowed font-open-sans">
                    <i class="fas fa-link-slash mr-2"></i>
                    Chưa có link
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
