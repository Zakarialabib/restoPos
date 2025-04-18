<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\{form, layout, title};

layout('layouts.guest');

title(__('Login'));

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $user = Auth::user();

    if ($user->hasRole('admin')) {
        $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
    } elseif ($user->hasRole('manager')) {
        $this->redirectIntended(default: route('manager.dashboard', absolute: false), navigate: true);
    } else {
        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
};

?>

<div>
    <div class="container mx-auto">
        <div class="bg-white my-auto p-5 mt-4 rounded opacity-90">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <div class="flex flex-col gap-4">
                <h1 class="text-2xl font-semibold">{{ __('Login') }}</h1>
                <p class="text-sm text-gray-500">{{ __('Please enter your email and password to login') }}</p>
            </div>
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

                    <x-button primary type="submit" class="ms-3">
                        {{ __('Log in') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>