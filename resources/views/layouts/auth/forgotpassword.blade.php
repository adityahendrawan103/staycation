@extends('layouts.auth.index')
@section('container')
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <form class="form w-100" novalidate="novalidate" id="formForgotPassword" data-kt-redirect-url="{{ env('APP_URL') }}" method="post" action="{{ route('auth.forgot-password-proses') }}">
            @csrf
            <img alt="Logo" src="assets/media/logos/logo-1.svg" class="h-40px mb-5">
            <div class="pt-lg-10 mb-10">
                <h4 class="fw-bolder fs-1 text-gray-800 mb-8">Verify Your Account</h4>
                <div class="fs-6 fw-bold text-muted mb-2">Inputkan alamat email atau username anda:</div>
                <div class="fv-row mb-8">
                    <input class="form-control form-control-lg form-control-solid w-100" type="text" name="email_username" autocomplete="off" />
                </div>
                <div class="text-center">
                    <button id="btnForgotPassword" class="btn btn-lg btn-primary fw-bolder" role="button">Send to email</a>
                </div>
            </div>
        </form>
        <div class="text-gray-700 fw-bold fs-4 pt-7 text-center">Kembali ke menu
            <a href="{{ route('auth.index') }}" class="text-primary fw-bolder">login</a>
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#btnForgotPassword').on('click', function(e) {
            e.preventDefault();

            loading.block();
            $('#formForgotPassword').submit();
        });
    });
</script>
@endpush
@endsection
