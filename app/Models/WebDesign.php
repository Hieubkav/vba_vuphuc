<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsViewCache;

class WebDesign extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        // Hero Banner
        'hero_banner_enabled',
        'hero_banner_order',
        
        // Courses Overview
        'courses_overview_enabled',
        'courses_overview_order',
        'courses_overview_title',
        'courses_overview_description',
        'courses_overview_bg_color',
        'courses_overview_animation_class',
        
        // Album Timeline
        'album_timeline_enabled',
        'album_timeline_order',
        'album_timeline_title',
        'album_timeline_description',
        'album_timeline_bg_color',
        'album_timeline_animation_class',
        
        // Course Groups
        'course_groups_enabled',
        'course_groups_order',
        'course_groups_title',
        'course_groups_description',
        'course_groups_bg_color',
        'course_groups_animation_class',
        
        // Course Categories
        'course_categories_enabled',
        'course_categories_order',
        'course_categories_title',
        'course_categories_description',
        'course_categories_bg_color',
        'course_categories_animation_class',
        
        // Testimonials
        'testimonials_enabled',
        'testimonials_order',
        'testimonials_title',
        'testimonials_description',
        'testimonials_bg_color',
        'testimonials_animation_class',
        
        // FAQ
        'faq_enabled',
        'faq_order',
        'faq_title',
        'faq_description',
        'faq_bg_color',
        'faq_animation_class',
        
        // Partners
        'partners_enabled',
        'partners_order',
        'partners_title',
        'partners_description',
        'partners_bg_color',
        'partners_animation_class',
        
        // Blog Posts
        'blog_posts_enabled',
        'blog_posts_order',
        'blog_posts_title',
        'blog_posts_description',
        'blog_posts_bg_color',
        'blog_posts_animation_class',
        
        // Homepage CTA
        'homepage_cta_enabled',
        'homepage_cta_order',
        'homepage_cta_title',
        'homepage_cta_description',
        'homepage_cta_primary_button_text',
        'homepage_cta_primary_button_url',
        'homepage_cta_secondary_button_text',
        'homepage_cta_secondary_button_url',
    ];

    protected $casts = [
        'hero_banner_enabled' => 'boolean',
        'hero_banner_order' => 'integer',
        'courses_overview_enabled' => 'boolean',
        'courses_overview_order' => 'integer',
        'album_timeline_enabled' => 'boolean',
        'album_timeline_order' => 'integer',
        'course_groups_enabled' => 'boolean',
        'course_groups_order' => 'integer',
        'course_categories_enabled' => 'boolean',
        'course_categories_order' => 'integer',
        'testimonials_enabled' => 'boolean',
        'testimonials_order' => 'integer',
        'faq_enabled' => 'boolean',
        'faq_order' => 'integer',
        'partners_enabled' => 'boolean',
        'partners_order' => 'integer',
        'blog_posts_enabled' => 'boolean',
        'blog_posts_order' => 'integer',
        'homepage_cta_enabled' => 'boolean',
        'homepage_cta_order' => 'integer',
    ];

    /**
     * Get all sections ordered by their order field
     */
    public function getOrderedSections()
    {
        $sections = [
            'hero_banner' => [
                'enabled' => $this->hero_banner_enabled,
                'order' => $this->hero_banner_order,
                'component' => 'components.storefront.hero-banner',
                'type' => 'include'
            ],
            'courses_overview' => [
                'enabled' => $this->courses_overview_enabled,
                'order' => $this->courses_overview_order,
                'title' => $this->courses_overview_title,
                'description' => $this->courses_overview_description,
                'bg_color' => $this->courses_overview_bg_color,
                'animation_class' => $this->courses_overview_animation_class,
                'component' => 'courses-overview',
                'type' => 'livewire'
            ],
            'album_timeline' => [
                'enabled' => $this->album_timeline_enabled,
                'order' => $this->album_timeline_order,
                'title' => $this->album_timeline_title,
                'description' => $this->album_timeline_description,
                'bg_color' => $this->album_timeline_bg_color,
                'animation_class' => $this->album_timeline_animation_class,
                'component' => 'components.storefront.album-timeline',
                'type' => 'include'
            ],
            'course_groups' => [
                'enabled' => $this->course_groups_enabled,
                'order' => $this->course_groups_order,
                'title' => $this->course_groups_title,
                'description' => $this->course_groups_description,
                'bg_color' => $this->course_groups_bg_color,
                'animation_class' => $this->course_groups_animation_class,
                'component' => 'components.storefront.course-groups',
                'type' => 'include'
            ],
            'course_categories' => [
                'enabled' => $this->course_categories_enabled,
                'order' => $this->course_categories_order,
                'title' => $this->course_categories_title,
                'description' => $this->course_categories_description,
                'bg_color' => $this->course_categories_bg_color,
                'animation_class' => $this->course_categories_animation_class,
                'component' => 'components.storefront.course-categories-sections',
                'type' => 'include'
            ],
            'testimonials' => [
                'enabled' => $this->testimonials_enabled,
                'order' => $this->testimonials_order,
                'title' => $this->testimonials_title,
                'description' => $this->testimonials_description,
                'bg_color' => $this->testimonials_bg_color,
                'animation_class' => $this->testimonials_animation_class,
                'component' => 'components.storefront.testimonials',
                'type' => 'include'
            ],
            'faq' => [
                'enabled' => $this->faq_enabled,
                'order' => $this->faq_order,
                'title' => $this->faq_title,
                'description' => $this->faq_description,
                'bg_color' => $this->faq_bg_color,
                'animation_class' => $this->faq_animation_class,
                'component' => 'components.storefront.faq-section',
                'type' => 'include'
            ],
            'partners' => [
                'enabled' => $this->partners_enabled,
                'order' => $this->partners_order,
                'title' => $this->partners_title,
                'description' => $this->partners_description,
                'bg_color' => $this->partners_bg_color,
                'animation_class' => $this->partners_animation_class,
                'component' => 'components.storefront.partners',
                'type' => 'include'
            ],
            'blog_posts' => [
                'enabled' => $this->blog_posts_enabled,
                'order' => $this->blog_posts_order,
                'title' => $this->blog_posts_title,
                'description' => $this->blog_posts_description,
                'bg_color' => $this->blog_posts_bg_color,
                'animation_class' => $this->blog_posts_animation_class,
                'component' => 'components.storefront.blog-posts',
                'type' => 'include'
            ],
            'homepage_cta' => [
                'enabled' => $this->homepage_cta_enabled,
                'order' => $this->homepage_cta_order,
                'component' => 'components.storefront.homepage-cta',
                'type' => 'include'
            ]
        ];

        // Filter enabled sections and sort by order
        $enabledSections = array_filter($sections, function($section) {
            return $section['enabled'];
        });

        uasort($enabledSections, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return $enabledSections;
    }
}
