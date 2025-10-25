@extends('layouts.app')

@section('content')
    <div class="flex-center">
        <x-auth.register-modal :show="true" onClose="redirect('/')" onLogin="redirect('/login')" />
    </div>
@endsection
