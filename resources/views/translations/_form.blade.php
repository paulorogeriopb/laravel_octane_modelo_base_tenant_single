@props(['route', 'method' => 'POST', 'data' => null])

<form action="{{ $route }}" method="POST" novalidate>
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    <div class="mb-4">
        <label for="key" class="form-label">Chave:</label>
        <input type="text" id="key" name="key" class="form-input" value="{{ old('key', $data->key ?? '') }}"
            required>
        @error('key')
            <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="group" class="form-label">Grupo:</label>
        <input type="text" id="group" name="group" class="form-input"
            value="{{ old('group', $data->group ?? '') }}">
        @error('group')
            <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>

    <div class="flex flex-wrap gap-4">
        @foreach (['pt', 'en', 'es'] as $lang)
            <div class="flex-1 min-w-[200px]">
                <label for="text_{{ $lang }}" class="block mb-1 text-sm font-medium text-gray-700">
                    Texto ({{ strtoupper($lang) }}):
                </label>
                <textarea id="text_{{ $lang }}" name="text[{{ $lang }}]" rows="3" class="w-full form-textarea"
                    required>{{ old("text.$lang", $data?->getTranslation('text', $lang) ?? '') }}</textarea>
                @error('text.' . $lang)
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
        @endforeach
    </div>


    <x-save-button />
</form>
