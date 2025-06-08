<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\Course;
use App\Models\CourseImage;
use App\Models\CourseMaterial;
use App\Models\Slider;
use App\Models\Association;
use App\Models\Setting;
use App\Models\Album;
use App\Models\AlbumImage;
use App\Observers\PostObserver;
use App\Observers\PostImageObserver;
use App\Observers\CourseObserver;
use App\Observers\CourseImageObserver;
use App\Observers\CourseMaterialObserver;
use App\Observers\SliderObserver;
use App\Observers\AssociationObserver;
use App\Observers\SettingObserver;
use App\Observers\AlbumObserver;
use App\Observers\AlbumImageObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Đăng ký observer cho các model có file upload
        Post::observe(PostObserver::class);
        PostImage::observe(PostImageObserver::class);
        Course::observe(CourseObserver::class);
        CourseImage::observe(CourseImageObserver::class);
        CourseMaterial::observe(CourseMaterialObserver::class);
        Slider::observe(SliderObserver::class);
        Association::observe(AssociationObserver::class);
        Setting::observe(SettingObserver::class);
        Album::observe(AlbumObserver::class);
        AlbumImage::observe(AlbumImageObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
