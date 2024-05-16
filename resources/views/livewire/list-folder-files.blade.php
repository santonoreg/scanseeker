<div>
    <h2 class="text-2xl font-bold leading-tight">Files in {{ $folderName }}</h2>
    <div>
        @foreach ($files as $file)
            <div>{{ $file }}</div>
        @endforeach
    </div>
</div>
