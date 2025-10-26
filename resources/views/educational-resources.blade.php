@extends('layouts.app', ['title' => 'Educational Resources'])

@section('content')
    <div class="max-w-5xl mx-auto">
        <h1 class="text-display-sm mb-4">Educational Resources</h1>

        <div class="card p-4">
            <p class="text-muted">Resources on renewable energy topics (downloadable PDFs & infographics).</p>
            <ul class="mt-3 space-y-2">
                <li><a href="/downloads/renewable-infographic.pdf" class="text-primary">Renewable energy infographic (PDF)</a>
                </li>
            </ul>
        </div>
    </div>
@endsection
