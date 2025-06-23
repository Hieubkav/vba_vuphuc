<?php
/**
 * Script tạo logo mẫu cho associations
 */

// Tạo thư mục nếu chưa có
$logoDir = 'storage/app/public/associations/logos';
if (!is_dir($logoDir)) {
    mkdir($logoDir, 0755, true);
}

// Tạo ảnh SVG đơn giản cho từng association
$logos = [
    'vaip-logo.png' => [
        'text' => 'VAIP',
        'color' => '#2563eb', // blue
        'bg' => '#dbeafe'
    ],
    'vinasa-logo.png' => [
        'text' => 'VINASA', 
        'color' => '#dc2626', // red
        'bg' => '#fecaca'
    ],
    'vinasme-logo.png' => [
        'text' => 'VINASME',
        'color' => '#059669', // green
        'bg' => '#a7f3d0'
    ]
];

foreach ($logos as $filename => $config) {
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="200" height="100" viewBox="0 0 200 100" xmlns="http://www.w3.org/2000/svg">
    <rect width="200" height="100" fill="' . $config['bg'] . '" rx="10"/>
    <text x="100" y="55" font-family="Arial, sans-serif" font-size="18" font-weight="bold" 
          text-anchor="middle" fill="' . $config['color'] . '">' . $config['text'] . '</text>
</svg>';
    
    // Lưu file SVG (tạm thời dùng SVG thay PNG)
    $svgFile = $logoDir . '/' . str_replace('.png', '.svg', $filename);
    file_put_contents($svgFile, $svg);
    
    // Tạo file PNG giả (copy từ SVG)
    $pngFile = $logoDir . '/' . $filename;
    file_put_contents($pngFile, $svg);
    
    echo "Created: $pngFile\n";
}

echo "Sample logos created successfully!\n";
