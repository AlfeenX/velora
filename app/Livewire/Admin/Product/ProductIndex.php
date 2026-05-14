<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ProductIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $category_id = '';

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDirection = 'desc';

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            session()->flash('status', 'Product deleted successfully.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category', 'variants', 'tags'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_id, function ($query) {
                $query->where('category_id', $this->category_id);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.product.product-index', [
            'products' => $products,
            'categories' => Category::all(),
        ]);
    }
}
