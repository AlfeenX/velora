<x-layouts::app :title="__('Edit Product')">
    <div class="flex justify-between items-center mb-6">
        <div class="flex flex-col gap-1">
            <h1 class="text-3xl font-bold tracking-tight">{{ __('Edit Product') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Update the product details, images, and variants.') }}
            </p>
        </div>
    </div>

    <livewire:admin.product.edit-product :product="$product" />
</x-layouts::app>
