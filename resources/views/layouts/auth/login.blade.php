@extends('layouts.auth.index')
@section('container')
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <form class="form w-100" novalidate="novalidate" id="formLogin" data-kt-redirect-url="{{ env('APP_URL') }}" method="post" action="{{ route('auth.login') }}">
            @csrf
            <div class="text-center mb-15">
                <img alt="Logo" src="assets/media/logos/logo-1.svg" class="h-40px" />
                <h1 class="text-dark mt-12 mb-3">Sign In | Staycation Apps</h1>
                <h4 class="text-muted mt-2 mb-3">Welcome to staycation application</h4>
            </div>
            <div class="fv-row mb-15">
                <label class="form-label fs-6 fw-bolder text-dark">Email or Username</label>
                <input class="form-control form-control-lg form-control-solid" type="text" name="email_username" autocomplete="off" />
            </div>
            <div class="fv-row mb-12">
                <div class="d-flex flex-stack mb-2">
                    <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                    <a href="{{ route('auth.forgot-password') }}" class="link-primary fs-6 fw-bolder">Forgot Password ?</a>
                </div>
                <input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off" />
            </div>
            <div class="text-center">
                <button type="submit" id="btnLogin" class="btn btn-lg btn-primary w-100 mb-5">Login</button>
                <div class="text-center text-muted text-uppercase fw-bolder mb-5">or</div>
                <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
                <img alt="Logo" src="assets/media/svg/brand-logos/google-icon.svg" class="h-20px me-3" />Continue with Google</a>
                <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
                <img alt="Logo" src="assets/media/svg/brand-logos/facebook-4.svg" class="h-20px me-3" />Continue with Facebook</a>
                <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100">
                <img alt="Logo" src="assets/media/svg/brand-logos/apple-black.svg" class="h-20px me-3" />Continue with Apple</a>
            </div>
        </form>
    </div>
</div>
<div class="d-flex flex-center flex-column-auto">
    <div class="d-flex align-items-center fw-bold fs-6">
        <a href="https://keenthemes.com" class="text-muted text-hover-primary px-2">About</a>
        <a href="mailto:support@keenthemes.com" class="text-muted text-hover-primary px-2">Contact</a>
        <a href="https://1.envato.market/EA4JP" class="text-muted text-hover-primary px-2">Contact Us</a>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    $('#btnSimpan').on('click', function(e) {
        e.preventDefault();

        loading.block();
        $('#formLogin').submit();
    });
</script>
@endpush
@endsection
