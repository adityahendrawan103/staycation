@extends('layouts.main.index')
@section('caption','Transaksi')
@section('title','Reservasi')
@section('subtitle','In House')
@section('container')
<form id="formEntryReservasi" name="formEntryReservasi" autofill="off" autocomplete="off" method="post" action="#">
    @csrf
    <div class="row">
        <div class="col-lg-4 mt-4">
            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Reservasi</span>
                            <span class="text-muted mt-1 fw-bold fs-7">Form Reservasi</span>
                        </h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="fv-row mt-4">
                        <label class="form-label">Kode Reservasi:</label>
                        <input id="inputKodeReservasi" name="kode_reservasi" type="text" class="form-control form-control-solid" placeholder="Input Kode Reservasi" required readonly
                            value="@if(isset($kode_reservasi)){{ strtoupper(trim($kode_reservasi)) }}@else{{ old('kode_reservasi') }}@endif">
                        <input id="inputNamaPlatform" name="nama_platform" type="text" class="form-control form-control-solid mt-2" placeholder="Nama Platform" readonly
                            value="@if(isset($nama_platform)){{ trim($nama_platform) }}@else{{ old('nama_platform') }}@endif">
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label">Customer:</label>
                        <input id="inputNoIdentitas" name="no_identitas" type="text" class="form-control form-control-solid" placeholder="No Identitas" required readonly
                            value="@if(isset($no_identitas)){{ strtoupper(trim($no_identitas)) }}@else{{ old('no_identitas') }}@endif">
                        <input id="inputNamaCustomer" name="nama_customer" type="text" class="form-control form-control-solid mt-2" placeholder="Nama Customer" required readonly
                            value="@if(isset($nama_customer)){{ strtoupper(trim($nama_customer)) }}@else{{ old('nama_customer') }}@endif">
                        <input id="inputKotaCustomer" name="kota_customer" type="text" class="form-control form-control-solid mt-2" placeholder="Kota Customer" required readonly
                            value="@if(isset($kota_customer)){{ strtoupper(trim($kota_customer)) }}@else{{ old('kota_customer') }}@endif">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-4">
            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Room</span>
                            <span class="text-muted mt-1 fw-bold fs-7">Form Edit Room</span>
                        </h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="fv-row mt-4">
                        <label class="form-label">Tipe Room:</label>
                        <input id="inputNamaTipe" name="nama_tipe" type="text" class="form-control form-control-solid" placeholder="Tipe Ruangan" readonly
                            value="@if(isset($nama_tipe)){{ trim($nama_tipe) }}@else{{ old('nama_tipe') }}@endif">
                        <input id="inputKodeRoom" name="kode_room" type="text" class="form-control form-control-solid mt-2" placeholder="Kode Room" required readonly
                            value="@if(isset($kode_room)){{ trim($kode_room) }}@else{{ old('kode_room') }}@endif">
                    </div>
                    <div class="fv-row mt-6">
                        <div class="form-check form-switch form-check-custom form-check-solid col-lg-8 fv-row fv-plugins-icon-container">
                            <input id="inputStatusLongtime" name="status_longtime" class="form-check-input" type="checkbox" role="switch"
                                @if($status_longtime == 1) checked  @endif
                                @if($default_jam_reservasi == 0) disabled @endif>
                            <label class="form-check-label" for="inputStatusLongtime">Longtime</label>
                        </div>
                    </div>
                    <div class="fv-row mt-7">
                        <label class="form-label">Total:</label>
                        <input id="inputGrandTotalRoom" name="total_room" type="text" class="form-control form-control-solid" placeholder="Total Room" required readonly
                            value="@if(isset($total_room)){{ number_format($total_room) }}@else{{ number_format(old('total_room')) }}@endif">
                    </div>
                    <div class="fv-row">
                        <button id="btnEditReservasi" class="btn btn-sm btn-flex btn-primary fw-bolder mt-5">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                                    <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                                </svg>
                            </span>Edit Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-4">
            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Extends</span>
                            <span class="text-muted mt-1 fw-bold fs-7">Form Reservasi</span>
                        </h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="fv-row mt-4">
                        <label class="form-label">Check In:</label>
                        <input id="inputTanggalCheckIn" name="tanggal_check_in" type="text" placeholder="Tanggal Check In" required
                            class="form-control form-control-solid" readonly
                            value="@if(isset($tanggal_check_in)){{ trim($tanggal_check_in) }}@else{{ old('tanggal_check_in') }}@endif">
                        <input id="inputJamCheckIn" name="jam_check_in" type="text" placeholder="Jam Check In" required
                            class="form-control form-control-solid mt-2" readonly
                            value="@if(isset($jam_check_in)){{ trim($jam_check_in) }}@else{{ old('jam_check_in') }}@endif">
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label">Check Out:</label>
                        <input id="inputTanggalCheckOut" name="tanggal_check_out" type="text" placeholder="Tanggal Check Out" required
                            class="form-control form-control-solid" readonly
                            value="@if(isset($tanggal_check_out)){{ trim($tanggal_check_out) }}@else{{ old('tanggal_check_out') }}@endif">
                        <input id="inputJamCheckOut" name="jam_check_out" type="text" placeholder="Jam Check Out" required
                            class="form-control form-control-solid mt-2" readonly
                            value="@if(isset($jam_check_out)){{ trim($jam_check_out) }}@else{{ old('jam_check_out') }}@endif">
                    </div>
                    <div class="fv-row">
                        <button id="btnExtendReservasi" class="btn btn-sm btn-flex btn-primary fw-bolder mt-5">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                                    <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                                </svg>
                            </span>Edit Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-5 mb-xl-10 mt-8">
        <div class="card-header border-0 pt-7">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">Detail Layanan</span>
                <span class="text-muted mt-1 fw-bold fs-7">Daftar layanan reservasi</span>
            </h3>
            <div class="card-toolbar">
                <button id="btnTambahLayanan" class="btn btn-primary">
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
            <div class="fv-row mt-8">
                <div class="table-responsive">
                    <div id="contentTableDetailReservasi"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-5 mb-xl-10">
        <div class="card-body">
            <div class="fv-row">
                <label class="form-label">Catatan:</label>
                <input id="inputCatatan" name="catatan" type="text" class="form-control" maxlength="100"
                    placeholder="Input Catatan Reservasi" value="@if(isset($catatan)){{ trim($catatan) }}@else{{ old('catatan') }}@endif">
            </div>
            <div class="fv-row mt-8">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle table-nowrap mb-0">
                        <tbody class="border">
                            <tr class="fw-bolder text-muted">
                                <td colspan="5" class="min-w-200px fw-bolder text-muted text-end fs-6 ps-3 pe-3">Total (Room + Layanan)</td>
                                <td colspan="2" class="w-150px fw-bolder text-dark text-end fs-6 ps-3 pe-3">
                                    <input id="inputTotalRoomLayanan" name="total_room_layanan" type="text" class="form-control form-control-solid text-end" readonly value="0">
                                </td>
                            </tr>
                            <tr class="fw-bolder text-muted">
                                <td colspan="5" class="min-w-200px fw-bolder text-muted text-end fs-6 ps-3 pe-3">Biaya Lain-lain</td>
                                <td colspan="2" class="w-150px fw-bolder text-dark text-end fs-6 ps-3 pe-3">
                                    <input id="inputBiayaLain" name="biaya_lain" type="text" class="form-control text-end"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="13"
                                        value="@if(isset($biaya_lain)){{ number_format($biaya_lain) }}@else{{ number_format(old('biaya_lain')) }}@endif">
                                </td>
                            </tr>
                            <tr class="fw-bolder text-muted">
                                <td colspan="5" class="min-w-200px fw-bolder text-muted text-end fs-6 ps-3 pe-3">Grand Total</td>
                                <td colspan="2" class="w-150px fw-bolder text-dark text-end fs-6 ps-3 pe-3">
                                    <input id="inputGrandTotal" name="grand_total" type="text" class="form-control form-control-solid text-end" readonly
                                        value="@if(isset($grand_total)){{ number_format($grand_total) }}@else{{ number_format(old('grand_total')) }}@endif">
                                </td>
                            </tr>
                            <tr class="fw-bolder text-muted">
                                <td colspan="5" class="min-w-200px fw-bolder text-muted text-end fs-6 ps-3 pe-3">Pembayaran</td>
                                <td colspan="2" class="w-150px fw-bolder text-dark text-end fs-6 ps-3 pe-3">
                                    <input id="inputTotalPembayaran" name="total_pembayaran" type="text" class="form-control text-end"
                                        value="@if(isset($total_pembayaran)){{ number_format($total_pembayaran) }}@else{{ number_format(old('total_pembayaran')) }}@endif">
                                </td>
                            </tr>
                            <tr class="fw-bolder text-muted">
                                <td colspan="5" class="min-w-200px fw-bolder text-muted text-end fs-6 ps-3 pe-3">Sisa Pembayaran</td>
                                <td colspan="2" class="w-150px fw-bolder text-dark text-end fs-6 ps-3 pe-3">
                                    <input id="inputSisaPembayaran" name="sisa_pembayaran" type="text" class="form-control form-control-solid text-end" readonly
                                        value="@if(isset($sisa_pembayaran)){{ number_format($sisa_pembayaran) }}@else{{ number_format(old('sisa_pembayaran')) }}@endif">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex flex-stack flex-wrap">
                <div class="align-items-start">
                </div>
                <div class="align-items-end">
                    <button id="btnSimpan" name="btnSimpan" type="submit" value="simpan" class="btn btn-primary me-2">
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
        </div>
    </div>
</form>

<div id="modalEntryEditReservasi" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div id="modalContentEditReservasi" class="modal-content">
            <form id="formEntryEditReservasi" name="formEntryEditReservasi" autofill="off" autocomplete="off" method="post" action="{{ route('transaksi.reservasi.inhouse.simpan-edit-room-reservasi-inhouse', trim($kode_reservasi)) }}">
                @csrf
                <div class="modal-header">
                    <h3 id="modalTitleEditReservasi" class="modal-title"></h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-muted svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M6 19.7C5.7 19.7 5.5 19.6 5.3 19.4C4.9 19 4.9 18.4 5.3 18L18 5.3C18.4 4.9 19 4.9 19.4 5.3C19.8 5.7 19.8 6.29999 19.4 6.69999L6.7 19.4C6.5 19.6 6.3 19.7 6 19.7Z" fill="currentColor"/>
                                <path d="M18.8 19.7C18.5 19.7 18.3 19.6 18.1 19.4L5.40001 6.69999C5.00001 6.29999 5.00001 5.7 5.40001 5.3C5.80001 4.9 6.40001 4.9 6.80001 5.3L19.5 18C19.9 18.4 19.9 19 19.5 19.4C19.3 19.6 19 19.7 18.8 19.7Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="fv-row">
                        <label class="form-label">Kode Reservasi:</label>
                        <div class="input-group">
                            <input id="inputModalEditKodeReservasi" name="kode_reservasi" type="text" class="form-control form-control-solid" placeholder="Kode Reservasi" required readonly
                                value="@if(isset($kode_reservasi)){{ trim($kode_reservasi) }}@else{{ old('kode_reservasi') }}@endif">
                        </div>
                    </div>
                    <div class="fv-row mt-8">
                        <div class="row">
                            <label class="form-label required">Tipe Room:</label>
                            <div class="col-lg-6 mb-2">
                                <div class="input-group">
                                    <input id="inputModalEditKodeTipe" name="kode_tipe" type="text" class="form-control" style="cursor: pointer;" placeholder="Kode Tipe Room" required readonly
                                        value="@if(isset($kode_tipe)){{ trim($kode_tipe) }}@else{{ old('kode_tipe') }}@endif">
                                    <button id="btnModalOptionTipeRoom" name="btnOptionTipeRoom" class="btn btn-icon btn-primary" type="button" data-toggle="modal" data-target="#optionModalRoomTipe">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <div class="input-group">
                                    <input id="inputModalEditNamaTipe" name="nama_tipe" type="text" class="form-control form-control-solid" placeholder="Nama Tipe" readonly
                                        value="@if(isset($nama_tipe)){{ trim($nama_tipe) }}@else{{ old('nama_tipe') }}@endif">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row mt-6">
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <label class="form-label required">Room:</label>
                                <div class="input-group">
                                    <input id="inputModalEditKodeRoom" name="kode_room" type="text" class="form-control" placeholder="Kode Room" style="cursor: pointer;" required readonly
                                        value="@if(isset($kode_room)){{ trim($kode_room) }}@else{{ old('kode_room') }}@endif">
                                    <button id="btnModalOptionRoom" name="btnOptionRoom" class="btn btn-icon btn-primary" type="button" data-toggle="modal" data-target="#optionModalRoomReservasi">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label class="form-label">Harga:</label>
                                <input id="inputModalEditHargaRoom" name="harga_room" type="text" class="form-control form-control-solid text-end" placeholder="Harga Room" required readonly
                                    value="@if(isset($harga_room)){{ number_format($harga_room) }}@else{{ old('harga_room') }}@endif">
                            </div>
                        </div>
                    </div>
                    <div class="fv-row mt-6">
                        <label class="form-label">Lama Inap:</label>
                        <input id="inputModalEditLamaInap" name="lama_inap" type="text" class="form-control form-control-solid" placeholder="Harga Room" required readonly
                            value="@if(isset($lama_inap)){{ number_format($lama_inap) }}@else{{ old('lama_inap') }}@endif @if($status_longtime == 1) MALAM @else x / {{ $default_jam_reservasi }} JAM @endif">
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label required">Alasan:</label>
                        <div class="input-group">
                            <input id="inputModalEditAlasan" name="alasan" type="text" class="form-control" placeholder="Input Alasan Pemindahan" maxlength="50" required
                                value="@if(isset($alasan)){{ trim($alasan) }}@else{{ old('alasan') }}@endif">
                        </div>
                    </div>
                    <div class="fv-row mt-8">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-nowrap mb-0">
                                <tbody class="border">
                                    <tr class="fw-bolder text-muted">
                                        <td class="min-w-200px ps-3 pe-3 text-end">Harga Room</td>
                                        <td class="w-150px ps-3 pe-3 text-end" colspan="2">
                                            <input id="inputModalEditTotalRoom" name="sub_total_room" class="form-control form-control-solid text-end" readonly
                                                value="@if(isset($sub_total_room)){{ number_format($sub_total_room) }}@else{{ number_format(old('sub_total_room')) }}@endif">
                                        </td>
                                    </tr>
                                    <tr class="fw-bolder text-muted">
                                        <td class="min-w-200px ps-3 pe-3 text-end">Diskon (%)</td>
                                        <td class="w-100px ps-3 pe-3 text-end">
                                            <input id="inputModalEditDiskonRoomProsentase" name="diskon_room_prosentase" class="form-control text-end"
                                                value="@if(isset($diskon_room_prosentase)){{ number_format($diskon_room_prosentase, 2) }}@else{{ number_format(old('diskon_room_prosentase'), 2) }}@endif"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </td>
                                        <td class="w-200px ps-3 pe-3 text-end">
                                            <input id="inputModalEditDiscRoomNominal" name="diskon_room_nominal" class="form-control form-control-solid text-end" readonly
                                                value="@if(isset($diskon_room_nominal)){{ number_format($diskon_room_nominal) }}@else{{ number_format(old('diskon_room_nominal')) }}@endif">
                                        </td>
                                    </tr>
                                    <tr class="fw-bolder text-muted">
                                        <td class="min-w-200px ps-3 pe-3 text-end">PPN (%)</td>
                                        <td class="w-100px ps-3 pe-3 text-end">
                                            <input id="inputModalEditPPNRoomProsentase" name="ppn_room_prosentase" class="form-control form-control-solid text-end" readonly
                                                value="@if(isset($ppn_room_prosentase)){{ number_format($ppn_room_prosentase, 2) }}@else{{ number_format(old('ppn_room_prosentase'), 2) }}@endif"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </td>
                                        <td class="w-200px ps-3 pe-3 text-end">
                                            <input id="inputModalEditPPNRoomNominal" name="ppn_room_nominal" class="form-control form-control-solid text-end" readonly
                                                value="@if(isset($ppn_room_nominal)){{ number_format($ppn_room_nominal) }}@else{{ number_format(old('ppn_room_nominal')) }}@endif">
                                        </td>
                                    </tr>
                                    <tr class="fw-bolder text-muted">
                                        <td class="min-w-200px ps-3 pe-3 text-end">Total</td>
                                        <td class="w-150px ps-3 pe-3 text-end" colspan="2">
                                            <input id="inputModalEditGrandTotalRoom" name="grand_total_room" class="form-control form-control-solid text-end" readonly
                                                value="@if(isset($total_room)){{ number_format($total_room) }}@else{{ number_format(old('total_room')) }}@endif">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnSimpanEditReservasi" name="btnSimpanEditReservasi" type="submit" class="btn btn-primary">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                            </svg>
                        </span>Simpan
                    </button>
                    <button id="btnCloseEditReservasi" name="btnCloseEditReservasi" type="button" class="btn btn-light btn-active-light-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="4" y="11" width="12" height="2" rx="1" fill="currentColor"/>
                                <path d="M5.86875 11.6927L7.62435 10.2297C8.09457 9.83785 8.12683 9.12683 7.69401 8.69401C7.3043 8.3043 6.67836 8.28591 6.26643 8.65206L3.34084 11.2526C2.89332 11.6504 2.89332 12.3496 3.34084 12.7474L6.26643 15.3479C6.67836 15.7141 7.3043 15.6957 7.69401 15.306C8.12683 14.8732 8.09458 14.1621 7.62435 13.7703L5.86875 12.3073C5.67684 12.1474 5.67684 11.8526 5.86875 11.6927Z" fill="currentColor"/>
                                <path d="M8 5V6C8 6.55228 8.44772 7 9 7C9.55228 7 10 6.55228 10 6C10 5.44772 10.4477 5 11 5H18C18.5523 5 19 5.44772 19 6V18C19 18.5523 18.5523 19 18 19H11C10.4477 19 10 18.5523 10 18C10 17.4477 9.55228 17 9 17C8.44772 17 8 17.4477 8 18V19C8 20.1046 8.89543 21 10 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H10C8.89543 3 8 3.89543 8 5Z" fill="#C4C4C4"/>
                            </svg>
                        </span>Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalEntryExtendReservasi" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div id="modalContentExtendReservasi" class="modal-content">
            <form id="formEntryExtendReservasi" name="formEntryExtendReservasi" autofill="off" autocomplete="off" method="post" action="{{ route('transaksi.reservasi.inhouse.simpan-extend-room-reservasi-inhouse', trim($kode_reservasi)) }}">
                @csrf
                <div class="modal-header">
                    <h3 id="modalTitleExtendReservasi" class="modal-title"></h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-muted svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M6 19.7C5.7 19.7 5.5 19.6 5.3 19.4C4.9 19 4.9 18.4 5.3 18L18 5.3C18.4 4.9 19 4.9 19.4 5.3C19.8 5.7 19.8 6.29999 19.4 6.69999L6.7 19.4C6.5 19.6 6.3 19.7 6 19.7Z" fill="currentColor"/>
                                <path d="M18.8 19.7C18.5 19.7 18.3 19.6 18.1 19.4L5.40001 6.69999C5.00001 6.29999 5.00001 5.7 5.40001 5.3C5.80001 4.9 6.40001 4.9 6.80001 5.3L19.5 18C19.9 18.4 19.9 19 19.5 19.4C19.3 19.6 19 19.7 18.8 19.7Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="fv-row">
                        <label class="form-label">Kode Reservasi:</label>
                        <div class="input-group">
                            <input id="inputModalExtendKodeReservasi" name="kode_reservasi" type="text" class="form-control form-control-solid" placeholder="Kode Reservasi" required readonly
                                value="@if(isset($kode_reservasi)){{ trim($kode_reservasi) }}@else{{ old('kode_reservasi') }}@endif">
                        </div>
                    </div>
                    <div class="fv-row mt-8">
                        <div class="row">
                            <label class="form-label">Tipe Room:</label>
                            <div class="col-lg-6 mb-2">
                                <div class="input-group">
                                    <input id="inputModalExtendKodeTipe" name="kode_tipe" type="text" class="form-control form-control-solid" placeholder="Kode Tipe Room" required readonly
                                        value="@if(isset($kode_tipe)){{ trim($kode_tipe) }}@else{{ old('kode_tipe') }}@endif">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <div class="input-group">
                                    <input id="inputModalExtendNamaTipe" name="nama_tipe" type="text" class="form-control form-control-solid" placeholder="Nama Tipe" readonly
                                        value="@if(isset($nama_tipe)){{ trim($nama_tipe) }}@else{{ old('nama_tipe') }}@endif">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row mt-6">
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <label class="form-label">Room:</label>
                                <div class="input-group">
                                    <input id="inputModalExtendKodeRoom" name="kode_room" type="text" class="form-control form-control-solid" placeholder="Kode Room" required readonly
                                        value="@if(isset($kode_room)){{ trim($kode_room) }}@else{{ old('kode_room') }}@endif">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label class="form-label">Harga Room:</label>
                                <input id="inputModalExtendHargaRoom" name="harga_room" type="text" class="form-control form-control-solid text-end" placeholder="Harga Room" required readonly
                                    value="@if(isset($harga_room)){{ number_format($harga_room) }}@else{{ old('harga_room') }}@endif">
                            </div>
                        </div>
                    </div>
                    <div class="fv-row mt-8">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="form-label">Status Reservasi:</label>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-check form-switch form-check-custom form-check-solid col-lg-8 fv-row fv-plugins-icon-container">
                                    <input id="inputModalExtendStatusLongtime" name="status_longtime" class="form-check-input" type="checkbox" role="switch"
                                        @if($status_longtime == 1) checked  @endif
                                        @if($default_jam_reservasi == 0) disabled @endif>
                                    <label class="form-check-label" for="inputModalExtendStatusLongtime">Longtime</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row mt-8">
                        <div class="row">
                            <label class="form-label">Check In:</label>
                            <div class="col-lg-6 mb-2">
                                <div class="input-group">
                                    <input id="inputModalExtendTanggalCheckIn" name="tanggal_check_in" type="text" class="form-control form-control-solid" placeholder="Pilih Tanggal Check In" required readonly
                                        value="@if(isset($tanggal_check_in)){{ trim($tanggal_check_in) }}@else{{ old('tanggal_check_in') }}@endif">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <input id="inputModalExtendJamCheckIn" name="jam_check_in" type="text" class="form-control form-control-solid" placeholder="Input Jam Check In" required readonly
                                    value="@if(isset($jam_check_in)){{ trim($jam_check_in) }}@else{{ old('jam_check_in') }}@endif">
                            </div>
                        </div>
                    </div>
                    <div class="fv-row mt-6">
                        <div class="row">
                            <label class="form-label required">Check Out:</label>
                            <div class="col-lg-6 mb-2">
                                <div class="input-group">
                                    <input id="inputModalExtendTanggalCheckOut" name="tanggal_check_out" type="text" class="form-control" placeholder="Pilih Tanggal Check Out" required readonly
                                        value="@if(isset($tanggal_check_out)){{ trim($tanggal_check_out) }}@else{{ old('tanggal_check_out') }}@endif">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <input id="inputModalExtendJamCheckOut" name="jam_check_out" type="text" class="form-control" placeholder="Input Jam Check Out" required readonly
                                    value="@if(isset($jam_check_out)){{ trim($jam_check_out) }}@else{{ old('jam_check_out') }}@endif">
                            </div>
                        </div>
                    </div>
                    <div class="fv-row mt-6">
                        <div class="row">
                            <label class="form-label">Lama Inap:</label>
                            <input id="inputModalExtendLamaInap" name="lama_inap" type="text" class="form-control form-control-solid" placeholder="Harga Room" required readonly
                                value="@if(isset($lama_inap)){{ number_format($lama_inap) }}@else{{ old('lama_inap') }}@endif @if($status_longtime == 1) MALAM @else PER-{{ $default_jam_reservasi }} JAM @endif">
                        </div>
                    </div>
                    <div class="fv-row mt-8">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-nowrap mb-0">
                                <tbody class="border">
                                    <tr class="fw-bolder text-muted">
                                        <td class="min-w-200px ps-3 pe-3 text-end">Harga Room</td>
                                        <td class="w-150px ps-3 pe-3 text-end" colspan="2">
                                            <input id="inputModalExtendTotalRoom" name="sub_total_room" class="form-control form-control-solid text-end" readonly
                                                value="@if(isset($sub_total_room)){{ number_format($sub_total_room) }}@else{{ number_format(old('sub_total_room')) }}@endif">
                                        </td>
                                    </tr>
                                    <tr class="fw-bolder text-muted">
                                        <td class="min-w-200px ps-3 pe-3 text-end">Diskon (%)</td>
                                        <td class="w-100px ps-3 pe-3 text-end">
                                            <input id="inputModalExtendDiskonRoomProsentase" name="diskon_room_prosentase" class="form-control text-end"
                                                value="@if(isset($diskon_room_prosentase)){{ number_format($diskon_room_prosentase, 2) }}@else{{ number_format(old('diskon_room_prosentase'), 2) }}@endif"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </td>
                                        <td class="w-200px ps-3 pe-3 text-end">
                                            <input id="inputModalExtendDiscRoomNominal" name="diskon_room_nominal" class="form-control form-control-solid text-end" readonly
                                                value="@if(isset($diskon_room_nominal)){{ number_format($diskon_room_nominal) }}@else{{ number_format(old('diskon_room_nominal')) }}@endif">
                                        </td>
                                    </tr>
                                    <tr class="fw-bolder text-muted">
                                        <td class="min-w-200px ps-3 pe-3 text-end">PPN (%)</td>
                                        <td class="w-100px ps-3 pe-3 text-end">
                                            <input id="inputModalExtendPPNRoomProsentase" name="ppn_room_prosentase" class="form-control form-control-solid text-end" readonly
                                                value="@if(isset($ppn_room_prosentase)){{ number_format($ppn_room_prosentase, 2) }}@else{{ number_format(old('ppn_room_prosentase'), 2) }}@endif"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </td>
                                        <td class="w-200px ps-3 pe-3 text-end">
                                            <input id="inputModalExtendPPNRoomNominal" name="ppn_room_nominal" class="form-control form-control-solid text-end" readonly
                                                value="@if(isset($ppn_room_nominal)){{ number_format($ppn_room_nominal) }}@else{{ number_format(old('ppn_room_nominal')) }}@endif">
                                        </td>
                                    </tr>
                                    <tr class="fw-bolder text-muted">
                                        <td class="min-w-200px ps-3 pe-3 text-end">Total</td>
                                        <td class="w-150px ps-3 pe-3 text-end" colspan="2">
                                            <input id="inputModalExtendGrandTotalRoom" name="grand_total_room" class="form-control form-control-solid text-end" readonly
                                                value="@if(isset($total_room)){{ number_format($total_room) }}@else{{ number_format(old('total_room')) }}@endif">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnSimpanExtendReservasi" name="btnSimpanExtendReservasi" type="submit" class="btn btn-primary">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                            </svg>
                        </span>Simpan
                    </button>
                    <button id="btnCloseExtendReservasi" name="btnCloseExtendReservasi" type="button" class="btn btn-light btn-active-light-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="4" y="11" width="12" height="2" rx="1" fill="currentColor"/>
                                <path d="M5.86875 11.6927L7.62435 10.2297C8.09457 9.83785 8.12683 9.12683 7.69401 8.69401C7.3043 8.3043 6.67836 8.28591 6.26643 8.65206L3.34084 11.2526C2.89332 11.6504 2.89332 12.3496 3.34084 12.7474L6.26643 15.3479C6.67836 15.7141 7.3043 15.6957 7.69401 15.306C8.12683 14.8732 8.09458 14.1621 7.62435 13.7703L5.86875 12.3073C5.67684 12.1474 5.67684 11.8526 5.86875 11.6927Z" fill="currentColor"/>
                                <path d="M8 5V6C8 6.55228 8.44772 7 9 7C9.55228 7 10 6.55228 10 6C10 5.44772 10.4477 5 11 5H18C18.5523 5 19 5.44772 19 6V18C19 18.5523 18.5523 19 18 19H11C10.4477 19 10 18.5523 10 18C10 17.4477 9.55228 17 9 17C8.44772 17 8 17.4477 8 18V19C8 20.1046 8.89543 21 10 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H10C8.89543 3 8 3.89543 8 5Z" fill="#C4C4C4"/>
                            </svg>
                        </span>Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalEntryLayanan" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div id="modalContentLayanan" class="modal-content">
            <form id="formEntryLayanan" name="formEntryLayanan" autofill="off" autocomplete="off" method="post" action="#">
                @csrf
                <div class="modal-header">
                    <h3 id="modalTitleLayanan" class="modal-title"></h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-muted svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M6 19.7C5.7 19.7 5.5 19.6 5.3 19.4C4.9 19 4.9 18.4 5.3 18L18 5.3C18.4 4.9 19 4.9 19.4 5.3C19.8 5.7 19.8 6.29999 19.4 6.69999L6.7 19.4C6.5 19.6 6.3 19.7 6 19.7Z" fill="currentColor"/>
                                <path d="M18.8 19.7C18.5 19.7 18.3 19.6 18.1 19.4L5.40001 6.69999C5.00001 6.29999 5.00001 5.7 5.40001 5.3C5.80001 4.9 6.40001 4.9 6.80001 5.3L19.5 18C19.9 18.4 19.9 19 19.5 19.4C19.3 19.6 19 19.7 18.8 19.7Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="fv-row">
                        <label class="form-label required">Kode Layanan:</label>
                        <div class="input-group">
                            <input id="inputKodeLayanan" name="kode_layanan" type="text" class="form-control" style="cursor: pointer;" placeholder="Pilih Kode Layanan" required readonly
                                value="@if(isset($kode_layanan)){{ trim($kode_layanan) }}@else{{ old('kode_layanan') }}@endif">
                            <button id="btnOptionLayanan" name="btnOptionLayanan" class="btn btn-icon btn-primary" type="button" data-toggle="modal" data-target="#optionModalLayanan">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label">Nama Layanan:</label>
                        <input id="inputNamaLayanan" name="nama_layanan" type="text" class="form-control form-control-solid" placeholder="Nama Layanan" readonly>
                    </div>
                    <div class="fv-row">
                        <div class="row">
                            <div class="col-lg-6 mt-8">
                                <label class="form-label">Satuan:</label>
                                <input id="inputSatuanLayanan" name="satuan" type="text" class="form-control form-control-solid" placeholder="Satuan Layanan" readonly>
                            </div>
                            <div class="col-lg-6 mt-8">
                                <label class="form-label">Harga:</label>
                                <input id="inputHargaLayanan" name="harga" type="text" class="form-control form-control-solid" placeholder="Harga Layanan" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row">
                        <div class="row">
                            <div class="col-lg-6 mt-8">
                                <label class="form-label required">Diskon (%):</label>
                                <input id="inputDiskonLayanan" name="diskon" type="text" class="form-control" placeholder="Input Diskon (Prosentase)" required
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                            </div>
                            <div class="col-lg-6 mt-8">
                                <label class="form-label required">Jumlah:</label>
                                <input id="inputJumlahLayanan" name="jumlah" type="text" class="form-control" placeholder="Input Jumlah Quantity" required
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnSimpanLayanan" name="btnSimpanLayanan" type="submit" class="btn btn-primary">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                            </svg>
                        </span>Simpan
                    </button>
                    <button id="btnCloseLayanan" name="btnCloseLayanan" type="button" class="btn btn-light btn-active-light-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="4" y="11" width="12" height="2" rx="1" fill="currentColor"/>
                                <path d="M5.86875 11.6927L7.62435 10.2297C8.09457 9.83785 8.12683 9.12683 7.69401 8.69401C7.3043 8.3043 6.67836 8.28591 6.26643 8.65206L3.34084 11.2526C2.89332 11.6504 2.89332 12.3496 3.34084 12.7474L6.26643 15.3479C6.67836 15.7141 7.3043 15.6957 7.69401 15.306C8.12683 14.8732 8.09458 14.1621 7.62435 13.7703L5.86875 12.3073C5.67684 12.1474 5.67684 11.8526 5.86875 11.6927Z" fill="currentColor"/>
                                <path d="M8 5V6C8 6.55228 8.44772 7 9 7C9.55228 7 10 6.55228 10 6C10 5.44772 10.4477 5 11 5H18C18.5523 5 19 5.44772 19 6V18C19 18.5523 18.5523 19 18 19H11C10.4477 19 10 18.5523 10 18C10 17.4477 9.55228 17 9 17C8.44772 17 8 17.4477 8 18V19C8 20.1046 8.89543 21 10 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H10C8.89543 3 8 3.89543 8 5Z" fill="#C4C4C4"/>
                            </svg>
                        </span>Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



@include('layouts.option.option.optionroomreservasi')
@include('layouts.option.option.optionroomtipe')
@include('layouts.option.option.optionlayanan')

@push('scripts')
<script type="text/javascript">
    $("#tableDetailReservasi").DataTable({
        "scrollX": true,
        "ordering": false
    });
    const data = {
        'status_longtime': "{{ $status_longtime }}",
        'lama_inap': "{{ $lama_inap }}",
        'default_layanan_disc_prosentase': "@if(isset($default_layanan_disc_prosentase)){{ number_format($default_layanan_disc_prosentase, 2) }}@else {{ number_format(old('default_layanan_disc_prosentase'), 2) }} @endif",
        'default_layanan_ppn_prosentase': "@if(isset($default_layanan_ppn_prosentase)){{ number_format($default_layanan_ppn_prosentase, 2) }}@else {{ number_format(old('default_layanan_ppn_prosentase'), 2) }} @endif",
    }
    const url = {
        'daftar_reservasi': "{{ route('transaksi.reservasi.inhouse.daftar-reservasi-inhouse') }}",
        'daftar_detail_reservasi': "{{ route('transaksi.reservasi.checkin.detail.daftar-reservasi-detail') }}",
        'form_detail_reservasi': "{{ route('transaksi.reservasi.checkin.detail.form-reservasi-detail') }}",
        'option_roomtipe': "{{ route('option.option-room-tipe') }}",
        'option_layanan': "{{ route('option.option-layanan') }}",
        'option_room_reservasi': "{{ route('option.option-room-reservasi') }}",
        'simpan_layanan': "{{ route('transaksi.reservasi.checkin.detail.simpan-reservasi-detail') }}",
        'hapus_layanan': "{{ route('transaksi.reservasi.checkin.detail.hapus-reservasi-detail') }}",
        'simpan_reservasi_inhouse': "{{ route('transaksi.reservasi.inhouse.simpan-reservasi-inhouse') }}",
    }
</script>
<script src="{{ asset('assets/js/app/custom/autonumeric.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/custom/format.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/option/optionroomtipe.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/option/optionroomreservasi.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/option/optionlayanan.js') }}?v={{ time() }}"></script>

<script src="{{ asset('assets/js/app/transaksi/reservasi/inhouse/reservasi-inhouse-formdetail.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/transaksi/reservasi/inhouse/reservasi-inhouse-form.js') }}?v={{ time() }}"></script>
@endpush
@endsection
