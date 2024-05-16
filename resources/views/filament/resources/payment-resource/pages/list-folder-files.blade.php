<x-filament::page>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight">Files in {{ $this->getTitle() }}</h2>
    </x-slot>

    <div class="p-4 bg-white shadow rounded-lg">
            @csrf
            <ul class="list-none p-0">
                @foreach ($this->getFiles() as $file)
                    <li class="mb-2 flex items-center">
                        <span>{{ $loop->index + 1 }}. {{ $file }}</span>
                    </li>
                @endforeach
            </ul>
    </div>
</x-filament::page>
