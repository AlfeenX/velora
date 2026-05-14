<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class EditProduct extends Component
{
    use WithFileUploads;

    public Product $product;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $category_id = '';
    public $collection_id = '';
    public $gender = 'unisex';
    public $release_date = '';
    public $selectedTags = [];

    public $images = [];
    public $existingImages = [];
    public $primaryImageIndex = '0';

    public $variants = [];

    public function mount(Product $product)
    {
        $product->load(['category', 'collection', 'tags', 'variants', 'images']);
        
        $this->product = $product;
        $this->name = $product->name ?? '';
        $this->slug = $product->slug ?? '';
        $this->description = $product->description ?? '';
        $this->category_id = $product->category_id ? (string) $product->category_id : '';
        $this->collection_id = $product->collection_id ? (string) $product->collection_id : '';
        $this->gender = $product->gender ?? 'unisex';
        $this->release_date = $product->release_date ? (\Illuminate\Support\Carbon::parse($product->release_date)->format('Y-m-d')) : '';
        $this->selectedTags = $product->tags->pluck('id')->map(fn($id) => (string) $id)->toArray();

        $this->existingImages = $product->images->toArray();
        
        $primaryImage = $product->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            foreach ($this->existingImages as $index => $img) {
                if ($img['id'] === $primaryImage->id) {
                    $this->primaryImageIndex = (string) $index;
                    break;
                }
            }
        }

        $this->variants = $product->variants->map(function ($variant) {
            return [
                'id' => (string) $variant->id,
                'sku' => (string) ($variant->sku ?? ''),
                'color' => (string) ($variant->color ?? ''),
                'size' => (string) ($variant->size ?? ''),
                'price' => (string) ($variant->price ?? ''),
                'stock' => (int) ($variant->stock ?? 0),
                'weight' => (string) ($variant->weight ?? ''),
            ];
        })->toArray();

        if (empty($this->variants)) {
            $this->addVariant();
        }
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function addVariant()
    {
        $this->variants[] = [
            'sku' => '',
            'color' => '',
            'size' => '',
            'price' => '',
            'stock' => 0,
            'weight' => '',
        ];
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
    }

    public function removeExistingImage($id)
    {
        $image = $this->product->images()->find($id);
        if ($image) {
            $image->delete();
            $this->existingImages = $this->product->fresh()->images->toArray();
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $this->product->id,
            'category_id' => 'required|exists:categories,id',
            'collection_id' => 'nullable|exists:collections,id',
            'gender' => 'required|in:male,female,unisex',
            'release_date' => 'nullable|date',
            'variants.*.sku' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $this->product->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'collection_id' => $this->collection_id ?: null,
            'gender' => $this->gender,
            'release_date' => $this->release_date ?: null,
        ]);

        $this->product->tags()->sync($this->selectedTags);

        // Update variants
        $variantIds = collect($this->variants)->pluck('id')->filter()->toArray();
        $this->product->variants()->whereNotIn('id', $variantIds)->delete();

        foreach ($this->variants as $variantData) {
            if (isset($variantData['id'])) {
                $this->product->variants()->find($variantData['id'])->update($variantData);
            } else {
                $this->product->variants()->create($variantData);
            }
        }

        // Handle new images
        foreach ($this->images as $image) {
            $path = $image->store('products', 'public');
            $this->product->images()->create([
                'image_url' => '/storage/' . $path,
                'is_primary' => false,
            ]);
        }

        // Update primary image status for all images
        // This is a bit tricky with existing and new. For now let's just allow primary among existing.
        foreach ($this->existingImages as $index => $imgData) {
            $this->product->images()->find($imgData['id'])->update([
                'is_primary' => (string) $index === (string) $this->primaryImageIndex
            ]);
        }

        session()->flash('status', 'Product updated successfully!');

        return $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.product.edit-product', [
            'categories' => Category::all(),
            'collections' => Collection::all(),
            'tags' => Tag::all(),
        ]);
    }
}
