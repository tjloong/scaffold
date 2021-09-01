@extends('auth.layout')

@section('content')
	<div class="min-h-screen relative px-4 py-4 md:py-20">
		<div class="absolute inset-0">
			<img src="/storage/img/{{ config('scaffold.web.blob_bg') }}" class="w-full h-full object-cover object-center opacity-50">
		</div>

		<div class="relative max-w-md mx-auto">
			<a href="/" class="mb-4">
				<img src="/storage/img/{{ config('scaffold.web.logo') }}" class="w-20 mb-6">
			</a>

			<form class="mb-6" method="POST" action="{{ route('register') }}">
				@csrf

				@if (request()->ref)
					<input type="hidden" name="ref" value="{{ request()->ref }}">
				@endif

				<div class="p-5 bg-white shadow-lg rounded-md border border-gray-50 md:p-10">
					<div class="text-2xl font-bold mb-8 text-gray-600">
						Create your account
					</div>

					<div class="field-input">
						<label class="required">Your Full Name</label>
						<input type="text" class="form-input" name="name" value="{{ old('name') }}" required>
					</div>

					<div class="field-input">
						<label class="required">Login Email</label>
						<input type="text" class="form-input" name="email" value="{{ old('email') }}" required>
					</div>

					<div class="field-input">
						<label class="required">Login Password</label>
						<x-scaffold-component::password-input name="password" required/>
					</div>

	
					<div class="mt-6 mb-3">
						<x-scaffold-component::checkbox name="agree_tnc" color="teal">
							<div class="text-gray-500">
								By signing up, I have read and agreed to the app's
								<a href="/terms" target="_blank" class="text-blue-500 font-medium">
									terms and conditions
								</a> and 
								<a href="/privacy" target="_blank" class="text-blue-500 font-medium">
									privacy policy
								</a>.
							</div>
						</x-scaffold-component::checkbox>
					</div>

					<div class="mb-6">
						<x-scaffold-component::checkbox name="agree_marketing" color="teal" checked>
							<div class="text-gray-500">
								I agree to be part of the app's database for future newsletter, marketing and promotions opportunities.
							</div>
						</x-scaffold-component::checkbox>
					</div>

					@if ($errors->any())
						<div class="bg-red-100 text-red-800 p-4 rounded my-4 text-sm">
							<ul>
								@foreach ($errors->all() as $error)
									<li class="flex items-center">
										<icon name="close" size="sm" class="mr-1 flex-shrink-0"></icon>
										{{ $error }}
									</li>
								@endforeach
							</ul>
						</div>
					@endif

					<button submit class="py-2.5 text-center font-medium w-full bg-blue-500 text-white rounded-md mb-4">
						Create Account
					</button>

					<div class="text-center text-sm">
						Have an account? 
						<a href="/login" class="text-blue-500 font-medium">
							Sign In
						</a>
					</div>
				</div>
			</form>

			@include('auth.footer')
		</div>
	</div>
@endsection
