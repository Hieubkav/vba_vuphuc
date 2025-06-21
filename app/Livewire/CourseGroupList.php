<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourseGroup;
use Illuminate\Support\Facades\Cache;

class CourseGroupList extends Component
{
    use WithPagination;

    public $search = '';
    public $groupType = '';
    public $sort = 'order';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'groupType' => ['except' => ''],
        'sort' => ['except' => 'order'],
    ];

    public function mount()
    {
        // Initialize from request if available
        $this->search = request('search', '');
        $this->groupType = request('groupType', '');
        $this->sort = request('sort', 'order');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedGroupType()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->groupType = '';
        $this->sort = 'order';
        $this->resetPage();
    }

    public function getCourseGroups()
    {
        $query = CourseGroup::where('status', 'active')
            ->whereNotNull('group_link');

        // Apply filters
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if (!empty($this->groupType)) {
            $query->where('group_type', $this->groupType);
        }

        // Apply sorting
        switch ($this->sort) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'members':
                $query->orderBy('current_members', 'desc');
                break;
            default:
                $query->orderBy('order', 'asc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate($this->perPage);
    }

    public function getGroupTypes()
    {
        return Cache::remember('course_group_types', 1800, function() {
            return CourseGroup::where('status', 'active')
                ->whereNotNull('group_link')
                ->selectRaw('group_type, count(*) as count')
                ->groupBy('group_type')
                ->pluck('count', 'group_type');
        });
    }

    public function getStats()
    {
        return Cache::remember('course_group_stats', 1800, function() {
            return [
                'total' => CourseGroup::where('status', 'active')->whereNotNull('group_link')->count(),
                'types' => $this->getGroupTypes(),
                'total_members' => CourseGroup::where('status', 'active')->sum('current_members'),
            ];
        });
    }

    public function render()
    {
        $courseGroups = $this->getCourseGroups();
        $groupTypes = $this->getGroupTypes();
        $stats = $this->getStats();

        return view('livewire.course-group-list', compact('courseGroups', 'groupTypes', 'stats'));
    }
}
