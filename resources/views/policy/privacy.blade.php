@extends('layouts.app', ['title' => 'Privacy Policy'])

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <h1 class="text-2xl font-semibold mb-4">Privacy Policy</h1>

    <p class="mb-4 text-sm text-muted">Last updated: {{ date('Y-m-d') }}</p>

    <section class="mb-6">
        <h2 class="font-semibold">Overview</h2>
        <p class="text-sm text-gray-700">FoodFusion (“we”, “us”, “our”) is committed to protecting your privacy.
            This policy explains what personal data we collect, why we collect it, and how you can control it.</p>
    </section>

    <section class="mb-6">
        <h2 class="font-semibold">Information We Collect</h2>
        <ul class="list-disc pl-5 text-sm text-gray-700">
            <li>Account information you provide when registering (name, email, password).</li>
            <li>Profile details you choose to publish (username, bio, avatar, recipes).</li>
            <li>Usage data and technical information (IP address, device, cookies).</li>
        </ul>
    </section>

    <section class="mb-6">
        <h2 class="font-semibold">How We Use Data</h2>
        <p class="text-sm text-gray-700">We use your data to operate and improve the service, deliver features,
            communicate notifications, and personalize content. We do not sell your personal information.</p>
    </section>

    <section class="mb-6">
        <h2 class="font-semibold">Sharing & Third Parties</h2>
        <p class="text-sm text-gray-700">We may share data with service providers who help run the site (hosting,
            analytics, email). We disclose information when required by law.</p>
    </section>

    <section class="mb-6">
        <h2 class="font-semibold">Your Rights</h2>
        <p class="text-sm text-gray-700">You can access, correct, or request deletion of your account data by
            contacting us at the address below or using the account settings within the site.</p>
    </section>

    <section class="mb-6">
        <h2 class="font-semibold">Data Security</h2>
        <p class="text-sm text-gray-700">We implement reasonable security measures to protect your information,
            but no system is completely secure. Please keep your account credentials safe.</p>
    </section>

    <section class="mb-6">
        <h2 class="font-semibold">Contact</h2>
        <p class="text-sm text-gray-700">If you have questions about this policy, email <a href="mailto:hello@foodfusion.example" class="text-primary">hello@foodfusion.example</a>.</p>
    </section>
</div>
@endsection
