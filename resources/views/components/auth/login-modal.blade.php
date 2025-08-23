@if (request()->has('login'))
    <div class="modal flex-center">
        <div class="modal-card bg-background p-[20px] w-[380px]">
            <div class="flex justify-end">
                {{-- TODO: Fix this to render only on client-side [May be use JS for transitioning] Currently, it's taking soooooooo long to load!! --}}
                <a href="{{ url()->current() }}">
                    <x-css-close class="h-[24px] w-[24px] fill-gray-400 hover:fill-gray-800" />
                </a>
            </div>
            <div class="flex flex-col gap-[20px]">
                <div class="flex-center w-full text-heading-lg font-semibold">
                    Log In
                </div>
                <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-[20px] px-[20px]">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label for="email" class="text-body-lg font-bold">
                            Email
                        </label>
                        <input type="email" name="email" id="email" required class="input-box"
                            value="{{ old('email') }}">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="password" class="text-body-lg font-bold">
                            Password
                        </label>
                        <input type="password" name="password" id="password" required class="input-box">
                    </div>
                    <div class="flex-center flex-col px-[10px] gap-[10px]">
                        @if ($errors->any())
                            <div class="text-red-600 text-body-sm">
                                {{ $errors->first('error') }}
                            </div>
                        @endif
                        <button type="submit" class="button bg-primary w-full h-10">
                            Log In
                        </button>
                        <a href="" class="text-secondary text-body-md font-bold">
                            Forgot password?
                        </a>
                    </div>
                </form>
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
                            <img src="{{ asset('images/logos/google.svg') }}" alt="Google Logo"
                                class="h-[24px] w-[24px]">
                        </x-nav-item>
                        <x-nav-item href="#">
                            <img src="{{ asset('images/logos/apple.svg') }}" alt="Apple Logo" class="h-[24px] w-[24px]">
                        </x-nav-item>
                    </div>
                </div>
                <p class="flex-center text-subtitle-md font-medium gap-1">
                    Don't have an account yet?
                    <a href="{{ url()->current() }}?register=1" class="text-primary">Register</a>
                </p>
            </div>
        </div>
@endif
