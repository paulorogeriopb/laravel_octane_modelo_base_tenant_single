@props([
    'text' => __('mensagens.save'),
])

<div class="flex justify-end mt-5">
    <button type="submit" class="btn-default-md">
        {{ $text }}
    </button>
</div>
