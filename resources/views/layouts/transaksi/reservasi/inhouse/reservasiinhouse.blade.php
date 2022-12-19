@extends('layouts.main.index')
@section('caption','Transaksi')
@section('title','Reservasi')
@section('subtitle','In House')
@section('container')
<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 pt-7">
        <h3 class="card-title align-rooms-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Daftar Reservasi (In House)</span>
            <span class="text-muted mt-1 fw-bold fs-7">Form transaksi reservasi</span>
            @if($data_filter->search != '')
            <div class="mt-4">
                <span class="fs-7 fw-bolder text-dark">Filter
                    @if(trim(strtoupper($data_filter->filter)) == 'PLATFORM') Platform
                    @elseif(trim(strtoupper($data_filter->filter)) == 'TIPEROOM') Tipe Room
                    @elseif(trim(strtoupper($data_filter->filter)) == 'ROOM') Room
                    @elseif(trim(strtoupper($data_filter->filter)) == 'NAMACP') Contact Person
                    @elseif(trim(strtoupper($data_filter->filter)) == 'TELPCP') Telepon Contact Person
                    @elseif(trim(strtoupper($data_filter->filter)) == 'NOIDENTITAS') No Identitas
                    @elseif(trim(strtoupper($data_filter->filter)) == 'KODERESERVASI') Kode Reservasi
                    @endif:
                    <span class="badge badge-light-info fs-7 fw-boldest ms-2">{{ trim($data_filter->search) }}</span>
                </span>
            </div>
            @endif
        </h3>
        <div class="card-toolbar">
            <button id="btnFilter" class="btn btn-light btn-active-light-primary ms-2" data-bs-toggle="modal" data-bs-target="#modalFilter">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor"/>
                    </svg>
                </span>Filter
            </button>
        </div>
    </div>
    <div class="card-body p-9">
        <div class="fv-row">
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-nowrap mb-0">
                    <thead class="border">
                        <tr class="fs-7 fw-bolder text-gray-500">
                            <th class="w-150px ps-3 pe-3 text-center">Nomor</th>
                            <th class="w-50px ps-3 pe-3 text-center">Indicator</th>
                            <th class="w-100px ps-3 pe-3 text-center">Room</th>
                            <th class="w-200px ps-3 pe-3 text-center">Customer</th>
                            <th class="w-100px ps-3 pe-3 text-center">Reservasi</th>
                            <th class="w-100px ps-3 pe-3 text-center">Status</th>
                            <th class="min-w-50px ps-3 pe-3 text-center">Catatan</th>
                            <th class="w-100px ps-3 pe-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border">
                        @forelse($data_reservasi->data as $data)
                        <tr class="fs-6 fw-bold text-gray-700">
                            <td class="ps-3 pe-3" style="text-align:left;vertical-align:top;">
                                <div class="row">
                                    <span class="fs-7 fw-bolder text-dark d-block">{{ trim($data->kode_reservasi) }}</span>
                                    <span class="fs-7 fw-bolder text-gray-600 mt-1 d-block">{{ trim($data->tanggal_reservasi) }}</span>
                                    <span class="fs-7 fw-bolder text-gray-500 mt-8 d-block">Contact Person:</span>
                                    <span class="fs-7 fw-bolder text-danger d-block mt-1">{{ trim($data->nama_contact_person) }}</span>
                                    <span class="fs-7 fw-bolder text-gray-600 d-block">{{ trim($data->keterangan) }}</span>
                                </div>
                            </td>
                            <td class="ps-3 pe-3" style="text-align:center;vertical-align:top;">
                                @if(trim($data->indicator) != '')
                                @if(trim($data->indicator) == 'CHECKIN')
                                <span class="badge badge-success fs-8 fw-boldest animation-blink">{{ trim($data->indicator) }}</span>
                                @else
                                <span class="badge badge-danger fs-8 fw-boldest animation-blink">{{ trim($data->indicator) }}</span>
                                @endif
                                @endif
                            </td>
                            <td class="ps-3 pe-3" style="text-align:center;vertical-align:top;">
                                <span class="badge badge-light-info fs-7 fw-boldest">{{ trim($data->nama_tipe) }}</span>
                                <br>
                                <span class="badge badge-light-danger fs-7 fw-boldest">{{ trim($data->kode_room) }}</span>
                            </td>
                            <td class="ps-3 pe-3" style="text-align:left;vertical-align:top;">
                                <div class="fs-7 fw-bolder text-dark mb-4">{{ trim($data->no_identitas) }}</div>
                                <div class="fs-7 fw-bolder text-gray-600">{{ trim($data->nama_customer) }}</div>
                                <div class="fs-7 fw-bolder text-gray-600">{{ trim($data->kota_customer) }}</div>
                                <div class="fs-7 fw-bolder text-gray-600">{{ trim($data->telepon_customer) }}</div>
                            </td>
                            <td class="ps-3 pe-3" style="text-align:center;vertical-align:top;">
                                <div class="fs-7 fw-boldest text-primary">{{ trim($data->tanggal_check_in) }}</div>
                                <div class="fs-7 fw-boldest text-primary">{{ trim($data->jam_check_in) }}</div>
                                <div class="fs-7 fw-boldest text-danger mt-4">{{ trim($data->tanggal_check_out) }}</div>
                                <div class="fs-7 fw-boldest text-danger">{{ trim($data->jam_check_out) }}</div>
                            </td>
                            <td class="ps-3 pe-3" style="text-align:center;vertical-align:top;">
                                @if(strtoupper(trim($data->status_reservasi)) == 'CANCELED')
                                <span class="badge badge-danger fs-8 fw-boldest">{{ strtoupper(trim($data->status_reservasi)) }}</span>
                                @elseif(strtoupper(trim($data->status_reservasi)) == 'CHECK-OUT')
                                <span class="badge badge-light-danger fs-8 fw-boldest">{{ strtoupper(trim($data->status_reservasi)) }}</span>
                                @elseif(strtoupper(trim($data->status_reservasi)) == 'NOT SHOW')
                                <span class="badge badge-warning fs-8 fw-boldest">{{ strtoupper(trim($data->status_reservasi)) }}</span>
                                @elseif(strtoupper(trim($data->status_reservasi)) == 'NOT SHOW END')
                                <span class="text-info fs-8 fw-boldest">{{ strtoupper(trim($data->status_reservasi)) }}</span>
                                @elseif(strtoupper(trim($data->status_reservasi)) == 'NOT SHOW END (NEED PROCESS)')
                                <span class="text-danger fs-8 fw-boldest">{{ strtoupper(trim($data->status_reservasi)) }}</span>
                                @elseif(strtoupper(trim($data->status_reservasi)) == 'IN-HOUSE')
                                <span class="badge badge-success fs-8 fw-boldest">{{ strtoupper(trim($data->status_reservasi)) }}</span>
                                @else
                                <span class="badge badge-primary fs-8 fw-boldest">{{ strtoupper(trim($data->status_reservasi)) }}</span>
                                @endif
                            </td>
                            <td class="ps-3 pe-3" style="text-align:left;vertical-align:top;">
                                <p class="fs-7 fw-bolder text-gray-600">{{ trim($data->catatan) }}</p>
                            </td>
                            <td class="ps-3 pe-3" style="text-align:center;vertical-align:top;">
                                <a href="{{ route('transaksi.reservasi.inhouse.form-reservasi-inhouse', trim($data->kode_reservasi)) }}" id="btnEdit" class="btn btn-icon btn-light-primary btn-sm mt-1" data-toggle="modal" data-kode={{ trim($data->kode_reservasi) }}>
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr class="fw-bolder text-muted fs-6">
                            <td class="text-center p-20" colspan="8">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="row mt-4">
                <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                    <div class="dataTables_length">
                        <label>
                            <select id="selectPerPage" name="per_page" class="form-select form-select-sm">
                                <option value="10" @if($data_page->per_page == 10) selected @endif>10</option>
                                <option value="25" @if($data_page->per_page == 25) selected @endif>25</option>
                                <option value="50" @if($data_page->per_page == 50) selected @endif>50</option>
                                <option value="100" @if($data_page->per_page == 100) selected @endif>100</option>
                            </select>
                        </label>
                    </div>
                    <div class="dataTables_info" role="status" aria-live="polite">Showing {{ $data_page->from }} to {{ $data_page->to }} of {{ $data_page->total }} records</div>
                </div>
                <div class="col-sm-12 col-md-7 d-flex align-rooms-center justify-content-center justify-content-md-end">
                    <div class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination">
                            @foreach ($data_reservasi->links as $link)
                            <li class="page-item @if($link->active == true) active @endif
                                @if($link->url == '') disabled @endif
                                @if($data_page->current_page == $link->label) active @endif">
                                @if($link->active == true)
                                <span class="page-link">{{ $link->label }}</span>
                                @else
                                <a href="#" class="page-link" data-page="{{ $link->url }}">
                                    @if(Str::contains(strtolower($link->label), 'previous'))
                                    <i class="previous"></i>
                                    @elseif(Str::contains(strtolower($link->label), 'next'))
                                    <i class="next"></i>
                                    @else
                                    {{ $link->label }}
                                    @endif
                                </a>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalFilter" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div id="modalContentFilter" class="modal-content">
            <form id="formEntryFilter" name="formEntryFilter" autofill="off" autocomplete="off" method="get" action="{{ route('transaksi.reservasi.inhouse.daftar-reservasi-inhouse') }}">
                <div class="modal-header">
                    <h3 id="modalTitleFilter" class="modal-title">Filter Reservasi</h3>
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
                        <label class="form-label">Cari Berdasarkan:</label>
                        <select id="selectFilter" name="filter" class="form-select">
                            <option value="PLATFORM" @if(trim(strtoupper($data_filter->filter)) == 'PLATFORM') selected @endif>Platform</option>
                            <option value="TIPEROOM" @if(trim(strtoupper($data_filter->filter)) == 'TIPEROOM') selected @endif>Tipe Room</option>
                            <option value="ROOM" @if(trim(strtoupper($data_filter->filter)) == 'ROOM') selected @endif>Room</option>
                            <option value="NAMACP" @if(trim(strtoupper($data_filter->filter)) == 'NAMACP') selected @endif>Contact Person</option>
                            <option value="TELPCP" @if(trim(strtoupper($data_filter->filter)) == 'TELPCP') selected @endif>Telepon Contact Person</option>
                            <option value="NOIDENTITAS" @if(trim(strtoupper($data_filter->filter)) == 'NOIDENTITAS') selected @endif>No Identitas</option>
                            <option value="KODERESERVASI" @if(trim(strtoupper($data_filter->filter)) == 'KODERESERVASI') selected @endif>Kode Reservasi</option>
                        </select>
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label">Cari Data:</label>
                        <input id="inputSearch" name="search" type="text" class="form-control" placeholder="Input Data Pencarian" value="{{ $data_filter->search }}">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button id="btnFilterReset" class="btn btn-danger" role="button">Reset Filter</button>
                    <div class="text-end">
                        <button id="btnFilterProses" type="submit" class="btn btn-primary">Terapkan</button>
                        <button id="btnFilterClose" name="btnClose" type="button" class="btn btn-light text-end" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    const data = {
        'start_record': "{{ $data_page->from }}"
    }
    const url = {
        'data_reservasi': "{{ route('transaksi.reservasi.inhouse.daftar-reservasi-inhouse') }}"
    }
</script>
<script src="{{ asset('assets/js/app/custom/autonumeric.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/transaksi/reservasi/inhouse/reservasi-daftar.js') }}?v={{ time() }}"></script>
@endpush
@endsection
