@extends('layouts.main.index')
@section('caption','Transaksi')
@section('title','Reservasi')
@section('subtitle','Check Out')
@section('container')
@csrf
<div class="card">
    <div class="card-body p-lg-20">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                <div class="mt-n1">
                    <div class="d-flex flex-stack pb-10">
                        <img alt="Logo" src="{{ trim($data->company->logo) }}">
                        <button id="btnCheckOut" name="btnCheckOut" class="btn btn-sm btn-danger">Check Out</button>
                    </div>
                    <div class="m-0">
                        <div class="fw-boldest fs-3 text-gray-800 mb-8">{{ strtoupper(trim($data->kode_reservasi)) }}</div>
                        <div class="row g-5 mb-12">
                            <div class="col-sm-6">
                                <div class="fw-boldest text-muted fs-7 mb-1">GUEST:</div>
                                <div class="fw-bolder fs-6 text-gray-800">{{ trim($data->customer->customer->no_identitas) }}</div>
                                <div class="fw-bold fs-6 text-gray-800">{{ trim($data->customer->customer->nama) }}
                                    <br>{{ trim($data->customer->customer->kota) }}
                                </div>
                            </div>
                        </div>
                        <div class="row g-5 mb-11">
                            <div class="col-sm-6">
                                <div class="fw-boldest text-muted fs-7 mb-1">CHECK IN:</div>
                                <div class="fw-bolder fs-6 text-gray-800">{{ date('j F Y', strtotime($data->reservasi->check_in)) }}
                                    <br><div class="fw-bold fs-6 text-gray-800">{{ date('H:i:s', strtotime($data->reservasi->check_in)) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fw-boldest text-muted fs-7 mb-1">CHECK OUT:</div>
                                <div class="fw-bolder fs-6 text-gray-800">{{ date('j F Y', strtotime($data->reservasi->check_out)) }}
                                    <br><div class="fw-bold fs-6 text-gray-800">{{ date('H:i:s', strtotime($data->reservasi->check_out)) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fs-3 fw-boldest text-dark mt-4 mb-4">ROOM</div>
                            <div class="table-responsive border-top">
                                <table class="table table-row-bordered">
                                    <thead>
                                        <tr class="fw-boldest text-muted fs-7">
                                            <th class="w-50px pb-2 text-center">NO</th>
                                            <th class="min-w-175px pb-2">KETERANGAN</th>
                                            <th class="min-w-70px text-end pb-2">HARGA</th>
                                            <th class="min-w-80px text-end pb-2">QUANTITY</th>
                                            <th class="min-w-100px text-end pb-2">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-bottom">
                                        <tr class="fs-6 fw-bold text-gray-800">
                                            <td class="pt-3 pb-3 text-center">1</td>
                                            <td class="pt-3 pb-3">{{ strtoupper(trim($data->reservasi->room->nama_tipe)) }} ROOM {{ strtoupper(trim($data->reservasi->room->kode_room)) }}</td>
                                            <td class="pt-3 pb-3 text-end">{{ number_format($data->reservasi->room->harga) }}</td>
                                            <td class="pt-3 pb-3 text-end">{{ number_format($data->reservasi->room->lama_inap) }} @if($data->reservasi->room->status_longtime == 1) MALAM @else JAM @endif</td>
                                            <td class="pt-3 pb-3 text-end">{{ number_format($data->reservasi->room->sub_total) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <div class="mw-300px">
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-boldest text-muted fs-7 pe-20">TOTAL:</div>
                                            <div class="text-end fw-bold fs-6 text-gray-800">{{ number_format($data->reservasi->room->sub_total) }}</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-boldest text-muted fs-7 pe-20">DISC ({{ number_format($data->reservasi->room->disc_room, 2) }}%):</div>
                                            <div class="text-end fw-bold fs-6 text-gray-800">{{ number_format($data->reservasi->room->disc_rp_room) }}</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-boldest text-muted fs-7 pe-20">PPN ({{ number_format($data->reservasi->room->ppn_room, 2) }}%):</div>
                                            <div class="text-end fw-bold fs-6 text-gray-800">{{ number_format($data->reservasi->room->ppn_rp_room) }}</div>
                                        </div>
                                        <div class="d-flex flex-stack">
                                            <div class="fw-boldest text-muted fs-7 pe-20">TOTAL ROOM:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->room->total_room) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-10"></div>
                        <div class="flex-grow-1">
                            <div class="fs-3 fw-boldest text-dark mb-4">LAYANAN</div>
                            <div class="table-responsive border-top">
                                <table class="table table-row-bordered">
                                    <thead>
                                        <tr class="fw-boldest text-muted fs-7">
                                            <th class="w-50px pb-2 text-center">NO</th>
                                            <th class="min-w-175px pb-2">KETERANGAN</th>
                                            <th class="min-w-70px text-end pb-2">HARGA</th>
                                            <th class="min-w-80px text-end pb-2">QUANTITY</th>
                                            <th class="min-w-80px text-end pb-2">DISC</th>
                                            <th class="min-w-100px text-end pb-2">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-bottom">
                                        @forelse ($data->reservasi->layanan->detail as $detail_layanan)
                                        <tr class="fs-6 fw-bold text-gray-800">
                                            <td class="pt-3 pb-3 text-center">{{ $loop->iteration }}</td>
                                            <td class="pt-3 pb-3">{{ $detail_layanan->nama_layanan }}</td>
                                            <td class="pt-3 pb-3 text-end">{{ number_format($detail_layanan->harga) }}</td>
                                            <td class="pt-3 pb-3 text-end">{{ number_format($detail_layanan->qty) }}</td>
                                            <td class="pt-3 pb-3 text-end">{{ number_format($detail_layanan->disc_detail, 2) }}</td>
                                            <td class="pt-3 pb-3 text-end">{{ number_format($detail_layanan->total) }}</td>
                                        </tr>
                                        @empty
                                        <tr class="fs-6 fw-boldest text-muted">
                                            <td colspan="6" class="pt-3 pb-3 text-center">- KOSONG -</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <div class="mw-300px">
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-boldest text-muted fs-7 pe-20">TOTAL:</div>
                                            <div class="text-end fw-bold fs-6 text-gray-800">{{ number_format($data->reservasi->layanan->sub_total_layanan) }}</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-boldest text-muted fs-7 pe-20">DISC ({{ number_format($data->reservasi->layanan->disc_layanan, 2) }}%):</div>
                                            <div class="text-end fw-bold fs-6 text-gray-800">{{ number_format($data->reservasi->layanan->disc_rp_layanan) }}</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-boldest text-muted fs-7 pe-20">PPN ({{ number_format($data->reservasi->layanan->ppn_layanan, 2) }}%):</div>
                                            <div class="text-end fw-bold fs-6 text-gray-800">{{ number_format($data->reservasi->layanan->ppn_rp_layanan) }}</div>
                                        </div>
                                        <div class="d-flex flex-stack">
                                            <div class="fw-boldest text-muted fs-7 pe-20">TOTAL LAYANAN:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->layanan->total_layanan) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator my-10"></div>
                                <div class="d-flex justify-content-end">
                                    <div class="mw-300px">
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-boldest text-muted fs-7 pe-20">TOTAL ROOM + LAYANAN:</div>
                                            <div class="text-end fw-bold fs-6 text-gray-800">{{ number_format($data->reservasi->layanan->sub_total_layanan + $data->reservasi->room->total_room) }}</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-boldest text-muted fs-7 pe-20">BIAYA LAIN-LAIN:</div>
                                            <div class="text-end fw-bold fs-6 text-gray-800">{{ number_format($data->reservasi->biaya_lain) }}</div>
                                        </div>
                                        <div class="d-flex flex-stack">
                                            <div class="fw-boldest text-muted fs-7 pe-20">GRAND TOTAL:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->grand_total) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-10"></div>
                        <div class="flex-grow-1">
                            <div class="fs-3 fw-boldest text-dark mb-4">PENALTY</div>
                            <div class="table-responsive border-top">
                                <table class="table table-row-bordered">
                                    <thead>
                                        <tr class="fw-boldest text-muted fs-7">
                                            <th class="w-50px pb-2 text-center">NO</th>
                                            <th class="min-w-105px pb-2">ITEM</th>
                                            <th class="min-w-175px pb-2">KETERANGAN</th>
                                            <th class="min-w-70px text-end pb-2">DENDA</th>
                                            <th class="min-w-80px text-end pb-2">QUANTITY</th>
                                            <th class="min-w-100px text-end pb-2">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-bottom">
                                        @forelse($data->reservasi->penalty->detail as $data_penalty)
                                        <tr class="fs-6 fw-bold text-gray-800">
                                            <td class="pt-3 pb-3 text-center">{{ $loop->iteration }}</td>
                                            <td class="pt-3 pb-3">{{ strtoupper(trim($data_penalty->nama_item)) }}</td>
                                            <td class="pt-3 pb-3">{{ strtoupper(trim($data_penalty->keterangan)) }}</td>
                                            <td class="pt-3 pb-3 text-end">{{ number_format($data_penalty->denda) }}</td>
                                            <td class="pt-3 pb-3 text-end">{{ number_format($data_penalty->qty) }}</td>
                                            <td class="pt-3 pb-3 text-end">{{ number_format($data_penalty->total) }}</td>
                                        </tr>
                                        @empty
                                        <tr class="fs-6 fw-boldest text-muted">
                                            <td colspan="6" class="pt-3 pb-3 text-center">- KOSONG -</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <div class="mw-300px">
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-boldest text-muted fs-7 pe-20">TOTAL PENALTY:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->penalty->total) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-0">
                <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 w-lg-400px p-9 bg-lighten">
                    <div class="mb-8">
                        @if($data->reservasi->status->status_pembayaran_reservasi == 0)
                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                            <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
                                    <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor"></rect>
                                    <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"></rect>
                                </svg>
                            </span>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-bold">
                                    <h4 class="text-gray-900 fw-bolder">Informasi</h4>
                                    <div class="fs-6 text-gray-700">Data pembayaran reservasi atau layanan belum selesai.
                                    <a href="{{ route('transaksi.reservasi.inhouse.form-reservasi-inhouse', trim($data->kode_reservasi)) }}" class="fw-bolder">Selesaikan pembayaran</a>.</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($data->reservasi->status->status_pembayaran_penalty == 0)
                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                            <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
                                    <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor"></rect>
                                    <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"></rect>
                                </svg>
                            </span>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-bold">
                                    <h4 class="text-gray-900 fw-bolder">Informasi</h4>
                                    <div class="fs-6 text-gray-700">Data pembayaran penalty belum selesai.
                                        <a href="{{ route('transaksi.reservasi.penalty.form-reservasi-penalty', trim($data->kode_reservasi)) }}" class="fw-bolder">Selesaikan pembayaran</a>.
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($data->reservasi->status->status_pembayaran_reservasi == 1 && $data->reservasi->status->status_pembayaran_penalty == 1)
                        <div class="notice d-flex bg-light-success rounded border-success border border-dashed p-6">
                            <span class="svg-icon svg-icon-2tx svg-icon-success me-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
                                    <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor"></rect>
                                    <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"></rect>
                                </svg>
                            </span>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-bold">
                                    <h4 class="text-gray-900 fw-bolder">Informasi</h4>
                                    <div class="fs-6 text-gray-700">Pembayaran reservasi OK</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <h6 class="mt-8 mb-8 fw-boldest text-primary">DETAILS</h6>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Platform</div>
                        <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">{{ strtoupper(trim($data->platform->nama)) }}
                            <span class="fs-7 text-danger d-flex align-items-center">
                                <span class="bullet bullet-dot bg-danger mx-2"></span>
                                {{ strtoupper(trim($data->platform->kode)) }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">No Referensi</div>
                        <div class="fw-bolder fs-6 text-gray-800">{{ strtoupper(trim($data->platform->no_referensi)) }}</div>
                    </div>
                    <h6 class="mb-8 fw-boldest text-primary mt-15">CONTACT PERSON</h6>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Nama</div>
                        <div class="fw-bolder fs-6 text-gray-800">{{ strtoupper(trim($data->customer->contact_person->nama)) }}</div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Telepon</div>
                        <div class="fw-bolder fs-6 text-gray-800">{{ strtoupper(trim($data->customer->contact_person->telepon)) }}</div>
                    </div>
                    <h6 class="mb-8 fw-boldest text-primary mt-15">DETAIL PEMBAYARAN</h6>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Deposit</div>
                        @if($data->reservasi->deposit > 0)
                        <div class="fw-bolder fs-6 text-danger">{{ number_format($data->reservasi->deposit) }}</div>
                        @else
                        <div class="fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->deposit) }}</div>
                        @endif
                    </div>
                    <h6 class="mb-8 fw-boldest text-primary mb-6">RESERVASI</h6>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Total Reservasi</div>
                        <div class="fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->grand_total) }}</div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Pembayaran Reservasi</div>
                        <div class="fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->total_pembayaran) }}</div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Sisa Pembayaran Reservasi</div>
                        @if($data->reservasi->sisa_pembayaran > 0)
                        <div class="fw-bolder fs-6 text-danger">{{ number_format($data->reservasi->sisa_pembayaran) }}</div>
                        @else
                        <div class="fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->sisa_pembayaran) }}</div>
                        @endif
                    </div>
                    <h6 class="mb-8 fw-boldest text-primary mt-15">PENALTY</h6>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Total Penalty</div>
                        <div class="fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->penalty->total) }}</div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Pembayaran Penalty</div>
                        <div class="fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->penalty->total_pembayaran) }}</div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Sisa Pembayaran Penalty</div>
                        @if($data->reservasi->penalty->sisa_pembayaran > 0)
                        <div class="fw-bolder fs-6 text-danger">{{ number_format($data->reservasi->penalty->sisa_pembayaran) }}</div>
                        @else
                        <div class="fw-bolder fs-6 text-gray-800">{{ number_format($data->reservasi->penalty->sisa_pembayaran) }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalEntryCheckOut" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div id="modalContentEntryCheckOut" class="modal-content">
            <form id="formEntryCheckOut" name="formEntryCheckOut" autofill="off" autocomplete="off" method="post" action="{{ route('transaksi.reservasi.checkout.simpan-reservasi-checkout') }}">
                <div class="modal-header">
                    <h3 id="modalTitleEntryCheckOut" class="modal-title">Reservasi Check Out</h3>
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
                    @csrf
                    <label class="form-label required">Apakah anda yakin akan melakukan check out untuk data reservasi ini:</label>
                    <div class="fv-row mt-8">
                        <label class="form-label required">Password:</label>
                        <input id="inputPassword" name="password" type="password" class="form-control" placeholder="Input Password">
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label required">Password Confirmation:</label>
                        <input id="inputPasswordConfirm" name="password_confirm" type="password" class="form-control" placeholder="Input Password Confirmation">
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button id="btnCheckOutProses" type="submit" class="btn btn-danger">Check Out</button>
                    <button type="button" class="btn btn-light text-end" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    const url = {
        'daftar_reservasi': "{{ route('transaksi.reservasi.checkout.daftar-reservasi-checkout') }}",
        'cek_pembayaran': "{{ route('transaksi.reservasi.checkout.cek-pembayaran-reservasi-checkout') }}",
        'simpan_reservasi_checkout': "{{ route('transaksi.reservasi.checkout.simpan-reservasi-checkout') }}",
    }
    const data = {
        'kode_reservasi': "{{ trim($data->kode_reservasi) }}"
    }
</script>
<script src="{{ asset('assets/js/app/transaksi/reservasi/checkout/reservasi-checkout-form.js') }}?v={{ time() }}"></script>
@endpush
@endsection
