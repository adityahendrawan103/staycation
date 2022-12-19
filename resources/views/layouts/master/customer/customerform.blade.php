@extends('layouts.main.index')
@section('caption','Master')
@section('title','Customer')
@section('subtitle','')
@section('container')
<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 pt-7">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">{{ $title }}</span>
            <span class="text-muted mt-1 fw-bold fs-7">Form Customer</span>
        </h3>
    </div>
    <form id="formEntryCustomer" name="formEntryCustomer" autofill="off" autocomplete="off" method="post" action="{{ route('master.customer.simpan-customer') }}">
        @csrf
        <div class="card-body p-9">
            <div class="fv-row">
                <label class="form-label required">No Identitas:</label>
                <input id="inputNoIdentitas" name="no_identitas" type="text" class="form-control @if(str_contains(strtoupper(trim($title)), 'EDIT')) form-control-solid @endif"
                    placeholder="Input No Identitas" maxlength="30" oninput="this.value = this.value.toUpperCase()" required
                    value="@if(isset($no_identitas)){{ strtoupper(trim($no_identitas)) }}@else{{ old('no_identitas') }}@endif"
                    @if(str_contains(strtoupper(trim($title)), 'EDIT')) readonly @endif>
                <span id="messageNoIdentitas" class="invalid-feedback"></span>
            </div>
            <div class="fv-row mt-8">
                <label class="form-label required">Jenis Identitas:</label>
                <select id="selectJenisIdentitas" name="jenis_identitas" class="form-select">
                    <option value="KTP" @if(isset($jenis_identitas)) @if(strtoupper(trim($jenis_identitas)) == 'KTP') selected @endif @else @if(strtoupper(trim(old('jenis_identitas'))) == 'KTP') selected @endif @endif>KTP</option>
                    <option value="SIM" @if(isset($jenis_identitas)) @if(strtoupper(trim($jenis_identitas)) == 'SIM') selected @endif @else @if(strtoupper(trim(old('jenis_identitas'))) == 'SIM') selected @endif @endif>SIM</option>
                    <option value="PASPOR" @if(isset($jenis_identitas)) @if(strtoupper(trim($jenis_identitas)) == 'PASPOR') selected @endif @else @if(strtoupper(trim(old('jenis_identitas'))) == 'PASPOR') selected @endif @endif>PASPOR</option>
                </select>
            </div>
            <div class="fv-row mt-8">
                <label class="form-label required">Nama Lengkap:</label>
                <input id="inputNama" name="nama" type="text" class="form-control" placeholder="Input Nama Lengkap" maxlength="50" required
                    value="@if(isset($nama)){{ trim($nama) }}@else{{ old('nama') }}@endif">
            </div>
            <div class="fv-row mt-8">
                <label class="form-label required">Jenis Kelamin:</label>
                <select id="selectJenisKelamin" name="jenis_kelamin" class="form-select">
                    <option value="Laki-Laki" @if(isset($jenis_kelamin)) @if(strtoupper(trim($jenis_kelamin)) == 'LAKI-LAKI') selected @endif @else @if(strtoupper(trim(old('jenis_kelamin'))) == 'LAKI-LAKI') selected @endif @endif>Laki-Laki</option>
                    <option value="Perempuan" @if(isset($jenis_kelamin)) @if(strtoupper(trim($jenis_kelamin)) == 'PEREMPUAN') selected @endif @else @if(strtoupper(trim(old('jenis_kelamin'))) == 'PEREMPUAN') selected @endif @endif>Perempuan</option>
                </select>
            </div>
            <div class="fv-row">
                <div class="row">
                    <div class="col-lg-6 mt-8">
                        <label class="form-label required">Tempat Lahir:</label>
                        <input id="inputTempatLahir" name="tempat_lahir" type="text" class="form-control" placeholder="Input Tanggal Lahir" maxlength="50" required
                            value="@if(isset($tempat_lahir)){{ trim($tempat_lahir) }}@else{{ old('tempat_lahir') }}@endif">
                    </div>
                    <div class="col-lg-6 mt-8">
                        <label class="form-label required">Tanggal Lahir:</label>
                        <input id="inputTanggalLahir" name="tanggal_lahir" class="form-control" placeholder="yyyy-mm-dd" required
                            value="@if(isset($tanggal_lahir)){{ date_format(date_create($tanggal_lahir), 'Y-m-d') }}@else{{ date_format(date_create(old('tanggal_lahir')), 'Y-m-d') }}@endif">
                    </div>
                </div>
            </div>
            <div class="fv-row mt-8">
                <label class="form-label">Alamat:</label>
                <input id="inputAlamat" name="alamat" type="text" class="form-control" placeholder="Input Alamat" maxlength="50"
                    value="@if(isset($alamat)){{ trim($alamat) }}@else{{ old('alamat') }}@endif">
            </div>
            <div class="fv-row mt-8">
                <label class="form-label">Kota/Kabupaten:</label>
                <input id="inputKota" name="kota" type="text" class="form-control" placeholder="Input Kota atau Kabupaten" maxlength="50"
                    value="@if(isset($kota)){{ trim($kota) }}@else{{ old('kota') }}@endif">
            </div>
            <div class="fv-row mt-8">
                <label class="form-label">Pekerjaan:</label>
                <input id="inputPekerjaan" name="pekerjaan" type="text" class="form-control" placeholder="Input Pekerjaan" maxlength="50"
                    value="@if(isset($pekerjaan)){{ trim($pekerjaan) }}@else{{ old('pekerjaan') }}@endif">
            </div>
            <div class="fv-row mt-8">
                <label class="form-label required">Telepon:</label>
                <input id="inputTelepon" name="telepon" type="text" class="form-control" placeholder="Input Telepon" maxlength="30"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required
                    value="@if(isset($telepon)){{ trim($telepon) }}@else{{ old('telepon') }}@endif">
            </div>
            <div class="fv-row mt-8">
                <label class="form-label required">Email:</label>
                <input id="inputEmail" name="email" type="text" class="form-control" placeholder="Input Email" maxlength="100" required
                    value="@if(isset($email)){{ trim($email) }}@else{{ old('email') }}@endif">
            </div>
        </div>
        <div class="card-footer">
            <button id="btnSimpan" name="btnSimpan" type="submit" class="btn btn-primary">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                        <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                    </svg>
                </span>Simpan
            </button>
            <button id="btnBatal" name="btnBatal" type="button" class="btn btn-light btn-active-light-primary">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM18 12C18 11.4 17.6 11 17 11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H17C17.6 13 18 12.6 18 12Z" fill="currentColor"/>
                    </svg>
                </span>Batal
            </button>
        </div>
    </form>
</div>


@push('scripts')
<script type="text/javascript">
    const url = {
        'cek_no_identitas': "{{ route('master.customer.cek-identitas-customer') }}"
    }
</script>
<script src="{{ asset('assets/js/app/master/customer/customer.js') }}?v={{ time() }}"></script>
@endpush
@endsection
