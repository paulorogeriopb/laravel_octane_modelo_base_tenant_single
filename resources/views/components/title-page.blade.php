@props([
    'title' => pageTitle(), // título padrão
    'breadcrumb' => null,
])

<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">{{ $title }}</h2>
        {!! $breadcrumb ?? renderBreadcrumb() !!}
    </div>
</div>
