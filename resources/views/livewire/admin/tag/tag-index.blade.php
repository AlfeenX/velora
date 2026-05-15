<div class="space-y-4 mt-8">
    <div class="flex gap-4 w-full md:max-w-xl">
        <flux:input wire:model.live.debounce.500ms="search" icon="magnifying-glass" placeholder="Search tags..."
            clearable class="flex-1" />
    </div>
    <!-- Add Tag -->
    <flux:card class="p-4">
        <h1 class="pb-3 text-xl font-semibold">Add Tag</h1>
        <form wire:submit="save" class=" flex gap-4">
            <div class="space-y-2 w-full">
                <flux:input wire:model.live="nameCreate" placeholder="Tag Name" />
                @error('nameCreate')
                    <span class="text-sm text-red-500 font-semibold mt-4 px-4 py-1 block"><flux:icon.exclamation-triangle
                            class="inline-block w-4 h-4" />{{ $message }}</span>
                @enderror
            </div>
            <div class="space-y-2 w-full">
                <flux:input wire:model.live.debounce.500ms="slugCreate" placeholder="Tag Slug" />
                @error('slugCreate')
                    <span class="text-sm font-semibold   text-red-500 mt-4 px-4 py-1 block"><flux:icon.exclamation-triangle
                            class="inline-block w-4 h-4 mr-1" />{{ $message }}</span>
                @enderror
            </div>
            <flux:button type="submit" variant="primary">Add Tag</flux:button>
        </form>
    </flux:card>

    <!-- Tags Table -->
    <flux:card class="px-2 py-1">
        <flux:table :paginate="$tags">
            <flux:table.columns>
                <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection"
                    wire:click="sort('name')">
                    Tag
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection"
                    wire:click="sort('slug')">
                    Slug
                </flux:table.column>

                <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                    wire:click="sort('created_at')">
                    Created At
                </flux:table.column>

                <flux:table.column align="end">
                    Actions
                </flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($tags as $tag)

                    <flux:table.row wire:key="tag-{{ $tag->id }}">
                        <flux:table.cell>{{ $tag->name }}</flux:table.cell>
                        <flux:table.cell>{{ $tag->slug }}</flux:table.cell>
                        <flux:table.cell>
                            {{ $tag->created_at->format('Y-m-d') }}
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            <flux:modal.trigger name="edit-tag">
                                <flux:button wire:click="edit({{ $tag->id }})" icon="pencil-square" size="sm"
                                    color="zinc" inset="top bottom" variant="ghost"/>
                            </flux:modal.trigger>
                            <flux:modal.trigger name="delete-tag">
                                <flux:button wire:click="confirmDelete({{ $tag->id }})" icon="trash" size="sm"
                                    class="hover:text-red-600" inset="top bottom" variant="ghost"/>
                            </flux:modal.trigger>
                        </flux:table.cell>
                    </flux:table.row>

                @empty

                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center py-12 text-zinc-500">
                            No tags found
                        </flux:table.cell>
                    </flux:table.row>

                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- Edit Modal -->
    <flux:modal name="edit-tag" flyout>
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Update Tag
                </flux:heading>

                <flux:text class="mt-2">
                    Edit tag data.
                </flux:text>
            </div>

            <form wire:submit="update" class="space-y-4">
                <div>
                    <flux:label>Name</flux:label>
                    <flux:input wire:model="nameEdit" />
                    @error('nameEdit')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <flux:label>Slug</flux:label>
                    <flux:input wire:model="slugEdit" />
                    @error('slugEdit')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <flux:button type="button" variant="ghost" x-on:click="$flux.modal('edit-tag').close()"
                        wire:click="cancelEdit">
                        Cancel
                    </flux:button>

                    <flux:button type="submit" variant="primary">
                        Update
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    <!-- Delete Modal -->
    <flux:modal name="delete-tag" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete tag?</flux:heading>
                <flux:text class="mt-2">
                    You're about to delete this tag.<br>
                    This action cannot be reversed.
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="deleteTag" variant="danger">
                    Delete
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
