<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiên làm việc hết hạn - 419</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            text-align: center;
            padding: 20px;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #dc3545;
            margin: 0;
        }
        .error-title {
            font-size: 24px;
            color: #333;
            margin: 20px 0;
        }
        .error-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }
        .btn-primary {
            background: #dc3545;
            color: white;
        }
        .btn-secondary {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="error-code">419</h1>
        <h2 class="error-title">Phiên làm việc hết hạn</h2>
        <p class="error-message">Phiên làm việc của bạn đã hết hạn. Vui lòng tải lại trang và thử lại.</p>
        
        <a href="{{ route('storeFront') }}" class="btn btn-primary">Về trang chủ</a>
        <button onclick="location.reload()" class="btn btn-secondary">Tải lại trang</button>
    </div>
</body>
</html>
