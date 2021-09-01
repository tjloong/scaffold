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

			<form method="POST" action="{{ route('password.reset') }}">
				@csrf

				<div class="bg-white shadow-lg rounded-md p-5 border md:p-10">
					<div class="text-2xl font-bold mb-2">
						Reset Password
					</div>

					<div class="font-medium text-gray-500 mb-6">
						{{ request()->email }}
					</div>

					@if (session('status'))
						<div class="mb-4 text-sm bg-green-100 text-green-800 rounded p-4">
							{{ session('status') }}
						</div>
					@endif

					@if ($errors->any())
						<div class="mb-4 text-sm bg-red-100 text-red-800 rounded p-4">
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

					<input type="hidden" name="email" value="{{ request()->email }}">
					<input type="hidden" name="token" value="{{ request()->token }}">
					
					<div class="field-input">
						<label>New Password</label>
						<input type="password" name="password" class="form-input" required>
					</div>
					
					<div class="field-input">
						<label>Confirm Password</label>
						<input type="password" name="password_confirmation" class="form-input w-full" required>
					</div>

					<button submit class="py-2.5 text-center w-full font-medium bg-blue-500 text-white rounded-md">
						Reset Password
					</button>
				</div>

				<div class="text-sm mt-4">
					<a href="/" class="flex items-center">
						<box-icon name="left-arrow-alt"></box-icon> Back to home
					</a>
				</div>
			</form>
		</div>
	</div>
@endsection