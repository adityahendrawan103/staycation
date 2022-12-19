@extends('layouts.main.index')
@section('caption','Master')
@section('title','Karyawan')
@section('subtitle','')
@section('container')
<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 pt-7">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">{{ $title }}</span>
            <span class="text-muted mt-1 fw-bold fs-7">Form Karyawan</span>
        </h3>
    </div>
    <form id="formEntryKaryawan" name="formEntryKaryawan" autofill="off" autocomplete="off" enctype="multipart/form-data" method="post" action="{{ route('master.karyawan.karyawan.simpan-karyawan') }}">
        @csrf
        <div class="card-body p-9">
            <div class="fv-row">
                <label class="d-block fw-bold fs-6 mb-5">Foto Karyawan</label>
                <div class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url('{{ asset('assets/images/karyawan/Default.png') }}')">
                    @if(isset($foto))
                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $foto }}');"></div>
                    @else
                    <div class="image-input-wrapper w-125px h-125px" style="background-image: none;"></div>
                    @endif
                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change avatar">
                        <i class="bi bi-pencil-fill fs-7"></i>
                        <input type="file" name="foto" accept=".png, .jpg, .jpeg">
                        <input type="hidden" name="foto_remove" value="1">
                    </label>
                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel avatar">
                        <i class="bi bi-x fs-2"></i>
                    </span>
                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove avatar">
                        <i class="bi bi-x fs-2"></i>
                    </span>
                </div>
                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
            </div>
            <div class="fv-row mt-8">
                <label class="form-label required">Nomor Induk Karyawan:</label>
                <input id="inputNIK" name="nik" type="text" class="form-control @if(str_contains(strtoupper(trim($title)), 'EDIT')) form-control-solid @endif"
                    placeholder="Input Nomor Induk Karyawan" maxlength="30" oninput="this.value = this.value.toUpperCase()" required
                    value="@if(isset($nik)){{ strtoupper(trim($nik)) }}@else{{ old('nik') }}@endif"
                    @if(str_contains(strtoupper(trim($title)), 'EDIT')) readonly @endif>
                <span id="messageNIK" class="invalid-feedback"></span>
            </div>
            <div class="fv-row mt-8">
                <label class="form-label required">Nomor KTP:</label>
                <input id="inputNoKTP" name="no_ktp" type="text" class="form-control" placeholder="Input Nomor KTP" maxlength="16" required
                    value="@if(isset($no_ktp)){{ trim($no_ktp) }}@else{{ old('no_ktp') }}@endif">
                <span id="messageNoKTP" class="invalid-feedback"></span>
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
                        <label class="form-label required">Kode Jabatan:</label>
                        <div class="input-group">
                            <input id="inputKodeJabatan" name="kode_jabatan" type="text" class="form-control" style="cursor: pointer;" placeholder="Pilih Kode Jabatan" maxlength="10" required readonly
                                value="@if(isset($kode_jabatan)){{ trim($kode_jabatan) }}@else{{ old('kode_jabatan') }}@endif">
                            <button id="btnOptionJabatan" name="btnOptionJabatan" class="btn btn-icon btn-primary" type="button" data-toggle="modal" data-target="#optionModalJabatan">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-8">
                        <label class="form-label required">Keterangan:</label>
                        <input id="inputNamaJabatan" name="nama_jabatan" type="text" class="form-control form-control-solid" placeholder="Keterangan" required readonly
                            value="@if(isset($nama_jabatan)){{ trim($nama_jabatan) }}@else{{ old('nama_jabatan') }}@endif">
                    </div>
                </div>
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
                        <input id="inputTanggalLahir" name="tanggal_lahir" type="date" class="form-control" placeholder="yyyy-mm-dd" required
                            value="@if(isset($tanggal_lahir)){{ date_format(date_create($tanggal_lahir), 'Y-m-d') }}@else{{ date_format(date_create(old('tanggal_lahir')), 'Y-m-d') }}@endif">
                    </div>
                </div>
            </div>
            <div class="fv-row mt-8">
                <label class="form-label">Alamat:</label>
                <input id="inputAlamat" name="alamat" type="text" class="form-control" placeholder="Input Alamat" maxlength="50"
                    value="@if(isset($alamat)){{ trim($alamat) }}@else{{ old('alamat') }}@endif">
            </div>
            <div class="fv-row">
                <div class="row">
                    <div class="col-lg-6 mt-8">
                        <label class="form-label">RT:</label>
                        <input id="inputRT" name="rt" type="text" class="form-control" placeholder="Input Nomor RT" maxlength="3"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                            value="@if(isset($rt)){{ trim($rt) }}@else{{ old('rt') }}@endif">
                    </div>
                    <div class="col-lg-6 mt-8">
                        <label class="form-label">RW:</label>
                        <input id="inputRW" name="rw" type="text" class="form-control" placeholder="Input Nomor RW" maxlength="3"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                            value="@if(isset($rw)){{ trim($rw) }}@else{{ old('rw') }}@endif">
                    </div>
                </div>
            </div>
            <div class="fv-row">
                <div class="row">
                    <div class="col-lg-6 mt-8">
                        <label class="form-label">Kelurahan:</label>
                        <input id="inputKelurahan" name="kelurahan" type="text" class="form-control" placeholder="Input Kelurahan" maxlength="50"
                            value="@if(isset($kelurahan)){{ trim($kelurahan) }}@else{{ old('kelurahan') }}@endif">
                    </div>
                    <div class="col-lg-6 mt-8">
                        <label class="form-label">Kecamatan:</label>
                        <input id="inputKecamatan" name="kecamatan" type="text" class="form-control" placeholder="Input Kecamatan" maxlength="50"
                            value="@if(isset($kecamatan)){{ trim($kecamatan) }}@else{{ old('kecamatan') }}@endif">
                    </div>
                </div>
            </div>
            <div class="fv-row">
                <div class="row">
                    <div class="col-lg-6 mt-8">
                        <label class="form-label">Kabupaten:</label>
                        <input id="inputKabupaten" name="kabupaten" type="text" class="form-control" placeholder="Input Kabupaten" maxlength="50"
                            value="@if(isset($kabupaten)){{ trim($kabupaten) }}@else{{ old('kabupaten') }}@endif">
                    </div>
                    <div class="col-lg-6 mt-8">
                        <label class="form-label">Provinsi:</label>
                        <input id="inputProvinsi" name="provinsi" type="text" class="form-control" placeholder="Input Provinsi" maxlength="50"
                            value="@if(isset($provinsi)){{ trim($provinsi) }}@else{{ old('provinsi') }}@endif">
                    </div>
                </div>
            </div>
            <div class="fv-row mt-8">
                <label class="form-label">Agama:</label>
                <select id="selectAgama" name="agama" class="form-select">
                    <option value="Islam" @if(isset($agama)) @if(strtoupper(trim($agama)) == 'ISLAM') selected @endif @else @if(strtoupper(trim(old('agama'))) == 'ISLAM') selected @endif @endif>Islam</option>
                    <option value="Kristen" @if(isset($agama)) @if(strtoupper(trim($agama)) == 'KRISTEN') selected @endif @else @if(strtoupper(trim(old('agama'))) == 'KRISTEN') selected @endif @endif>Kristen</option>
                    <option value="Katolik" @if(isset($agama)) @if(strtoupper(trim($agama)) == 'KATOLIK') selected @endif @else @if(strtoupper(trim(old('agama'))) == 'KATOLIK') selected @endif @endif>Katolik</option>
                    <option value="Budha" @if(isset($agama)) @if(strtoupper(trim($agama)) == 'BUDHA') selected @endif @else @if(strtoupper(trim(old('agama'))) == 'BUDHA') selected @endif @endif>Budha</option>
                    <option value="Hindu" @if(isset($agama)) @if(strtoupper(trim($agama)) == 'HINDU') selected @endif @else @if(strtoupper(trim(old('agama'))) == 'HINDU') selected @endif @endif>Hindu</option>
                    <option value="Konghuchu" @if(isset($agama)) @if(strtoupper(trim($agama)) == 'KONGHUCHU') selected @endif @else @if(strtoupper(trim(old('agama'))) == 'KONGHUCHU') selected @endif @endif>Konghuchu</option>
                </select>
            </div>
            <div class="fv-row mt-8">
                <label class="form-label">Telepon:</label>
                <input id="inputTelepon" name="telepon" type="text" class="form-control" placeholder="Input Telepon" maxlength="30" required
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                    value="@if(isset($telepon)){{ trim($telepon) }}@else{{ old('telepon') }}@endif">
            </div>
            <div class="fv-row mt-8">
                <label class="form-label required">Status Karyawan:</label>
                <select id="selectStatus" name="status" class="form-select">
                    <option value="Aktif" @if(isset($status)) @if(strtoupper(trim($status)) == 'AKTIF') selected @endif @else @if(strtoupper(trim(old('status'))) == 'AKTIF') selected @endif @endif>Aktif</option>
                    <option value="Resign" @if(isset($status)) @if(strtoupper(trim($status)) == 'RESIGN') selected @endif @else @if(strtoupper(trim(old('status'))) == 'RESIGN') selected @endif @endif>Resign</option>
                </select>
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

@include('layouts.option.option.optionjabatan')

@push('scripts')
<script type="text/javascript">
    const url = {
        'cek_nik': "{{ route('master.karyawan.karyawan.cek-nik-karyawan') }}",
        'cek_ktp': "{{ route('master.karyawan.karyawan.cek-ktp-karyawan') }}",
        'option_jabatan': "{{ route('option.option-jabatan') }}",
    }
</script>
<script src="{{ asset('assets/js/app/option/optionjabatan.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/master/karyawan/karyawan/karyawan.js') }}?v={{ time() }}"></script>
@endpush
@endsection
