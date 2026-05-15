<x-layouts::app :title="__('Inventory Products')">
    <div class="flex justify-between">
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold">{{ __('Manage Payment') }}</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                {{ __('Manage your products, :name!', ['name' => auth()->user()->name]) }}
            </p>
        </div>
        <x-button primary wire:navigate href="{{ route('home') }}" icon="arrow-left">
            {{ __('Back to homepage') }}
        </x-button>
    </div>

</x-layouts::app>