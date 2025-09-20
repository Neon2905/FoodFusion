@extends('layouts.app')

@section('content')
    <div class="flex-center">
        <x-auth.login-modal :show="true" onClose="redirect('/')" onRegister="redirect('/register')" />
    </div>
@endsection
