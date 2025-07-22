<?php

namespace App\Helpers;

class AvatarHelper
{
    /**
     * Tạo avatar chữ cái từ tên khách hàng
     * 
     * @param string $name Tên khách hàng
     * @return string Avatar string (VD: "MH", "NVA")
     */
    public static function generateInitials(string $name): string
    {
        // Loại bỏ khoảng trắng thừa và chuyển về chữ hoa
        $name = trim($name);
        if (empty($name)) {
            return 'KH'; // Khách hàng - fallback
        }

        // Tách các từ trong tên
        $words = preg_split('/\s+/', $name);
        $initials = '';

        // Lấy chữ cái đầu của mỗi từ
        foreach ($words as $word) {
            if (!empty($word)) {
                // Lấy ký tự đầu tiên của từ (hỗ trợ tiếng Việt)
                $firstChar = mb_substr($word, 0, 1, 'UTF-8');
                $initials .= mb_strtoupper($firstChar, 'UTF-8');
            }
        }

        // Giới hạn tối đa 3 chữ cái để tránh quá dài
        if (mb_strlen($initials, 'UTF-8') > 3) {
            $initials = mb_substr($initials, 0, 3, 'UTF-8');
        }

        // Nếu chỉ có 1 chữ cái, thêm chữ cái thứ 2 từ tên
        if (mb_strlen($initials, 'UTF-8') === 1 && count($words) === 1) {
            $secondChar = mb_substr($words[0], 1, 1, 'UTF-8');
            if (!empty($secondChar)) {
                $initials .= mb_strtoupper($secondChar, 'UTF-8');
            }
        }

        return $initials ?: 'KH';
    }

    /**
     * Tạo màu nền cho avatar dựa trên tên
     * 
     * @param string $name Tên khách hàng
     * @return string Hex color code
     */
    public static function generateBackgroundColor(string $name): string
    {
        // Danh sách màu đẹp cho avatar
        $colors = [
            '#FF6B6B', // Red
            '#4ECDC4', // Teal
            '#45B7D1', // Blue
            '#96CEB4', // Green
            '#FFEAA7', // Yellow
            '#DDA0DD', // Plum
            '#98D8C8', // Mint
            '#F7DC6F', // Light Yellow
            '#BB8FCE', // Light Purple
            '#85C1E9', // Light Blue
            '#F8C471', // Orange
            '#82E0AA', // Light Green
        ];

        // Tạo hash từ tên để chọn màu consistent
        $hash = crc32($name);
        $index = abs($hash) % count($colors);
        
        return $colors[$index];
    }

    /**
     * Tạo màu chữ tương phản với màu nền
     * 
     * @param string $backgroundColor Hex color code
     * @return string Hex color code cho chữ
     */
    public static function getContrastTextColor(string $backgroundColor): string
    {
        // Loại bỏ dấu # nếu có
        $color = ltrim($backgroundColor, '#');
        
        // Chuyển hex sang RGB
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        // Tính độ sáng (luminance)
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Trả về màu trắng hoặc đen tùy theo độ sáng
        return $luminance > 0.5 ? '#000000' : '#FFFFFF';
    }

    /**
     * Tạo avatar data hoàn chỉnh cho feedback
     * 
     * @param string $name Tên khách hàng
     * @return array Avatar data với initials, background color, text color
     */
    public static function generateFeedbackAvatar(string $name): array
    {
        $initials = self::generateInitials($name);
        $backgroundColor = self::generateBackgroundColor($name);
        $textColor = self::getContrastTextColor($backgroundColor);

        return [
            'initials' => $initials,
            'background_color' => $backgroundColor,
            'text_color' => $textColor,
            'type' => 'initials'
        ];
    }

    /**
     * Tạo avatar string để lưu vào database
     * Format: "initials:MH|bg:#FF6B6B|color:#FFFFFF"
     * 
     * @param string $name Tên khách hàng
     * @return string Avatar string để lưu vào database
     */
    public static function generateAvatarString(string $name): string
    {
        $avatar = self::generateFeedbackAvatar($name);
        
        return sprintf(
            'initials:%s|bg:%s|color:%s',
            $avatar['initials'],
            $avatar['background_color'],
            $avatar['text_color']
        );
    }

    /**
     * Parse avatar string từ database
     * 
     * @param string $avatarString Avatar string từ database
     * @return array|null Avatar data hoặc null nếu không parse được
     */
    public static function parseAvatarString(string $avatarString): ?array
    {
        if (!str_starts_with($avatarString, 'initials:')) {
            return null; // Không phải avatar chữ cái
        }

        $parts = explode('|', $avatarString);
        if (count($parts) !== 3) {
            return null;
        }

        $initials = str_replace('initials:', '', $parts[0]);
        $backgroundColor = str_replace('bg:', '', $parts[1]);
        $textColor = str_replace('color:', '', $parts[2]);

        return [
            'initials' => $initials,
            'background_color' => $backgroundColor,
            'text_color' => $textColor,
            'type' => 'initials'
        ];
    }

    /**
     * Kiểm tra xem avatar có phải là chữ cái không
     * 
     * @param string|null $avatar Avatar string
     * @return bool
     */
    public static function isInitialsAvatar(?string $avatar): bool
    {
        return $avatar && str_starts_with($avatar, 'initials:');
    }
}
