@php
    /** @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection|iterable $sections */
    $themes = $themes ?? app(\Modules\Website\Classes\Support\ThemeManager::class);
@endphp
<div class="cm-page-body">
    @forelse ($sections as $section)
        @include($themes->sectionViewName($section->type->value), ['section' => $section, 'payload' => $section->payload ?? []])
    @empty
        <div class="container cm-empty-page">
            <p>Content for this page will appear here once published in the Website admin.</p>
            <a href="{{ route('website.booking') }}" class="btn btn-primary btn-sm">Book Appointment</a>
        </div>
    @endforelse
</div>
