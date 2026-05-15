<x-layouts::app :title="__('Tag Tools')">
    <div class="flex justify-between">
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold">{{ __('Tag Tools') }}</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                {{ __('Create, update, and delete tags, :name!', ['name' => auth()->user()->name]) }}
            </p>
        </div> 
    </div>

    <livewire:admin.tag.tag-index />
</x-layouts::app>