@props([
    'show' => '$store.modals.login',
    'onClose' => 'toggleLoginModal()',
    'onRegister' => 'toggleRegisterModal()',
])

<div class="modal flex-center" x-show="{{ $show }}" x-transition.opacity.duration.300ms>
    <div class="card bg-background p-5 w-95">
        <div class="flex justify-end">
            <button @click="{{ $onClose }}">
                <x-css-close class="h-6 w-6 fill-gray-400 hover:fill-gray-800" />
            </button>
        </div>
        <div class="flex flex-col gap-5">
            <div class="flex-center w-full text-heading-lg font-semibold">
                Log In
            </div>
            <form x-data="loginModal()" x-ref="form" @submit.prevent="submit" method="POST"
                action="{{ route('login') }}" class="flex flex-col gap-5 px-5">
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
                    {{-- server-rendered errors (fallback) --}}
                    @if ($errors->any())
                        <div class="text-error text-body-sm">
                            {{ $errors->first('error') }}
                        </div>
                    @endif

                    {{-- client-side errors from fetch --}}
                    <div x-text="errorMessage" x-show="errorMessage" class="text-error text-body-sm"></div>

                    <button type="submit" class="button bg-primary w-full h-10" :disabled="loading">
                        <span x-show="!loading">Log In</span>
                        <span x-show="loading">Processing...</span>
                    </button>
                    <a href="" class="text-secondary text-body-md font-bold">
                        Forgot password?
                    </a>
                </div>
            </form>
            <div class="flex-center flex-col gap-[5px]">
                <div class="text-heading-sm font-bold">
                    OR CONTINUE WITH
                </div>
                <x-auth.oauth />
            </div>
            @if(session('success'))
                <div class="text-success text-body-sm">
                    {{ session('success') }}
                </div>
            @endif
            <p class="flex-center text-subtitle-md font-medium gap-1">
                Don't have an account yet?
                <button class="text-primary" @click="{{ $onRegister }}">Register</button>
            </p>
        </div>
    </div>
</div>

<script>
    function loginModal() {
        return {
            errorMessage: '',
            loading: false,
            async submit(e) {
                this.loading = true;
                this.errorMessage = null;
                const form = this.$refs.form;
                const url = form.action;
                const data = new FormData(form);

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: data,
                        credentials: 'same-origin'
                    });

                    // Try to parse JSON response (if any)
                    let payload = null;
                    try {
                        payload = await res.clone().json();
                    } catch (e) {}

                    if (!res.ok) {
                        // Show validation / error message if provided
                        this.errorMessage = (payload && (payload.message || payload.error)) || 'Login failed';
                        return;
                    }

                    // Success: run injected functions / callbacks
                    try {
                        // {{ $onClose }}
                    } catch (e) {
                        /* ignore if not defined */
                    }
                    // emit a global event for other code to hook into
                    window.dispatchEvent(new CustomEvent('login-success', {
                        detail: payload
                    }));
                    // call a conventional global callback if present
                    if (typeof window.onLoginSuccess === 'function') window.onLoginSuccess(payload);
                } catch (err) {
                    this.errorMessage = 'Network error. Please try again.';
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
