<x-filament-panels::page>
    <form wire:submit.prevent="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center justify-end gap-4">
            <x-filament::actions :actions="$this->getFormActions()" />
        </div>
    </form>
</x-filament-panels::page>