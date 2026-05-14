<div class="space-y-6">
    @if (session('status'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            class="fixed bottom-10 right-10 z-[100] p-4 rounded-xl bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 shadow-2xl flex items-center gap-3 border border-zinc-800 dark:border-zinc-200"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4"
        >
            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                <flux:icon.check class="w-4 h-4 text-white" />
            </div>
            <span class="font-medium pr-2">{{ session('status') }}</span>
            <button @click="show = false" class="hover:opacity-70 transition-opacity">
                <flux:icon.x-mark class="w-4 h-4" />
            </button>
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
        <div class="flex flex-1 gap-4 w-full md:max-w-xl">
            <flux:input 
                wire:model.live.debounce.500ms="search" 
                icon="magnifying-glass" 
                placeholder="Search products..." 
                clearable
                class="flex-1"
            />
            
            <flux:select wire:model.live="category_id" placeholder="All Categories" class="w-full md:w-48">
                <flux:select.option value="">All Categories</flux:select.option>
                @foreach($categories as $category)
                    <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="flex gap-2">
            {{-- Additional actions can go here --}}
        </div>
    </div>

    <flux:card class="overflow-hidden px-3 py-1">
        <flux:table :paginate="$products">
            <flux:table.columns>
                <flux:table.column 
                    sortable 
                    :sorted="$sortBy === 'name'" 
                    :direction="$sortDirection" 
                    wire:click="sort('name')"
                >
                    Product
                </flux:table.column>

                <flux:table.column 
                    sortable 
                    :sorted="$sortBy === 'category_id'" 
                    :direction="$sortDirection" 
                    wire:click="sort('category_id')"
                >
                    Category
                </flux:table.column>

                <flux:table.column>
                    Price
                </flux:table.column>

                <flux:table.column>
                    Stock
                </flux:table.column>

                <flux:table.column>
                    Tags
                </flux:table.column>

                <flux:table.column align="end">
                    Actions
                </flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($products as $product)
                    <flux:table.row wire:key="product-{{ $product->id }}">
                        <flux:table.cell class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 overflow-hidden flex-shrink-0 border border-zinc-200 dark:border-zinc-700">
                                @if($product->images->where('is_primary', true)->first())
                                    <img src="{{ $product->images->where('is_primary', true)->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-zinc-400">
                                        <flux:icon.photo class="w-5 h-5" />
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col">
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $product->name }}</span>
                                <span class="text-xs text-zinc-500">{{ $product->slug }}</span>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge size="sm" color="zinc" inset="top bottom">
                                {{ $product->category?->name ?? 'Uncategorized' }}
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell class="font-medium text-zinc-900 dark:text-white">
                            @php
                                $minPrice = $product->variants->min('price');
                                $maxPrice = $product->variants->max('price');
                            @endphp
                            
                            @if($minPrice === $maxPrice)
                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            @php
                                $totalStock = $product->variants->sum('stock');
                            @endphp
                            <span @class([
                                'text-zinc-900 dark:text-white',
                                'text-red-600 dark:text-red-400 font-bold' => $totalStock <= 5,
                            ])>
                                {{ $totalStock }}
                            </span>
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex flex-wrap gap-1">
                                @foreach($product->tags->take(2) as $tag)
                                    <flux:badge size="xs" variant="outline">{{ $tag->name }}</flux:badge>
                                @endforeach
                                @if($product->tags->count() > 2)
                                    <flux:badge size="xs" variant="outline">+{{ $product->tags->count() - 2 }}</flux:badge>
                                @endif
                            </div>
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                <flux:button 
                                    variant="ghost" 
                                    size="sm" 
                                    icon="pencil-square" 
                                    wire:navigate 
                                    href="{{ route('admin.products.edit', $product) }}" 
                                    inset="top bottom"
                                />
                                <flux:modal.trigger name="delete-product-{{ $product->id }}">
                                    <flux:button 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="trash" 
                                        class="hover:text-red-600"
                                        inset="top bottom"
                                    />
                                </flux:modal.trigger>

                                <flux:modal name="delete-product-{{ $product->id }}" class="md:w-[25rem]">
                                    <div class="space-y-6">
                                        <div class="text-start">
                                            <flux:heading size="lg">Delete Product?</flux:heading>
                                            <flux:subheading>
                                                Are you sure you want to delete <strong>{{ $product->name }}</strong>? This action cannot be undone.
                                            </flux:subheading>
                                        </div>

                                        <div class="flex gap-2 justify-end">
                                            <flux:modal.close>
                                                <flux:button variant="ghost">Cancel</flux:button>
                                            </flux:modal.close>
                                            <flux:button wire:click="deleteProduct({{ $product->id }})" variant="danger">Delete Product</flux:button>
                                        </div>
                                    </div>
                                </flux:modal>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-12 text-zinc-500">
                            No products found matching your criteria.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
