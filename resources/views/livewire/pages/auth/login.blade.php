<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\{form, layout, title};

layout('layouts.guest');

title('Login');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
};

?>

<div>
    <div class="container mx-auto">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <div class="flex flex-col gap-4">
            <h1 class="text-2xl font-semibold">{{ __('Login') }}</h1>
            <p class="text-sm text-gray-500">{{ __('Please enter your email and password to login') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center justify-center">
            <form wire:submit="login">
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                        required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                        name="password" required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember" class="inline-flex items-center">
                        <input wire:model="form.remember" id="remember" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </a>
                    @endif

                    <x-primary-button class="ms-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
            <div>
                <!-- image -->
                <!-- <img src="{{ asset('images/login.png') }}" alt="login" class="w-full h-full object-cover"> -->
            </div>
        </div>
    </div>
</div>