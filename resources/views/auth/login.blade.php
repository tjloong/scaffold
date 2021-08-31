@extends('auth.layout')

@section('content')
    <div class="min-h-screen relative px-4 py-4 md:py-16">
        <div class="absolute inset-0">
            <img src="/storage/img/{{ config('scaffold.view.blob_bg') }}" class="w-full h-full object-cover object-center opacity-50">
        </div>

        <div class="max-w-md mx-auto relative">
            <a class="block w-20 mb-4" href="/">
                <img src="/storage/img/{{ config('scaffold.view.logo') }}" class="w-full">
            </a>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="bg-white shadow-lg rounded-md p-5 border md:p-10">
                    <div class="text-2xl font-bold mb-6">
                        Sign in to your account
                    </div>

                    @error('login')
                        <div class="mb-4 text-sm bg-red-100 text-red-800 rounded p-4">
                            {{ $message }}
                        </div>
                    @elseif (session('status'))
                        <div class="mb-4 text-sm bg-green-100 text-green-800 rounded p-4">
                            {{ session('status') }}
                        </div>
                    @enderror

                    <div class="field-input">
                        <label>Email</label>
                        <input type="text" name="email" class="form-input" tabindex="1" required focus>
                    </div>

                    <div class="field-input">
                        <label class="flex justify-between">
                            <a href="/forgot-password" class="text-teal-500 text-xs float-right">
                                Forgot Password?
                            </a>

                            Password
                        </label>
                        <input type="password" name="password" class="form-input" tabindex="2" required>
                    </div>

                    <button submit class="py-2.5 font-medium text-center w-full bg-blue-500 text-white rounded-md">
                        Sign In
                    </button>
                </div>

                <div class="mt-6 text-sm">
                    Don't have an account? 

                    <a href="{{ route('register', ['ref' => 'login']) }}" class="text-blue-500 font-medium">
                        Sign Up
                    </a>
                </div>

                @include('auth.footer')
            </form>
        </div>
    </div>
@endsection
