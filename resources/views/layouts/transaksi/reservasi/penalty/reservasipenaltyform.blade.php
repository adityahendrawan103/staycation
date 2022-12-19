@extends('layouts.main.index')
@section('caption','Transaksi')
@section('title','Reservasi')
@section('subtitle','')
@section('container')
<form id="formReservasiPenalty" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" autocomplete="off" method="post" route="#">
    @csrf
    <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
        <div class="card card-flush py-4">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Data Reservasi</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Form Penalty Reservasi</span>
                    </h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex flex-column mt-4">
                    <div class="fv-row">
                        <label class="form-label">Kode Reservasi:</label>
                        <input id="inputKodeReservasi" name="kode_reservasi" type="text" class="form-control form-control-solid" placeholder="Input Kode Reservasi" required readonly
                            value="@if(isset($kode_reservasi)){{ strtoupper(trim($kode_reservasi)) }}@else{{ old('kode_reservasi') }}@endif">
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label">Room:
                            <span class="badge badge-light-primary fs-7 fw-boldest ms-2">{{ trim($nama_tipe) }}</span>
                        </label>
                        <input id="inputKodeRoom" name="kode_room" type="text" class="form-control form-control-solid" placeholder="Input Kode Room" required readonly
                            value="@if(isset($kode_room)){{ strtoupper(trim($kode_room)) }}@else{{ old('kode_room') }}@endif">
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label">Check In:</label>
                        <input id="inputTanggalCheckIn" name="tanggal_check_in" type="text" class="form-control form-control-solid" placeholder="Input Tanggal Check In" required readonly
                            value="@if(isset($tanggal_check_in)){{ strtoupper(trim($tanggal_check_in)) }}@else{{ old('tanggal_check_in') }}@endif">
                        <input id="inputJamCheckIn" name="jam_check_in" type="text" class="form-control form-control-solid mt-2" placeholder="Input Jam Check In" required readonly
                            value="@if(isset($jam_check_in)){{ strtoupper(trim($jam_check_in)) }}@else{{ old('jam_check_in') }}@endif">
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label">Check Out:</label>
                        <input id="inputTanggalCheckOut" name="tanggal_check_out" type="text" class="form-control form-control-solid" placeholder="Input Tanggal Check Out" required readonly
                            value="@if(isset($tanggal_check_out)){{ strtoupper(trim($tanggal_check_out)) }}@else{{ old('tanggal_check_out') }}@endif">
                        <input id="inputJamCheckOut" name="jam_check_out" type="text" class="form-control form-control-solid mt-2" placeholder="Input Jam Check Out" required readonly
                            value="@if(isset($jam_check_out)){{ strtoupper(trim($jam_check_out)) }}@else{{ old('jam_check_out') }}@endif">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
        <div class="card card-flush py-4">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Entry Item Penalty</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Entry data item penalty</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <button id="btnTambahItem" class="btn btn-primary">
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
                    <div class="table-responsive">
                        <div id="contentTableDetailReservasiPenalty"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex flex-stack flex-wrap">
                    <div class="align-items-start">
                    </div>
                    <div class="align-items-end">
                        <button id="btnSimpan" name="btnSimpan" type="button" value="simpan" class="btn btn-primary me-2">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"></path>
                                    <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"></path>
                                </svg>
                            </span>Simpan
                        </button>
                        <button id="btnBatal" name="btnBatal" type="button" class="btn btn-light btn-active-light-primary">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM18 12C18 11.4 17.6 11 17 11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H17C17.6 13 18 12.6 18 12Z" fill="currentColor"></path>
                                </svg>
                            </span>Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="modalEntryItem" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div id="modalContentItem" class="modal-content">
            <form id="formEntryItem" name="formEntryItem" autofill="off" autocomplete="off" method="post" action="#">
                @csrf
                <div class="modal-header">
                    <h3 id="modalTitleItem" class="modal-title"></h3>
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
                        <label class="form-label required">Kode Item:</label>
                        <div class="input-group">
                            <input id="inputKodeItem" name="kode_item" type="text" class="form-control" style="cursor: pointer;" placeholder="Pilih Kode Item" required readonly>
                            <button id="btnOptionItem" name="btnOptionItem" class="btn btn-icon btn-primary" type="button" data-toggle="modal" data-target="#optionModalItem">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label">Nama Item:</label>
                        <input id="inputNamaItem" name="nama_item" type="text" class="form-control form-control-solid" placeholder="Nama Item Penalty" readonly>
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label">Keterangan:</label>
                        <input id="inputKeterangan" name="keterangan" type="text" class="form-control" placeholder="Input Keterangan Penalty" maxlength="50">
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label required">Qty:</label>
                        <input id="inputQuantity" name="quantity" type="text" class="form-control" placeholder="Input Quantity" required
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label required">Denda:</label>
                        <input id="inputHargaDenda" name="denda" type="text" class="form-control" placeholder="Input Harga Denda" required
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnSimpanItem" name="btnSimpanItem" type="submit" class="btn btn-primary">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                            </svg>
                        </span>Simpan
                    </button>
                    <button id="btnCloseItem" name="btnCloseItem" type="button" class="btn btn-light btn-active-light-primary" data-bs-dismiss="modal">
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

@include('layouts.option.option.optionitem')

@push('scripts')
<script type="text/javascript">
    $("#tableDetailReservasi").DataTable({
        "scrollX": true,
        "ordering": false
    });
    const data = {
        'total_pembayaran': "@if(isset($total_pembayaran)){{ $total_pembayaran }}@else {{ old('total_pembayaran') }} @endif",
        'sisa_pembayaran': "@if(isset($sisa_pembayaran)){{ $sisa_pembayaran }}@else {{ old('sisa_pembayaran') }} @endif"
    }
    const url = {
        'option_item': "{{ route('option.option-item') }}",
        'daftar_reservasi_penalty': "{{ route('transaksi.reservasi.penalty.daftar-reservasi-penalty') }}",
        'daftar_detail_reservasi_penalty': "{{ route('transaksi.reservasi.penalty.detail.daftar-reservasi-penalty-detail') }}",
        'form_penalty_item': "{{ route('transaksi.reservasi.penalty.detail.form-reservasi-penalty-detail') }}",
        'simpan_penalty_item': "{{ route('transaksi.reservasi.penalty.detail.simpan-reservasi-penalty-detail') }}",
        'hapus_penalty_item': "{{ route('transaksi.reservasi.penalty.detail.hapus-reservasi-penalty-detail') }}",
        'simpan_reservasi_penalty': "{{ route('transaksi.reservasi.penalty.simpan-reservasi-penalty') }}",
    }
</script>
<script src="{{ asset('assets/js/app/custom/autonumeric.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/custom/format.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/option/optionitem.js') }}?v={{ time() }}"></script>

<script src="{{ asset('assets/js/app/transaksi/reservasi/penalty/reservasi-penalty-formdetail.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/transaksi/reservasi/penalty/reservasi-penalty-form.js') }}?v={{ time() }}"></script>
@endpush
@endsection
