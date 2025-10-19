@props([
    'show' => '$store.modals.register',
    'onClose' => 'toggleRegisterModal()',
    'onLogin' => 'toggleLoginModal()',
])

<div class="modal flex-center" x-show="{{ $show }}" x-transition.opacity.duration.300ms>
    <div class="card bg-background p-[20px] w-[380px]">
        <div class="flex justify-end">
            <button @click="{{ $onClose }}">
                <x-css-close class="h-[24px] w-[24px] fill-gray-400 hover:fill-gray-800" />
            </button>
        </div>
        <div class="flex flex-col gap-[20px]">
            <div class="flex-center w-full text-heading-lg font-semibold">
                Creat an Account
            </div>
            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-[20px] px-[20px]">
                @csrf
                <div class="flex flex-col gap-2">
                    <label for="email" class="text-body-lg font-bold">
                        Email
                    </label>
                    <input type="email" name="email" id="email" required class="input-box">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="password" class="text-body-lg font-bold">
                        Password
                    </label>
                    <input type="password" name="password" id="password" required class="input-box">
                </div>
                <div class="flex flex-col gap-2">
                    <label for="password_confirmation" class="text-body-lg font-bold">
                        Confirm Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="input-box">
                </div>
                @if ($errors->any())
                    <div class="text-red-600 text-body-sm">
                        {{ $errors->first('error') }}
                    </div>
                @endif
                <div class="flex flex-row items-start gap-[10px]">
                    <input id="agree" type="checkbox" class="checkbox">
                    <label for="agree" class="text-subtitle-md font-medium text-muted">
                        By creating an account, I agree to the
                        <a href="#" class="text-secondary">Terms of Use</a> and have read our
                        <a href="#" class="text-secondary">Privacy Policy</a>.
                    </label>
                </div>
                <div class="flex-center flex-col px-[10px] gap-[10px]">
                    <button type="submit" class="button bg-accent w-full h-10">
                        Create Account
                    </button>
                </div>
            </form>
            <div class="flex-center flex-col gap-[5px]">
                <div class="text-heading-sm font-bold">
                    OR CONTINUE WITH
                </div>
                <x-auth.oauth action="register" />
            </div>
            <div class="flex-center text-subtitle-md font-medium gap-1">
                Already a member?
                <button class="text-primary" @click="{{ $onLogin }}">Log In</button>
            </div>
        </div>
    </div>
</div>
