<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class CaptchaService
{
    /**
     * Tạo CAPTCHA đơn giản với phép toán cơ bản
     */
    public static function generate(): array
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $operators = ['+', '-', '*'];
        $operator = $operators[array_rand($operators)];
        
        switch ($operator) {
            case '+':
                $answer = $num1 + $num2;
                break;
            case '-':
                // Đảm bảo kết quả không âm
                if ($num1 < $num2) {
                    [$num1, $num2] = [$num2, $num1];
                }
                $answer = $num1 - $num2;
                break;
            case '*':
                $answer = $num1 * $num2;
                break;
        }
        
        $question = "{$num1} {$operator} {$num2} = ?";
        
        // Lưu đáp án vào session
        Session::put('captcha_answer', $answer);
        Session::put('captcha_time', time());
        
        return [
            'question' => $question,
            'answer' => $answer // Chỉ để debug, không trả về trong production
        ];
    }
    
    /**
     * Xác thực CAPTCHA
     */
    public static function validate($userAnswer): bool
    {
        $correctAnswer = Session::get('captcha_answer');
        $captchaTime = Session::get('captcha_time');
        
        // Kiểm tra thời gian hết hạn (5 phút)
        if (!$captchaTime || (time() - $captchaTime) > 300) {
            Session::forget(['captcha_answer', 'captcha_time']);
            return false;
        }
        
        // Kiểm tra đáp án
        $isValid = $correctAnswer && (int)$userAnswer === (int)$correctAnswer;
        
        // Xóa CAPTCHA sau khi validate (dù đúng hay sai)
        Session::forget(['captcha_answer', 'captcha_time']);
        
        return $isValid;
    }
    
    /**
     * Tạo CAPTCHA mới và trả về HTML
     */
    public static function render(): string
    {
        $captcha = self::generate();
        
        return view('components.captcha', [
            'question' => $captcha['question']
        ])->render();
    }
}
