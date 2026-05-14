<form wire:submit="save" class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Form Content -->
        <div class="md:col-span-2 space-y-6">
            <flux:card>
                <div class="space-y-6">
                    <div>
                        <flux:input wire:model.live="name" label="Product Name" placeholder="e.g. Vintage Leather Jacket" />
                    </div>

                    <div>
                        <flux:input wire:model.live.debounce.500ms="slug" label="Slug" placeholder="vintage-leather-jacket" />

                        @if (session()->has('slug_exists'))
                            <span class="text-sm text-red-500 mt-1">{{ session('slug_exists') }}</span>
                        @endif
                    </div>

                    <div>
                        <flux:textarea wire:model="description" label="Description" rows="5" placeholder="Enter product description here..." />
                    </div>
                </div>
            </flux:card>

            <!-- Images Section -->
            <flux:card>
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Product Images</h3>
                    <p class="text-sm text-gray-500">Upload multiple images. Select the primary image by clicking the radio button.</p>
                </div>
                
                <div class="space-y-4">
                    <flux:input type="file" wire:model="images" multiple accept="image/*" />
                    
                    @if ($images)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            @foreach ($images as $index => $image)
                                <div class="relative group rounded-lg border border-gray-200 dark:border-gray-700 p-2 flex flex-col items-center">
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-md mb-2">
                                    
                                    <div class="absolute top-2 right-2 flex gap-2">
                                        <button type="button" wire:click="removeImage({{ $index }})" class="p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                            <flux:icon.trash class="w-4 h-4" />
                                        </button>
                                    </div>
                                    
                                    <label class="flex items-center gap-1.5 cursor-pointer mt-1">
                                        <input type="radio" wire:model="primaryImageIndex" value="{{ $index }}" class="accent-zinc-900 dark:accent-white">
                                        <span class="text-xs text-zinc-600 dark:text-zinc-400">Primary</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </flux:card>

            <!-- Variants Section -->
            <flux:card>
                <div class="mb-4 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Product Variants</h3>
                        <p class="text-sm text-gray-500">Add different sizes, colors, and prices.</p>
                    </div>
                    <flux:button wire:click="addVariant" size="sm" icon="plus">Add Variant</flux:button>
                </div>

                <div class="space-y-6">
                    @foreach ($variants as $index => $variant)
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg relative">
                            @if(count($variants) > 1)
                                <button type="button" wire:click="removeVariant({{ $index }})" class="absolute top-4 right-4 text-gray-400 hover:text-red-500">
                                    <flux:icon.x-mark class="w-5 h-5" />
                                </button>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                <flux:input wire:model="variants.{{ $index }}.sku" label="SKU" placeholder="e.g. VINTAGE-JKT-RED-M" required />
                                <flux:input wire:model="variants.{{ $index }}.price" type="number" step="0.01" label="Price ($)" placeholder="99.99" required />
                                <flux:input wire:model="variants.{{ $index }}.color" label="Color" placeholder="e.g. Red" required />
                                <flux:input wire:model="variants.{{ $index }}.size" label="Size" placeholder="e.g. M, L, XL" />
                                <flux:input wire:model="variants.{{ $index }}.stock" type="number" label="Stock Quantity" placeholder="10" required />
                                <flux:input wire:model="variants.{{ $index }}.weight" type="number" label="Weight (g)" placeholder="500" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        </div>

        <!-- Sidebar / Settings -->
        <div class="space-y-6">
            <flux:card>
                <div class="space-y-6">
                    <flux:select wire:model="category_id" label="Category" placeholder="Select a Category">
                        @foreach($categories as $category)
                            <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="collection_id" label="Collection" placeholder="Select a Collection">
                        <flux:select.option value="">No Collection</flux:select.option>
                        @foreach($collections as $collection)
                            <flux:select.option value="{{ $collection->id }}">{{ $collection->name }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="gender" label="Gender Category">
                        <flux:select.option value="unisex">Unisex</flux:select.option>
                        <flux:select.option value="male">Male</flux:select.option>
                        <flux:select.option value="female">Female</flux:select.option>
                    </flux:select>

                    <flux:input wire:model="release_date" type="date" label="Release Date" />

                    <div>
                        <flux:label>Tags</flux:label>
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach($tags as $tag)
                                <label class="cursor-pointer">
                                    <input type="checkbox" wire:model="selectedTags" value="{{ $tag->id }}" class="peer sr-only">
                                    <div class="px-3 py-1 text-sm font-medium rounded-full border border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 peer-checked:bg-zinc-900 peer-checked:dark:bg-white peer-checked:text-white peer-checked:dark:text-zinc-900 peer-checked:border-zinc-900 peer-checked:dark:border-white hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                                        {{ $tag->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </flux:card>
            
            <div class="flex flex-col gap-4">
                <flux:button type="submit" variant="primary" class="w-full">Save Product</flux:button>
                <flux:button href="{{ route('admin.products.index') }}" variant="ghost" class="w-full">Cancel</flux:button>
            </div>
        </div>
    </div>
</form>
