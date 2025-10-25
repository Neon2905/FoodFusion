@props(['names' => []])

@if ($names ? $errors->has($names) : $errors->any())
    <div class="alert alert-warning text-error">
        <ul>
            @foreach ($errors->all() as $error)
                @if (empty($names) || collect($names)->contains(fn($n) => str_contains($error, $n)))
                    <li>{{ $error }}</li>
                @endif
            @endforeach
        </ul>
    </div>
@endif
