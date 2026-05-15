<x-layouts::app :title="__('Velora | Product Inventory')">
    <div class="flex justify-between items-center mb-6">
        <div class="flex flex-col gap-1">
            <h1 class="text-3xl font-bold tracking-tight">{{ __('Product Inventory') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Manage your product catalog, stock, and pricing.') }}
            </p>
        </div>
        <flux:button primary wire:navigate href="{{ route('admin.products.create') }}" icon="plus">
            {{ __('Add Product') }}
        </flux:button>
    </div>

    <livewire:admin.product.product-index />
</x-layouts::app>