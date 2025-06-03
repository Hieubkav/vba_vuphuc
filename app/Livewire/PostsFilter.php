<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class PostsFilter extends Component
{
    public $search = '';
    public $type = '';
    public $sort = 'newest';
    public $perPage = 12;
    public $loadedPosts = [];
    public $hasMorePosts = true;

    public $typeNames = [
        'normal' => 'Bài viết',
        'news' => 'Tin tức',
        'service' => 'Dịch vụ',
        'course' => 'Khóa học'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'sort' => ['except' => 'newest'],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->type = request('type', '');
        $this->sort = request('sort', 'newest');
        $this->loadPosts();
    }

    public function updatedSearch()
    {
        $this->resetPosts();
    }

    public function updatedType()
    {
        $this->resetPosts();
    }

    public function updatedSort()
    {
        $this->resetPosts();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->type = '';
        $this->sort = 'newest';
        $this->resetPosts();
    }

    public function loadMore()
    {
        $this->perPage += 12;
        $this->loadPosts();
    }

    private function resetPosts()
    {
        $this->perPage = 12;
        $this->loadedPosts = [];
        $this->hasMorePosts = true;
        $this->loadPosts();
    }

    private function loadPosts()
    {
        $query = $this->getQuery();

        $posts = $query->take($this->perPage)->get();
        $this->loadedPosts = $posts;

        // Check if there are more posts
        $totalPosts = $this->getQuery()->count();
        $this->hasMorePosts = $posts->count() < $totalPosts;
    }

    private function getQuery()
    {
        $query = Post::where('status', 'active')
            ->with(['category', 'images' => function($query) {
                $query->where('status', 'active')->orderBy('order');
            }]);

        // Lọc theo type
        if ($this->type && in_array($this->type, ['normal', 'news', 'service', 'course'])) {
            $query->where('type', $this->type);
        }

        // Tìm kiếm theo từ khóa
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%");
            });
        }

        // Sắp xếp
        switch ($this->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('order')->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }

    public function render()
    {
        $totalPosts = $this->getQuery()->count();

        return view('livewire.posts-filter', [
            'posts' => collect($this->loadedPosts),
            'totalPosts' => $totalPosts
        ]);
    }
}
