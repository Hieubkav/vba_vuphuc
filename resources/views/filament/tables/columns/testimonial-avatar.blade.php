@php
    $record = $getRecord();
    $avatar = $record->avatar;
    $isInitialsAvatar = \App\Helpers\AvatarHelper::isInitialsAvatar($avatar);
@endphp

<div class="flex items-center justify-center">
    @if($isInitialsAvatar)
        @php
            $avatarData = \App\Helpers\AvatarHelper::parseAvatarString($avatar);
        @endphp
        
        @if($avatarData)
            <!-- Avatar chữ cái -->
            <div 
                class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold"
                style="background-color: {{ $avatarData['background_color'] }}; color: {{ $avatarData['text_color'] }}"
                title="Avatar tự động: {{ $avatarData['initials'] }}"
            >
                {{ $avatarData['initials'] }}
            </div>
        @else
            <!-- Fallback nếu parse lỗi -->
            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold text-gray-600">
                ?
            </div>
        @endif
    @elseif($avatar)
        <!-- Ảnh thật từ testimonial -->
        <img 
            src="{{ asset('storage/' . $avatar) }}" 
            alt="Avatar {{ $record->name }}"
            class="w-10 h-10 rounded-full object-cover"
            title="Ảnh thật"
        >
    @else
        <!-- Không có avatar -->
        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
        </div>
    @endif
</div>
