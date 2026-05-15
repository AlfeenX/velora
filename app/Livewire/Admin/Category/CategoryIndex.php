<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Validation\Rule;

class CategoryIndex extends Component
{
    use WithPagination;

    // CREATE
    public $nameCreate = '';
    public $slugCreate = '';

    // EDIT
    public $nameEdit = '';
    public $slugEdit = '';
    public $editId = null;
    public $deleteId = null;

    // FILTER
    public $category_id = '';

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDirection = 'desc';


    /*
    |--------------------------------------------------------------------------
    | SORT
    |--------------------------------------------------------------------------
    */

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection =
                $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO SLUG CREATE
    |--------------------------------------------------------------------------
    */

    public function updatedNameCreate($value)
    {
        $this->validateOnly('nameCreate', [
            'nameCreate' => 'required',
        ]);

        if (!empty($value)) {
            $this->slugCreate = Str::slug($value);

            $this->validateOnly('slugCreate', [
                'slugCreate' => [
                    'required',
                    Rule::unique('categories', 'slug'),
                ],
            ]);
        }
    }

    public function updatedSlugCreate()
    {
        $this->validateOnly('slugCreate', [
            'slugCreate' => [
                'required',
                Rule::unique('categories', 'slug'),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        $validated = $this->validate([
            'nameCreate' => 'required',
            'slugCreate' => [
                'required',
                Rule::unique('categories', 'slug'),
            ],
        ]);

        Category::create([
            'name' => $validated['nameCreate'],
            'slug' => $validated['slugCreate'],
        ]);

        session()->flash(
            'status',
            'Category created successfully!'
        );

        $this->reset([
            'nameCreate',
            'slugCreate',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $this->editId = $category->id;
        $this->nameEdit = $category->name;
        $this->slugEdit = $category->slug;
    }


    public function cancelEdit()
    {
        $this->reset([
            'nameEdit',
            'slugEdit',
            'editId',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update()
    {
        $validated = $this->validate([
            'nameEdit' => 'required',
            'slugEdit' => [
                'required',
                Rule::unique('categories', 'slug')
                    ->ignore($this->editId),
            ],
        ]);

        Category::findOrFail($this->editId)->update([
            'name' => $validated['nameEdit'],
            'slug' => $validated['slugEdit'],
        ]);

        session()->flash(
            'status',
            'Category updated successfully.'
        );

        $this->reset([
            'nameEdit',
            'slugEdit',
            'editId',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function deleteCategory()
    {
        Category::findOrFail($this->deleteId)?->delete();

        $this->deleteId = null;

        session()->flash('status', 'Category deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.admin.category.category-index',
            [
                'categories' => Category::query()
                    ->when($this->search, function ($query) {
                        $query->where(
                            'name',
                            'like',
                            '%' . $this->search . '%'
                        );
                    })
                    ->orderBy(
                        $this->sortBy,
                        $this->sortDirection
                    )
                    ->paginate(5),
            ]
        );
    }
}