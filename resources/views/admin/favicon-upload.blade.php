<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Favicon - VBA Vũ Phúc</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="text-center mb-6">
                <i class="fas fa-image text-4xl text-red-500 mb-4"></i>
                <h1 class="text-2xl font-bold text-gray-800">Upload Favicon</h1>
                <p class="text-gray-600 mt-2">Chọn ảnh để thay thế favicon.ico</p>
            </div>

            <!-- Current Favicon -->
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 mb-2">Favicon hiện tại:</p>
                <img src="{{ asset('favicon.ico') }}?v={{ time() }}" alt="Current Favicon" class="w-8 h-8 mx-auto border border-gray-300 rounded">
            </div>

            <!-- Upload Form -->
            <form id="faviconForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-upload mr-2"></i>Chọn ảnh
                    </label>
                    <input type="file" 
                           id="favicon" 
                           name="favicon" 
                           accept="image/*" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Hỗ trợ: JPG, PNG, GIF, SVG, WebP (tối đa 2MB)</p>
                </div>

                <!-- Preview -->
                <div id="preview" class="mb-4 text-center hidden">
                    <p class="text-sm text-gray-600 mb-2">Xem trước:</p>
                    <img id="previewImage" src="" alt="Preview" class="w-16 h-16 mx-auto border border-gray-300 rounded">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        id="submitBtn"
                        class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-md transition duration-200 flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    <span id="btnText">Cập nhật Favicon</span>
                    <i id="loadingIcon" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                </button>
            </form>

            <!-- Messages -->
            <div id="message" class="mt-4 hidden">
                <div id="messageContent" class="p-3 rounded-md text-sm"></div>
            </div>

            <!-- Back Link -->
            <div class="text-center mt-6">
                <a href="{{ route('storeFront') }}" class="text-red-500 hover:text-red-600 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Về trang chủ
                </a>
            </div>
        </div>
    </div>

    <script>
        // Preview image when selected
        document.getElementById('favicon').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('preview').classList.add('hidden');
            }
        });

        // Handle form submission
        document.getElementById('faviconForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const loadingIcon = document.getElementById('loadingIcon');
            const messageDiv = document.getElementById('message');
            const messageContent = document.getElementById('messageContent');

            // Show loading state
            submitBtn.disabled = true;
            btnText.textContent = 'Đang xử lý...';
            loadingIcon.classList.remove('hidden');
            messageDiv.classList.add('hidden');

            // Debug CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('CSRF Token:', csrfToken);

            fetch('{{ route("favicon.upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Server trả về dữ liệu không phải JSON: ' + text.substring(0, 100));
                    }
                });
            })
            .then(data => {
                console.log('Parsed data:', data);

                if (data.success) {
                    messageContent.className = 'p-3 rounded-md text-sm bg-green-100 text-green-800 border border-green-200';
                    messageContent.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + data.message;

                    // Refresh current favicon display
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    messageContent.className = 'p-3 rounded-md text-sm bg-red-100 text-red-800 border border-red-200';
                    messageContent.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>' + data.message;
                }
                messageDiv.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Fetch error:', error);
                messageContent.className = 'p-3 rounded-md text-sm bg-red-100 text-red-800 border border-red-200';
                messageContent.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Có lỗi xảy ra: ' + error.message;
                messageDiv.classList.remove('hidden');
            })
            .finally(() => {
                // Reset loading state
                submitBtn.disabled = false;
                btnText.textContent = 'Cập nhật Favicon';
                loadingIcon.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
