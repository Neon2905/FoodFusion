@props([
    'show' => '$store.modals.login',
    'onClose' => 'toggleLoginModal()',
    'onRegister' => 'toggleRegisterModal()',
])

<template x-if="{{ $show }}" x-transition>
    <div class="modal flex-center" x-data="loginModal()" x-cloak>
        <div class="card bg-background p-8 w-95">
            <div class="flex justify-end">
                <button @click="{{ $onClose }}" x-ref="closeButton">
                    <x-css-close class="h-6 w-6 fill-gray-400 hover:fill-gray-800" />
                </button>
            </div>
            <div class="flex flex-col gap-5">
                <div class="flex-center w-full text-heading-lg font-semibold">
                    Log In
                </div>
                <div class="flex flex-col gap-2">
                    <label for="email" class="text-body-lg font-bold">
                        Email
                    </label>
                    <input type="email" x-model="email" required class="input-box">
                </div>
                <div class="flex flex-col gap-2">
                    <label for="password" class="text-body-lg font-bold">
                        Password
                    </label>
                    <input type="password" x-model="password" required class="input-box">
                </div>
                <div class="flex-center flex-col px-[10px] gap-[10px]">
                    <div class="text-error text-body-sm">
                        <span x-text="error"></span>
                    </div>

                    <button class="button bg-primary w-full h-10" @click="submit">
                        <span x-show="!isLoading">
                            Log In
                        </span>
                        <x-loader x-show="isLoading" class="h-5 w-5 text-white"></x-loader>
                    </button>
                    <a href="" class="text-secondary text-body-md font-bold">
                        Forgot password?
                    </a>
                </div>
                <div class="flex-center flex-col gap-[5px]">
                    <div class="text-heading-sm font-bold">
                        OR CONTINUE WITH
                        <v>
                            <x-auth.oauth />
                    </div>
                    <p class="flex-center text-subtitle-md font-medium gap-1">
                        Don't have an account yet?
                        <button class="text-primary" @click="{{ $onRegister }}">Register</button>
                    </p>
                </div>
            </div>
        </div>
</template>
<script>
    function loginModal() {
        return {
            isLoading: false,

            email: '',
            password: '',

            error: [],

            async submit() {
                try {
                    this.isLoading = true;
                    await axios.post('/login', {
                        email: this.email,
                        password: this.password,
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
