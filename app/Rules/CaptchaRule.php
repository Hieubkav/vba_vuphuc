<?php

namespace App\Rules;

use App\Services\CaptchaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!CaptchaService::validate($value)) {
            $fail('Kết quả xác thực bảo mật không chính xác. Vui lòng thử lại.');
        }
    }
}
