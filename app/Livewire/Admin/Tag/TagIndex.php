<?php

namespace App\Livewire\Admin\Tag;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Str;
use App\Models\Tag;
use Illuminate\Validation\Rule;

class TagIndex extends Component
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
    public $tag_id = '';

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
                    Rule::unique('tags', 'slug'),
                ],
            ]);
        }
    }

    public function updatedSlugCreate()
    {
        $this->validateOnly('slugCreate', [
            'slugCreate' => [
                'required',
                Rule::unique('tags', 'slug'),
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
                Rule::unique('tags', 'slug'),
            ],
        ]);

        Tag::create([
            'name' => $validated['nameCreate'],
            'slug' => $validated['slugCreate'],
        ]);

        session()->flash(
            'status',
            'Tag created successfully!'
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
        $tag = Tag::findOrFail($id);

        $this->editId = $tag->id;
        $this->nameEdit = $tag->name;
        $this->slugEdit = $tag->slug;
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
                Rule::unique('tags', 'slug')
                    ->ignore($this->editId),
            ],
        ]);

        Tag::findOrFail($this->editId)->update([
            'name' => $validated['nameEdit'],
            'slug' => $validated['slugEdit'],
        ]);

        session()->flash(
            'status',
            'Tag updated successfully.'
        );

        $this->reset([
            'nameEdit',
            'slugEdit',
            'editId',
        ]);
        
        $this->dispatch('close-modal', 'edit-tag');
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

    public function deleteTag()
    {
        Tag::findOrFail($this->deleteId)?->delete();

        $this->deleteId = null;

        session()->flash('status', 'Tag deleted successfully.');
        
        $this->dispatch('close-modal', 'delete-tag');
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
            'livewire.admin.tag.tag-index',
            [
                'tags' => Tag::query()
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
