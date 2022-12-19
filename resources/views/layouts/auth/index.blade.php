<!DOCTYPE html>
<html lang="en">
	<head>
        <base href="{{ env('APP_URL') }}">
		<title>Staycation Management System</title>
		<meta charset="utf-8" />
		<meta name="description" content="Staycation Management System" />
		<meta name="keywords" content="Staycation Management System" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Staycation Management System" />
		<meta property="og:url" content="{{ env('APP_URL') }}" />
		<meta property="og:site_name" content="Staycation Management System" />
		<link rel="canonical" href="{{ env('APP_URL') }}" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
	</head>
	<!--begin::Body-->
	<body id="kt_body" class="bg-body">
        <div id="loading" style="position: fixed; width: 100%; height: 100%; overflow: hidden; background: rgba(0, 0, 0, 0.5); z-index: 9999; display: none;">
            <div class="d-flex flex-column flex-center w-100 h-100">
                <div id="loading-message" class="text-center">
                </div>
            </div>
        </div>
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed">
                @yield('container')
			</div>
		</div>

        @extends('components.swalfailed')
        @extends('components.swalsuccess')

		<script>var hostUrl = "{{ asset('assets') }}/";</script>
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/app/custom/loading.js') }}?v={{ time() }}"></script>

        @stack('scripts')
	</body>
</html>
