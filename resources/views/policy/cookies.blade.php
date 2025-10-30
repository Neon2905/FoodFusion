@extends('layouts.app', ['title' => 'Cookie Policy'])

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <h1 class="text-2xl font-semibold mb-4">Cookie Policy</h1>

    <p class="mb-4 text-sm text-muted">Last updated: {{ date('Y-m-d') }}</p>

    <section class="mb-6">
        <h2 class="font-semibold">What are cookies?</h2>
        <p class="text-sm text-gray-700">Cookies are small text files placed on your device to remember preferences
            and enable site functionality.</p>
    </section>

    <section class="mb-6">
        <h2 class="font-semibold">How we use cookies</h2>
        <ul class="list-disc pl-5 text-sm text-gray-700">
            <li>Essential cookies to keep you logged in and secure.</li>
            <li>Preference cookies to remember UI choices.</li>
            <li>Analytics cookies to understand site usage and improve features.</li>
        </ul>
    </section>

    <section class="mb-6">
        <h2 class="font-semibold">Managing cookies</h2>
        <p class="text-sm text-gray-700">You can control cookies via your browser settings and clear cookies at any time.
            We also provide a cookie consent banner to opt in where required.</p>
    </section>

    <section class="mb-6">
        <h2 class="font-semibold">More information</h2>
        <p class="text-sm text-gray-700">If you need assistance, contact us at <a href="mailto:hello@foodfusion.example" class="text-primary">hello@foodfusion.example</a>.</p>
    </section>
</div>
@endsection
