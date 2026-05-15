<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Tag;
use App\Services\Product\ProductService;
use Illuminate\Validation\Rule;

class CreateProduct extends Component
{
    use WithFileUploads;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $category_id = '';
    public $collection_id = '';
    public $gender = 'unisex';
    public $release_date = '';
    public $selectedTags = [];

    public $images = [];
    public $primaryImageIndex = '0';

    public $variants = [
        ['sku' => '', 'color' => '', 'size' => '', 'price' => '', 'stock' => 0, 'weight' => '']
    ];

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);

        $this->validate([
            'required'
        ]);
    }

    public function updatedSlug()
    {
        $this->validateOnly('slug', [
            'slug' => [
                'required',
                Rule::unique('products', 'slug'),
            ]
            ]);
    }

    public function addVariant()
    {
        $this->variants[] = ['sku' => '', 'color' => '', 'size' => '', 'price' => '', 'stock' => 0, 'weight' => ''];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
        if ((string) $this->primaryImageIndex === (string) $index) {
            $this->primaryImageIndex = '0';
        } elseif ($this->primaryImageIndex > $index) {
            $this->primaryImageIndex = (string) ((int) $this->primaryImageIndex - 1);
        }
    }

    public function setPrimaryImage($index)
    {
        $this->primaryImageIndex = $index;
    }

    public function save(ProductService $service)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'collection_id' => 'nullable|exists:collections,id',
            'gender' => 'required|in:male,female,unisex',
            'release_date' => 'nullable|date',
            'variants.*.sku' => 'required|string|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'images.*' => 'image|max:2048', // 2MB Max
        ]);

        $product = $service->store([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'collection_id' => $this->collection_id ?: null,
            'gender' => $this->gender,
            'release_date' => $this->release_date ?: null,
            'tags' => $this->selectedTags,
            'variants' => $this->variants,
        ]);

        foreach ($this->images as $index => $image) {
            $path = $image->store('products', 'public');
            $product->images()->create([
                'image_url' => '/storage/' . $path,
                'is_primary' => (string) $index === (string) $this->primaryImageIndex,
            ]);
        }

        session()->flash('status', 'Product created successfully!');

        return $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.product.create-product', [
            'categories' => Category::all(),
            'collections' => Collection::all(),
            'tags' => Tag::all(),
        ]);
    }
}
