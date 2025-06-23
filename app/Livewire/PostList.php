<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\CatPost;

class PostList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sort = 'newest';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sort' => ['except' => 'newest'],
        'perPage' => ['except' => 12],
    ];

    public function mount()
    {
        // Initialize from query parameters
        $this->search = request('search', '');
        $this->category = request('category', '');
        $this->sort = request('sort', 'newest');
        $this->perPage = request('perPage', 12);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->sort = 'newest';
        $this->resetPage();
    }

    public function render()
    {
        // Get categories with post count
        $categories = CatPost::where('status', 'active')
            ->withCount(['postsMany' => function($query) {
                $query->where('status', 'active');
            }])
            ->having('posts_many_count', '>', 0)
            ->orderBy('order')
            ->get();

        // Build posts query
        $query = Post::where('status', 'active')
            ->with(['categories', 'images' => function($query) {
                $query->where('status', 'active')->orderBy('order');
            }]);

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        // Apply category filter
        if ($this->category) {
            $query->whereHas('categories', function($q) {
                $q->where('slug', $this->category);
            });
        }

        // Apply sorting
        switch ($this->sort) {
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

        $posts = $query->paginate($this->perPage);

        return view('livewire.post-list', compact('posts', 'categories'));
    }
}
