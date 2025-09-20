@extends('layouts.app')

@section('content')
    <div class="flex-center flex-col">
        <h2>Set Up Your Profile</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.setup.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Display Name</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', auth()->user()->name) }}" required maxlength="255">
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea name="bio" id="bio" class="form-control" maxlength="1000" rows="4">{{ old('bio', optional(auth()->user()->profile)->bio) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="avatar" class="form-label">Avatar (optional, max 2MB)</label>
                <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Save Profile</button>
        </form>
    </div>
@endsection
