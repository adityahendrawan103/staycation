@extends('layouts.main.index')
@section('caption','Master')
@section('title','Platform')
@section('subtitle','')
@section('container')
<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 pt-7">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Daftar Platform</span>
            <span class="text-muted mt-1 fw-bold fs-7">Form platform reservasi</span>
        </h3>
        <div class="card-toolbar">
            <button id="btnTambah" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEntryPlatform">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3" d="M11 13H7C6.4 13 6 12.6 6 12C6 11.4 6.4 11 7 11H11V13ZM17 11H13V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"/>
                        <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM17 11H13V7C13 6.4 12.6 6 12 6C11.4 6 11 6.4 11 7V11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="currentColor"/>
                    </svg>
                </span>Tambah
            </button>
        </div>
    </div>
    <div class="card-body p-9">
        <div class="fv-row mb-8">
            <label class="form-label">Cari Data Platform:</label>
            <div class="input-group">
                <span class="input-group-text">Pencarian</span>
                <input id="inputSearch" name="search" type="search" class="form-control" placeholder="Cari Data Platform"
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
                            <th class="min-w-100px ps-3 pe-3">Kode</th>
                            <th class="min-w-150px ps-3 pe-3">Keterangan</th>
                            <th class="min-w-100px ps-3 pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border">
                        @forelse($data_platform->data as $data)
                        <tr class="fs-6 fw-bold text-gray-700">
                            <td class="ps-3 pe-3">{{ trim($data->kode_platform) }}</td>
                            <td class="ps-3 pe-3">{{ trim($data->nama_platform) }}</td>
                            <td class="ps-3 pe-3">
                                <button id="btnEdit" class="btn btn-icon btn-light-primary btn-sm" data-toggle="modal" data-kode={{ trim($data->kode_platform) }}>
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </button>
                                <button id="btnHapus" class="btn btn-icon btn-light-danger btn-sm" data-kode={{ trim($data->kode_platform) }}>
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
                            <td class="text-center p-20" colspan="3">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
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
                <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                    <div class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination">
                            @foreach ($data_platform->links as $link)
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

<div id="modalEntryPlatform" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div id="modalContentPlatform" class="modal-content">
            <form id="formEntryPlatform" name="formEntryPlatform" autofill="off" autocomplete="off" method="post" action="{{ route('master.platform.simpan-platform') }}">
                @csrf
                <div class="modal-header">
                    <h3 id="modalTitlePlatform" class="modal-title"></h3>
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
                    @include('components.alertfailed')
                    <div class="fv-row">
                        <label class="form-label required">Kode Platform:</label>
                        <input id="inputKodePlatform" name="kode_platform" type="text" class="form-control" placeholder="Input Kode Platform" maxlength="10" oninput="this.value = this.value.toUpperCase()" required>
                        <span id="messageKodePlatform" class="invalid-feedback"></span>
                    </div>
                    <div class="fv-row mt-8">
                        <label class="form-label required">Keterangan:</label>
                        <input id="inputKeterangan" name="nama_platform" type="text" class="form-control" placeholder="Input Keterangan" maxlength="50" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnSimpan" name="btnSimpan" type="submit" class="btn btn-primary">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                            </svg>
                        </span>Simpan
                    </button>
                    <button id="btnClose" name="btnClose" type="button" class="btn btn-light btn-active-light-primary" data-bs-dismiss="modal">
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

@push('scripts')
<script type="text/javascript">
    const data = {
        'start_record': "{{ $data_page->from }}"
    }
    const url = {
        'data_platform': "{{ route('master.platform.daftar-platform') }}",
        'form_platform': "{{ route('master.platform.form-platform') }}",
        'hapus_platform': "{{ route('master.platform.hapus-platform') }}",
        'cek_kode_platform': "{{ route('master.platform.cek-kode-platform') }}"
    }
</script>
<script src="{{ asset('assets/js/app/master/platform/platform.js') }}?v={{ time() }}"></script>
@endpush
@endsection
