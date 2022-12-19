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
    <body id="kt_body" class="auth-bg">
        <div class="d-flex flex-column flex-root">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="d-flex flex-column flex-column-fluid text-center p-10 py-lg-15">
                    <a href="{{ env('APP_URL') }}" class="mb-10 pt-lg-10">
                        <img alt="Logo" src="{{ asset('assets/media/logos/logo-1.svg') }}" class="h-40px mb-5">
                    </a>
                    <div class="pt-lg-10 mb-10">
                        <h1 class="fw-bolder fs-2qx text-gray-800 mb-7">Password Berhasil Diubah</h1>
                        <div class="fw-bold fs-3 text-muted mb-15">Password anda berhasil diubah.
                        <br>Log in ke akun anda dengan mengentry alamat
                        <br>email dan password baru anda
                        </div>
                        <div class="text-center p-15">
                            <h4 class="text-muted">Your new password:</h4>
                            <h1 class="text-dark mt-8 fs-3qx" style="letter-spacing: 20px;">{{ $new_password }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
