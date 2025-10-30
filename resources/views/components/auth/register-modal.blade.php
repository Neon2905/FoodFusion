@props([
    'show' => '$store.modals.register',
    'onClose' => 'toggleRegisterModal()',
    'onLogin' => 'toggleLoginModal()',
])

{{-- TODO: add transition animation --}}
<template x-if="{{ $show }}" x-transition>
    <div class="modal flex-center" x-data="registerModal()" x-cloak>
        <div class="card bg-background p-5 w-95">
            <div class="flex justify-end">
                <button @click="{{ $onClose }}" x-ref="closeButton">
                    <x-css-close class="h-6 w-6 fill-gray-400 hover:fill-gray-800" />
                </button>
            </div>
            <div class="flex flex-col gap-5">
                <div class="flex-center w-full text-heading-lg font-semibold">
                    Create an Account
                </div>
                <div class="flex flex-col gap-5 px-5">
                    <div class="flex flex-col gap-2">
                        <label for="firstname" class="text-body-lg font-bold">
                            First Name
                        </label>
                        <input type="text" x-model="firstName" name="firstname" id="firstname" required
                            class="input-box">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="lastname" class="text-body-lg font-bold">
                            Last Name
                        </label>
                        <input type="text" x-model="lastName" name="lastname" id="lastname" required
                            class="input-box">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="email" class="text-body-lg font-bold">
                            Email
                        </label>
                        <input type="email" x-model="email" name="email" id="email" required class="input-box">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="password" class="text-body-lg font-bold">
                            Password
                        </label>
                        <input type="password" x-model="password" name="password" id="password" required
                            class="input-box">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="password_confirmation" class="text-body-lg font-bold">
                            Confirm Password
                        </label>
                        <input type="password" x-model="confirmPassword" name="password_confirmation"
                            id="password_confirmation" required class="input-box">
                    </div>
                    <div class="text-error text-body-sm" x-show="error">
                        <span x-text="error"></span>
                    </div>

                    <div class="flex flex-row items-start gap-3">
                        <input id="agree" type="checkbox" class="checkbox">
                        <label for="agree" class="text-subtitle-md font-medium text-muted">
                            By creating an account, I agree to the
                            <a href="#" class="text-secondary">Terms of Use</a> and have read our
                            <a href="{{ route('privacy') }}" class="text-secondary">Privacy Policy</a>.
                        </label>
                    </div>
                    <div class="flex flex-col px-3 gap-3">
                        <button @click="submit" type="submit" class="button bg-accent w-full h-10">
                            <span x-show="!isLoading">
                                Create Account
                            </span>
                            <x-loader x-show="isLoading" class="h-5 w-5 text-white"></x-loader>
                        </button>
                    </div>
                </div>
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
</template>

<script>
    function registerModal() {
        return {
            isLoading: false,

            firstName: '',
            lastName: '',
            email: '',
            password: '',
            confirmPassword: '',

            error: [],

            async submit() {
                try {
                    this.isLoading = true;
                    await axios.post('/register', {
                        first_name: this.firstName,
                        last_name: this.lastName,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.confirmPassword,
                    });
                    this.$refs.closeButton.click();
                    window.location.reload();
                } catch (error) {
                    this.error = error.response.data.message || 'An unexpected error occurred.';
                } finally {
                    this.isLoading = false;
                }
            }
        }
    }
</script>
