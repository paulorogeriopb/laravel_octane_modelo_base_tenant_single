@props(['route', 'method' => 'POST', 'data' => null])

<form action="{{ $route }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if ($method === 'PUT')
        @method('PUT')
    @endif

    <div class="mb-4">
        <label for="name" class="form-label">Nome do Curso:</label>
        <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $data->name ?? '') }}">
        @error('name')
            <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="image" class="form-label">Arquivo</label>
        <input type="file" id="image" name="image" class="form-input">
        @error('image')
            <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>



    <x-save-button />
</form>
