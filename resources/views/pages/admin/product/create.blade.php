<x-layouts::app :title="__('Create Product')">
    <div class="flex justify-between mb-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold">{{ __('Create Product') }}</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                {{ __('Fill in the form below to create a new product.') }}
            </p>
        </div>
    </div>

    <livewire:admin.product.create-product />
</x-layouts::app>