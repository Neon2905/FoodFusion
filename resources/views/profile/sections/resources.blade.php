@props(['profile'])

<div class="card p-6">
    <h2 class="text-heading-lg">Resources</h2>
    <p class="text-muted">Links, PDFs or other resources shared by {{ $profile->name }}.</p>

    @php $resources = optional($profile)->resources()->latest()->take(12)->get() ?? collect(); @endphp

    @if($resources->isEmpty())
        <div class="text-muted p-6">No resources yet.</div>
    @else
        <ul class="mt-4 space-y-2">
            @foreach($resources as $res)
                <li>
                    <a href="{{ $res->url ?? '#' }}" class="text-primary">{{ $res->title ?? $res->filename ?? 'Resource' }}</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
