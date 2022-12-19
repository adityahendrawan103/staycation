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
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
		<!--end::Global Stylesheets Bundle-->
	</head>

	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed">
        <div id="loading" style="position: fixed; width: 100%; height: 100%; overflow: hidden; background: rgba(0, 0, 0, 0.5); z-index: 9999; display: none;">
            <div class="d-flex flex-column flex-center w-100 h-100">
                <div id="loading-message" class="text-center">
                </div>
            </div>
        </div>
		<div class="d-flex flex-column flex-root">
			<div class="page d-flex flex-row flex-column-fluid">
                @include('layouts.main.sidebarleft')
				<!--end::Aside-->
				<!--begin::Wrapper-->
                @include('layouts.main.headerfooter')
			</div>
		</div>
		<!--end::Root-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
			<span class="svg-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Scrolltop-->

        @extends('components.swalfailed')
        @extends('components.swalsuccess')

        <!--begin::Javascript-->
		<script>var hostUrl = "{{ asset('assets') }}/";</script>
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

		<script src="{{ asset('assets/js/app/custom/loading.js') }}?v={{ time() }}"></script>

        @stack('scripts')
        <script type="text/javascript">
        $('body').on('click', '.menu-item a', function(e) {
            loading.block();
        });
        </script>
	</body>
</html>
