<div class="modal flex-center" x-show="$store.modals.register" x-transition.opacity.duration.300ms>
    <div class="modal-card bg-background p-[20px] w-[380px]">
        <div class="flex justify-end">
            <button @click="toggleRegisterModal()">
                <x-css-close class="h-[24px] w-[24px] fill-gray-400 hover:fill-gray-800" />
            </button>
        </div>
        <div class="flex flex-col gap-[20px]">
            <div class="flex-center w-full text-heading-lg font-semibold">
                Creat an Account
            </div>
            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-[20px] px-[20px]">
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
                <div class="flex-center rounded-full bg-gray-200 p-1 h-[40px]">
                    <x-nav-item href="#">
                        <img src="{{ asset('images/logos/facebook.svg') }}" alt="Facebook Logo"
                            class="h-[24px] w-[24px]">
                    </x-nav-item>
                    <x-nav-item href="#">
                        <img src="{{ asset('images/logos/google.svg') }}" alt="Google Logo" class="h-[24px] w-[24px]">
                    </x-nav-item>
                    <x-nav-item href="#">
                        <img src="{{ asset('images/logos/apple.svg') }}" alt="Apple Logo" class="h-[24px] w-[24px]">
                    </x-nav-item>
                </div>
            </div>
            <div class="flex-center text-subtitle-md font-medium gap-1">
                Already a member?
                <a href="{{ url()->current() }}?login=1" class="text-primary">Log In</a>
            </div>
        </div>
    </div>
</div>
