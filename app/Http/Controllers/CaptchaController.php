<?php

namespace App\Http\Controllers;

use App\Services\CaptchaService;
use Illuminate\Http\JsonResponse;

class CaptchaController extends Controller
{
    /**
     * Làm mới CAPTCHA
     */
    public function refresh(): JsonResponse
    {
        $captcha = CaptchaService::generate();
        
        return response()->json([
            'question' => $captcha['question']
        ]);
    }
}
