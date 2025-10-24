{{-- resources/views/reviews/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <form action="{{ route('follow.store', ['user' => 5]) }}" method="POST">
        @csrf
        <button type="submit" class="button">Follow</button>
    </form>
@endsection
