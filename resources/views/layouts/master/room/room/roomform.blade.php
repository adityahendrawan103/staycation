@extends('layouts.main.index')
@section('caption','Master')
@section('title','Room')
@section('subtitle','')
@section('container')
<form id="formEntryRoom" name="formEntryRoom" autofill="off" autocomplete="off" method="post" action="{{ route('master.room.simpan-room') }}">
    @csrf
    <div class="card card-flush py-4">
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">{{ $title }}</span>
                <span class="text-muted mt-1 fw-bold fs-7">Form Room</span>
            </h3>
        </div>
        <div class="card-body pt-0">
            <div class="fv-row mt-4">
                <label class="form-label required">Kode Room:</label>
                <input id="inputKodeRoom" name="kode_room" type="text" class="form-control @if(str_contains(strtoupper(trim($title)), 'EDIT')) form-control-solid @endif"
                    placeholder="Input Kode Room" maxlength="10" oninput="this.value = this.value.toUpperCase()" required
                    value="@if(isset($kode_room)){{ strtoupper(trim($kode_room)) }}@else{{ old('kode_room') }}@endif"
                    @if(str_contains(strtoupper(trim($title)), 'EDIT')) readonly @endif>
                <span id="messageKodeRoom" class="invalid-feedback"></span>
            </div>
            <div class="fv-row">
                <div class="row">
                    <div class="col-lg-6 mt-8">
                        <label class="form-label required">Kode Tipe Ruangan:</label>
                        <div class="input-group">
                            <input id="inputKodeTipe" name="kode_tipe" type="text" class="form-control" style="cursor: pointer;" placeholder="Pilih Tipe Ruangan" maxlength="10" required readonly
                                value="@if(isset($kode_tipe)){{ trim($kode_tipe) }}@else{{ old('kode_tipe') }}@endif">
                            <button id="btnOptionRoomTipe" name="btnOptionRoomTipe" class="btn btn-icon btn-primary" type="button" data-toggle="modal" data-target="#optionModalRoomTipe">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-8">
                        <label class="form-label required">Nama Tipe Ruangan:</label>
                        <input id="inputNamaTipe" name="nama_tipe" type="text" class="form-control form-control-solid" placeholder="Nama Tipe Ruangan" required readonly
                            value="@if(isset($nama_tipe)){{ trim($nama_tipe) }}@else{{ old('nama_tipe') }}@endif">
                    </div>
                </div>
            </div>
            <div class="fv-row">
                <div class="row">
                    <div class="col-lg-6 mt-8">
                        <label class="form-label required">Lantai:</label>
                        <input id="inputLantai" name="lantai" type="number" class="form-control" placeholder="Input Lantai Ruangan" maxlength="2" required
                            value="@if(isset($lantai)){{ trim($lantai) }}@else{{ old('lantai') }}@endif">
                    </div>
                    <div class="col-lg-6 mt-8">
                        <label class="form-label required">Kapasitas:</label>
                        <input id="inputKapasitas" name="kapasitas" type="text" class="form-control" placeholder="Input Kapasitas Ruangan" required maxlength="4"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                            value="@if(isset($kapasitas)){{ trim($kapasitas) }}@else{{ old('kapasitas') }}@endif">
                    </div>
                </div>
            </div>
            <div class="fv-row">
                <div class="row">
                    <div class="col-lg-6 mt-8">
                        <label class="form-label required">Harga Long Time:</label>
                        <input id="inputLongtime" name="longtime" type="text" class="form-control" placeholder="Input Harga Long Time" required maxlength="13"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                            value="@if(isset($longtime)){{ trim($longtime) }}@else{{ old('longtime') }}@endif">
                    </div>
                    <div class="col-lg-6 mt-8">
                        <label class="form-label required">Harga Short Time:</label>
                        <input id="inputShortTime" name="shorttime" type="text" class="form-control" placeholder="Input Harga Short Time" required maxlength="13"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                            value="@if(isset($shorttime)){{ trim($shorttime) }}@else{{ old('shorttime') }}@endif">
                    </div>
                </div>
            </div>
            <div class="fv-row mt-8">
                <label class="form-label required">Status Room:</label>
                <select id="selectStatusRoom" name="status" class="form-select">
                    <option value="READY" @if(isset($status)) @if(strtoupper(trim($status)) == 'READY') selected @endif @else @if(strtoupper(trim(old('status'))) == 'READY') selected @endif @endif>READY</option>
                    <option value="MAINTENANCE" @if(isset($status)) @if(strtoupper(trim($status)) == 'MAINTENANCE') selected @endif @else @if(strtoupper(trim(old('status'))) == 'MAINTENANCE') selected @endif @endif>MAINTENANCE</option>
                </select>
            </div>
            <div class="fv-row mt-8">
                <label class="form-label">Keterangan:</label>
                <input id="inputKeterangan" name="keterangan" type="text" class="form-control" placeholder="Input Keterangan" maxlength="100"
                    value="@if(isset($keterangan)){{ trim($keterangan) }}@else{{ old('keterangan') }}@endif">
            </div>
        </div>
    </div>

    <div class="fv-row mt-8">
        <div class="card card-flush py-4">
            <div class="card-header">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Detail Fasilitas</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Daftar fasilitas ruangan</span>
                </h3>
                <div class="card-toolbar">
                    <button id="btnTambahFasilitas" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCheckListFasilitas">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M11 13H7C6.4 13 6 12.6 6 12C6 11.4 6.4 11 7 11H11V13ZM17 11H13V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"></path>
                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM17 11H13V7C13 6.4 12.6 6 12 6C11.4 6 11 6.4 11 7V11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"></path>
                            </svg>
                        </span>Tambah
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="fv-row mt-4">
                    <div id="contentTableDetailRoom"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-flush p-4 mt-8">
        <div class="d-flex justify-content-end">
            <button id="btnSimpan" name="btnSimpan" type="submit" class="btn btn-primary me-2">
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
    </div>
</form>

@include('layouts.option.option.optionroomtipe')
@include('layouts.option.checklist.checklistfasilitas')

@push('scripts')
<script type="text/javascript">
    const url = {
        'cek_kode_room': "{{ route('master.room.cek-kode-room') }}",
        'option_roomtipe': "{{ route('option.option-room-tipe') }}",
        'option_fasilitas_checklist': "{{ route('master.room.detail.check-list-room-detail') }}",
        'daftar_detail_room': "{{ route('master.room.detail.daftar-room-detail') }}",
        'simpan_fasilitas': "{{ route('master.room.detail.simpan-room-detail') }}",
        'hapus_fasilitas': "{{ route('master.room.detail.hapus-room-detail') }}",
    }
</script>
<script src="{{ asset('assets/js/app/custom/autonumeric.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/option/optionroomtipe.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/option/checklist/checklistfasilitas.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/master/room/roomdetail.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/master/room/room.js') }}?v={{ time() }}"></script>
@endpush
@endsection
