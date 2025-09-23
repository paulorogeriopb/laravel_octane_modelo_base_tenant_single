@props(['route', 'method' => 'POST', 'data' => null])

<form action="{{ $route }}" method="POST">
    @csrf

    @if ($method === 'PUT')
        @method('PUT')
    @endif

    <div class="mb-4">
        <label for="title" class="form-label">Título:</label>
        <input type="text" id="title" name="title" class="form-input"
            value="{{ old('title', $data->title ?? '') }}">
    </div>

    <div class="mb-4">
        <label for="name" class="form-label">Nome:</label>
        <input type="text" id="name" name="name" class="form-input"
            value="{{ old('name', $data->name ?? '') }}">
    </div>

    <x-save-button />
</form>
