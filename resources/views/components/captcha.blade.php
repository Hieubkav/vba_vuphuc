{{-- CAPTCHA Component --}}
<div class="captcha-container">
    <label for="captcha" class="block text-sm font-medium text-gray-700 mb-2">
        Xác thực bảo mật <span class="text-red-500">*</span>
    </label>
    
    <div class="flex items-center space-x-4">
        {{-- Câu hỏi CAPTCHA --}}
        <div class="bg-gray-100 border border-gray-300 rounded-lg px-4 py-3 font-mono text-lg font-semibold text-gray-800 min-w-[120px] text-center">
            {{ $question }}
        </div>
        
        {{-- Input đáp án --}}
        <input 
            type="number" 
            id="captcha" 
            name="captcha" 
            class="w-20 px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-center font-semibold @error('captcha') border-red-500 @enderror"
            placeholder="?"
            required
            autocomplete="off"
        >
        
        {{-- Nút làm mới --}}
        <button 
            type="button" 
            onclick="refreshCaptcha()" 
            class="px-3 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors"
            title="Làm mới câu hỏi"
        >
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
    
    @error('captcha')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
    
    <p class="mt-2 text-xs text-gray-500">
        Vui lòng giải phép tính trên để xác thực bạn không phải robot
    </p>
</div>

<script>
function refreshCaptcha() {
    fetch('{{ route("captcha.refresh") }}')
        .then(response => response.json())
        .then(data => {
            document.querySelector('.captcha-container .bg-gray-100').textContent = data.question;
            document.getElementById('captcha').value = '';
        })
        .catch(error => {
            console.error('Error refreshing captcha:', error);
        });
}
</script>
