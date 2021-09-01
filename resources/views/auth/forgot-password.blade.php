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

			<form method="POST" action="{{ route('password.forgot') }}">
				@csrf

				<div class="bg-white shadow-lg rounded-md p-5 border md:p-10">
					<div class="text-2xl font-bold mb-6">
						Reset Password Request
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

					<div class="field-input">
						<label>Your registered email</label>
						<input type="text" name="email" class="form-input" required focus>
					</div>

					<button submit class="py-2.5 w-full text-center font-medium bg-blue-500 text-white rounded-md">
						Send Request
					</button>
				</div>

				<div class="text-sm mt-4">
					<a href="/" class="inline-flex items-center">
						<box-icon name="left-arrow-alt"></box-icon>
						Back to home
					</a>
				</div>
			</form>
		</div>
	</div>
@endsection