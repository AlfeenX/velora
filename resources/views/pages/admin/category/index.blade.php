<x-layouts::app :title="__('Category Tools')">
    <div class="flex justify-between">
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold">{{ __('Category Tools') }}</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                {{ __('Create, update, and delete categories, :name!', ['name' => auth()->user()->name]) }}
            </p>
        </div> 
    </div>

    <livewire:admin.category.category-index />
</x-layouts::app>