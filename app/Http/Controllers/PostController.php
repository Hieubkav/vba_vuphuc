<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\CatPost;
use App\Services\SeoService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Hiển thị trang filter tổng thể cho tất cả bài viết với Livewire
     */
    public function index()
    {
        return view('storefront.posts.index');
    }

    /**
     * Hiển thị danh sách bài viết theo danh mục
     */
    public function category($slug, Request $request)
    {
        $category = CatPost::where('slug', $slug)->where('status', 'active')->firstOrFail();

        // Query builder cho bài viết
        $query = Post::where('category_id', $category->id)
            ->where('status', 'active')
            ->with(['category', 'images' => function($query) {
                $query->where('status', 'active')->orderBy('order');
            }]);



        // Sắp xếp
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $posts = $query->paginate(12);

        return view('storefront.posts.category', compact('category', 'posts'));
    }

    /**
     * Hiển thị danh sách tất cả danh mục bài viết
     */
    public function categories()
    {
        $categories = CatPost::where('status', 'active')
            ->withCount(['posts' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('order')
            ->get();

        return view('storefront.posts.categories', compact('categories'));
    }

    /**
     * Hiển thị chi tiết bài viết
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'active')
            ->with([
                'images' => function($query) {
                    $query->where('status', 'active')->orderBy('order');
                },
                'category'
            ])
            ->firstOrFail();

        // Bài viết liên quan
        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'active')
            ->with(['images' => function($query) {
                $query->where('status', 'active')->orderBy('order')->limit(1);
            }])
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // SEO data
        $seoData = [
            'title' => $post->seo_title ?: $post->title,
            'description' => $post->seo_description ?: $post->excerpt,
            'ogImage' => SeoService::getPostOgImage($post),
            'structuredData' => SeoService::getPostStructuredData($post),
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('storeFront')],
                ['name' => 'Bài viết', 'url' => route('posts.categories')],
                ['name' => $post->category->name ?? 'Danh mục', 'url' => route('posts.category', $post->category->slug ?? '#')],
                ['name' => $post->title, 'url' => route('posts.show', $post->slug)]
            ]
        ];

        return view('storefront.posts.show', compact('post', 'relatedPosts', 'seoData'));
    }

    /**
     * Hiển thị danh sách bài viết theo type (deprecated - redirect to index)
     * @deprecated Sử dụng index() method thay thế
     */
    public function byType($type, Request $request)
    {
        // Redirect to the new unified posts index with type filter
        return redirect()->route('posts.index', array_merge($request->all(), ['type' => $type]));
    }
}
