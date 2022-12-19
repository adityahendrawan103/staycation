@extends('layouts.main.index')
@section('caption','Master')
@section('title','Room')
@section('subtitle','Room')
@section('container')
@csrf
<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 pt-7">
        <h3 class="card-title align-rooms-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Daftar Room</span>
            <span class="text-muted mt-1 fw-bold fs-7">Form room</span>
        </h3>
        <div class="card-toolbar">
            <a href="{{ route('master.room.form-add-room') }}" id="btnTambah" class="btn btn-primary">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3" d="M11 13H7C6.4 13 6 12.6 6 12C6 11.4 6.4 11 7 11H11V13ZM17 11H13V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"/>
                        <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM17 11H13V7C13 6.4 12.6 6 12 6C11.4 6 11 6.4 11 7V11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"/>
                    </svg>
                </span>Tambah
            </a>
        </div>
    </div>
    <div class="card-body p-9">
        <div class="fv-row mb-8">
            <label class="form-label">Cari Data Room:</label>
            <div class="input-group">
                <span class="input-group-text">Pencarian</span>
                <input id="inputSearch" name="search" type="search" class="form-control" placeholder="Cari Data Room"
                    value="@if(isset($data_page->search)) {{ $data_page->search }} @endif" autocomplete="off">
                <button id="btnSearch" name="btnSearch" class="btn btn-primary">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
        <div class="fv-row">
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-nowrap mb-0">
                    <thead class="border">
                        <tr class="fs-7 fw-bolder text-gray-500">
                            <th class="w-100px ps-3 pe-3">Kode</th>
                            <th class="min-w-100px ps-3 pe-3">Tipe</th>
                            <th class="w-50px ps-3 pe-3 text-end">Lantai</th>
                            <th class="w-50px ps-3 pe-3 text-end">Kapasitas</th>
                            <th class="w-100px ps-3 pe-3 text-end">Longtime</th>
                            <th class="w-100px ps-3 pe-3 text-end">Shorttime</th>
                            <th class="w-50px ps-3 pe-3 text-center">Status</th>
                            <th class="min-w-150px ps-3 pe-3">Keterangan</th>
                            <th class="min-w-100px ps-3 pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border">
                        @forelse($data_room->data as $data)
                        <tr class="fs-6 fw-bold text-gray-700">
                            <td class="ps-3 pe-3">{{ trim($data->kode) }}</td>
                            <td class="ps-3 pe-3">{{ trim($data->tipe) }}</td>
                            <td class="ps-3 pe-3 text-end">{{ trim($data->lantai) }}</td>
                            <td class="ps-3 pe-3 text-end">{{ number_format($data->kapasitas) }}</td>
                            <td class="ps-3 pe-3 text-end">{{ number_format($data->longtime)}}</td>
                            <td class="ps-3 pe-3 text-end">{{ number_format($data->shorttime)}}</td>
                            <td class="ps-3 pe-3 text-center">
                                @if(strtoupper(trim($data->status)) == 'BOOKED')
                                <span class="badge badge-light-danger fs-8 fw-boldest">BOOKED</span>
                                @elseif(strtoupper(trim($data->status)) == 'IN-HOUSE')
                                <span class="badge badge-light-info fs-8 fw-boldest">IN-HOUSE</span>
                                @elseif(strtoupper(trim($data->status)) == 'HOUSEKEEPING')
                                <span class="badge badge-light-warning fs-8 fw-boldest">HOUSEKEEPING</span>
                                @elseif(strtoupper(trim($data->status)) == 'READY')
                                <span class="badge badge-light-success fs-8 fw-boldest">READY</span>
                                @elseif(strtoupper(trim($data->status)) == 'MAINTENANCE')
                                <span class="badge badge-light-primary fs-8 fw-boldest">MAINTENANCE</span>
                                @endif
                            </td>
                            <td class="ps-3 pe-3">{{ trim($data->keterangan) }}</td>
                            <td class="ps-3 pe-3">
                                <a href="{{ route('master.room.form-edit-room', trim($data->kode)) }}" id="btnEdit" class="btn btn-icon btn-light-primary btn-sm" data-toggle="modal" data-kode={{ trim($data->kode) }}>
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </a>
                                <button id="btnHapus" class="btn btn-icon btn-light-danger btn-sm" data-kode={{ trim($data->kode) }}>
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr class="fw-bolder text-muted fs-6">
                            <td class="text-center p-20" colspan="7">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
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
                            @foreach ($data_room->links as $link)
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

@push('scripts')
<script type="text/javascript">
    const data = {
        'start_record': "{{ $data_page->from }}"
    }
    const url = {
        'data_room': "{{ route('master.room.daftar-room') }}",
        'hapus_room': "{{ route('master.room.hapus-room') }}"
    }
</script>
<script src="{{ asset('assets/js/app/custom/autonumeric.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/app/master/room/room.js') }}?v={{ time() }}"></script>
@endpush
@endsection
