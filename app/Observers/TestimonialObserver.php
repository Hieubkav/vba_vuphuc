<?php

namespace App\Observers;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class TestimonialObserver
{
    /**
     * Handle the Testimonial "created" event.
     */
    public function created(Testimonial $testimonial): void
    {
        $this->clearTestimonialsCache();
    }

    /**
     * Handle the Testimonial "updated" event.
     */
    public function updated(Testimonial $testimonial): void
    {
        $this->clearTestimonialsCache();
    }

    /**
     * Handle the Testimonial "deleted" event.
     */
    public function deleted(Testimonial $testimonial): void
    {
        $this->clearTestimonialsCache();
    }

    /**
     * Clear testimonials cache
     */
    protected function clearTestimonialsCache(): void
    {
        Cache::forget('storefront_testimonials');
        
        // Clear view cache nếu có
        if (function_exists('cache_clear')) {
            cache_clear('testimonials');
        }
    }
}
