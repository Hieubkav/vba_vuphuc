<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Menu Update</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">Test Menu Update</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Menu Display -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Current Menu</h2>
                @livewire('public.dynamic-menu', ['isMobile' => false])
            </div>
            
            <!-- Test Controls -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Test Controls</h2>
                
                <div class="space-y-4">
                    <button onclick="refreshMenu()" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Refresh Menu
                    </button>
                    
                    <button onclick="clearCache()" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Clear Cache
                    </button>
                    
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-600 mb-2">
                            Để test: Thêm menu item mới trong Filament admin, sau đó click "Refresh Menu" để xem có cập nhật không.
                        </p>
                        <p class="text-sm text-gray-500">
                            Cache sẽ tự động clear khi có thay đổi trong database.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    
    <script>
        function refreshMenu() {
            Livewire.dispatch('refreshMenu');
        }
        
        function clearCache() {
            fetch('/clear-cache', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                alert('Cache cleared!');
                refreshMenu();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error clearing cache');
            });
        }
    </script>
</body>
</html>
