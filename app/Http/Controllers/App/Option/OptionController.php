<?php

namespace App\Http\Controllers\App\Option;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OptionController extends Controller
{
    public function optionCustomer(Request $request) {
        $responseApi = ApiService::OptionCustomer($request->get('page'), $request->get('per_page'), $request->get('search'),
                            strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if ($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $data_per_page = $data->per_page;
            $data_link_page = $data->links;
            $data_from_record = $data->from;
            $data_to_record = $data->to;
            $data_total_record = $data->total;

            $table_row = '';
            $table_pagination = '';

            foreach ($data->data as $data) {
                $table_row .= '<tr class="fs-7 fw-bold text-gray-600">
                        <td class="ps-3 pe-3">
                            <div class="d-flex align-items-center">
                                <div class="d-flex justify-content-start flex-column">
                                    <span class="text-dark fw-bolder text-hover-primary fs-7">'.$data->no_identitas.'</span>
                                    <span class="text-muted fw-bold text-muted d-block fs-7">'.$data->nama.'</span>
                                </div>
                            </div>
                        </td>
                        <td class="ps-3 pe-3">';

                if(strtoupper(trim($data->jenis_identitas)) == 'KTP') {
                    $table_row .= '<span class="badge badge-light-primary fs-8 fw-boldest">'.strtoupper(trim($data->jenis_identitas)).'</span>';
                } elseif(strtoupper(trim($data->jenis_identitas)) == 'SIM') {
                    $table_row .= '<span class="badge badge-light-danger fs-8 fw-boldest">'.strtoupper(trim($data->jenis_identitas)).'</span>';
                } else {
                    $table_row .= '<span class="badge badge-light-success fs-8 fw-boldest">'.strtoupper(trim($data->jenis_identitas)).'</span>';
                }

                $table_row .= '</td>
                        <td class="ps-3 pe-3">'.$data->tanggal_lahir.'</td>
                        <td class="ps-3 pe-3">'.$data->kota.'</td>
                        <td class="ps-3 pe-3">'.$data->telepon.'</td>
                        <td class="ps-3 pe-3 text-center">
                            <button id="selectOptionCustomer" class="btn btn-icon btn-bg-primary btn-sm me-1"
                                data-no_identitas="'.$data->no_identitas.'" data-nama="'.$data->nama.'"
                                data-jenis_identitas="'.$data->jenis_identitas.'" data-tanggal_lahir="'.$data->tanggal_lahir.'"
                                data-kota="'.$data->kota.'">
                                <i class="fa fa-check text-white"></i>
                            </button>
                        </td>
                    </tr>';
            }

            if(trim($table_row) == '') {
                $table_row .= '<tr class="fw-bolder text-muted fs-6">
                        <td class="text-center p-15" colspan="6">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                    </tr>';
            }

            foreach ($data_link_page as $data) {
                $label = $data->label;
                $disabled = ($data->url == null) ? 'disabled' : '';
                $active = ($data->active == true) ? 'active' : '';
                $item = 'page-item';
                $url = $data->url;

                if (Str::contains(strtolower(trim($data->label)), 'previous')) {
                    $label = '<';
                    $item = 'page-item previous';
                }

                if (Str::contains(strtolower(trim($data->label)), 'next')) {
                    $label = '>';
                    $item = 'page-item next';
                }

                if ($data->url == null) {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($disabled)).'">
                            <span class="page-link">' . trim($label) . '</span></span>
                        </li>';
                } else {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($active).' '.trim($disabled)).'">
                            <button id="paginationOptionCustomer" class="page-link" data-page="'.trim($url).'">'.trim($label).'</button>
                        </li>';
                }
            }

            $table_per_page10 = '';
            $table_per_page25 = '';
            $table_per_page50 = '';
            $table_per_page100 = '';
            if ($data_per_page == '10') {
                $table_per_page10 = 'selected';
            } elseif ($data_per_page == '25') {
                $table_per_page25 = 'selected';
            } elseif ($data_per_page == '50') {
                $table_per_page50 = 'selected';
            } elseif ($data_per_page == '100') {
                $table_per_page100 = 'selected';
            }

            $dataTable = '<div class="table-responsive">
                        <table id="tableOptionCustomer" class="table table-bordered align-middle table-nowrap mb-0">
                            <thead class="border">
                                <tr class="fs-7 fw-bolder text-gray-500">
                                    <th class="min-w-100px ps-3 pe-3">Identitas</th>
                                    <th class="w-50px ps-3 pe-3">Jenis</th>
                                    <th class="w-50px ps-3 pe-3">TglLahir</th>
                                    <th class="min-w-100px ps-3 pe-3">Kota</th>
                                    <th class="w-100px ps-3 pe-3">Telepon</th>
                                    <th class="w-50px ps-3 pe-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border fs-6 text-gray-800">'.$table_row.'</tbody>
                        </table>
                    </div>
                    <div id="pageOptionCustomer" class="mt-5">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label>
                                        <select id="selectPerPageOptionCustomer" name="selectPerPageOptionCustomer" aria-controls="selectPerPage"
                                            class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                            <option value="10"'.$table_per_page10.'>10</option>
                                            <option value="25"'.$table_per_page25.'>25</option>
                                            <option value="50"'.$table_per_page50.'>50</option>
                                            <option value="100"'.$table_per_page100.'>100</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="dataTables_info" id="selectPerPageInfoOptionCustomer" role="status" aria-live="polite">Showing <span id="startRecordOptionCustomer">'.$data_from_record.'</span> to '.$data_to_record.' of '.$data_total_record.' records</div>
                            </div>
                            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers" id="paginateOptionCustomer">
                                    <ul class="pagination">'.$table_pagination.'</ul>
                                </div>
                            </div>
                        </div>
                    </div>';

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $dataTable]);
        } else {
            return response()->json(['status' => 0, 'message' => $messageApi]);
        }
    }

    public function optionJabatan(Request $request) {
        $responseApi = ApiService::OptionJabatan($request->get('page'), $request->get('per_page'), $request->get('search'),
                            strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if ($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $data_per_page = $data->per_page;
            $data_link_page = $data->links;
            $data_from_record = $data->from;
            $data_to_record = $data->to;
            $data_total_record = $data->total;

            $table_row = '';
            $table_pagination = '';

            foreach ($data->data as $data) {
                $table_row .= '<tr class="fs-6 fw-bold text-gray-700">
                        <td class="ps-3 pe-3">'.$data->kode_jabatan.'</td>
                        <td class="ps-3 pe-3">'.$data->nama_jabatan.'</td>
                        <td class="ps-3 pe-3 text-center">
                            <button id="selectOptionJabatan" class="btn btn-icon btn-bg-primary btn-sm me-1"
                                data-kode_jabatan="'.$data->kode_jabatan.'" data-nama_jabatan="'.$data->nama_jabatan.'">
                                <i class="fa fa-check text-white"></i>
                            </button>
                        </td>
                    </tr>';
            }

            if(trim($table_row) == '') {
                $table_row .= '<tr class="fw-bolder text-muted fs-6">
                        <td class="text-center p-15" colspan="3">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                    </tr>';
            }

            foreach ($data_link_page as $data) {
                $label = $data->label;
                $disabled = ($data->url == null) ? 'disabled' : '';
                $active = ($data->active == true) ? 'active' : '';
                $item = 'page-item';
                $url = $data->url;

                if (Str::contains(strtolower(trim($data->label)), 'previous')) {
                    $label = '<';
                    $item = 'page-item previous';
                }

                if (Str::contains(strtolower(trim($data->label)), 'next')) {
                    $label = '>';
                    $item = 'page-item next';
                }

                if ($data->url == null) {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($disabled)).'">
                            <span class="page-link">' . trim($label) . '</span></span>
                        </li>';
                } else {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($active).' '.trim($disabled)).'">
                            <button id="paginationOptionJabatan" class="page-link" data-page="'.trim($url).'">'.trim($label).'</button>
                        </li>';
                }
            }

            $table_per_page10 = '';
            $table_per_page25 = '';
            $table_per_page50 = '';
            $table_per_page100 = '';
            if ($data_per_page == '10') {
                $table_per_page10 = 'selected';
            } elseif ($data_per_page == '25') {
                $table_per_page25 = 'selected';
            } elseif ($data_per_page == '50') {
                $table_per_page50 = 'selected';
            } elseif ($data_per_page == '100') {
                $table_per_page100 = 'selected';
            }

            $dataTable = '<div class="table-responsive">
                    <table id="tableOptionJabatan" class="table table-bordered align-middle table-nowrap mb-0">
                        <thead class="border">
                            <tr class="fs-7 fw-bolder text-gray-500">
                                <th class="min-w-100px ps-3 pe-3">Kode</th>
                                <th class="min-w-150px ps-3 pe-3">Nama</th>
                                <th class="w-50px ps-3 pe-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border fs-6 text-gray-800">'.$table_row.'</tbody>
                    </table>
                    </div>
                    <div id="pageOptionJabatan" class="mt-5">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label>
                                        <select id="selectPerPageOptionJabatan" name="selectPerPageOptionJabatan" aria-controls="selectPerPage"
                                            class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                            <option value="10"'.$table_per_page10.'>10</option>
                                            <option value="25"'.$table_per_page25.'>25</option>
                                            <option value="50"'.$table_per_page50.'>50</option>
                                            <option value="100"'.$table_per_page100.'>100</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="dataTables_info" id="selectPerPageInfoOptionJabatan" role="status" aria-live="polite">Showing <span id="startRecordOptionJabatan">'.$data_from_record.'</span> to '.$data_to_record.' of '.$data_total_record.' records</div>
                            </div>
                            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers" id="paginateOptionJabatan">
                                    <ul class="pagination">'.$table_pagination.'</ul>
                                </div>
                            </div>
                        </div>
                    </div>';

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $dataTable]);
        } else {
            return response()->json(['status' => 0, 'message' => $messageApi]);
        }
    }

    public function optionLayanan(Request $request) {
        $responseApi = ApiService::OptionLayanan($request->get('page'), $request->get('per_page'), $request->get('search'),
                            strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if ($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $data_per_page = $data->per_page;
            $data_link_page = $data->links;
            $data_from_record = $data->from;
            $data_to_record = $data->to;
            $data_total_record = $data->total;

            $table_row = '';
            $table_pagination = '';

            foreach ($data->data as $data) {
                $table_row .= '<tr class="fs-6 fw-bold text-gray-700">
                        <td class="ps-3 pe-3">'.$data->kode_layanan.'</td>
                        <td class="ps-3 pe-3">'.$data->nama_layanan.'</td>
                        <td class="ps-3 pe-3">'.$data->satuan.'</td>
                        <td class="ps-3 pe-3">'.number_format($data->harga).'</td>
                        <td class="ps-3 pe-3 text-center">
                            <button id="selectOptionLayanan" class="btn btn-icon btn-bg-primary btn-sm me-1"
                                data-kode_layanan="'.$data->kode_layanan.'" data-nama_layanan="'.$data->nama_layanan.'"
                                data-satuan="'.$data->satuan.'" data-harga="'.number_format($data->harga).'">
                                <i class="fa fa-check text-white"></i>
                            </button>
                        </td>
                    </tr>';
            }

            if(trim($table_row) == '') {
                $table_row .= '<tr class="fw-bolder text-muted fs-6">
                        <td class="text-center p-15" colspan="5">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                    </tr>';
            }

            foreach ($data_link_page as $data) {
                $label = $data->label;
                $disabled = ($data->url == null) ? 'disabled' : '';
                $active = ($data->active == true) ? 'active' : '';
                $item = 'page-item';
                $url = $data->url;

                if (Str::contains(strtolower(trim($data->label)), 'previous')) {
                    $label = '<';
                    $item = 'page-item previous';
                }

                if (Str::contains(strtolower(trim($data->label)), 'next')) {
                    $label = '>';
                    $item = 'page-item next';
                }

                if ($data->url == null) {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($disabled)).'">
                            <span class="page-link">' . trim($label) . '</span></span>
                        </li>';
                } else {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($active).' '.trim($disabled)).'">
                            <button id="paginationOptionLayanan" class="page-link" data-page="'.trim($url).'">'.trim($label).'</button>
                        </li>';
                }
            }

            $table_per_page10 = '';
            $table_per_page25 = '';
            $table_per_page50 = '';
            $table_per_page100 = '';
            if ($data_per_page == '10') {
                $table_per_page10 = 'selected';
            } elseif ($data_per_page == '25') {
                $table_per_page25 = 'selected';
            } elseif ($data_per_page == '50') {
                $table_per_page50 = 'selected';
            } elseif ($data_per_page == '100') {
                $table_per_page100 = 'selected';
            }

            $dataTable = '<div class="table-responsive">
                    <table id="tableOptionLayanan" class="table table-bordered align-middle table-nowrap mb-0">
                        <thead class="border">
                            <tr class="fs-7 fw-bolder text-gray-500">
                                <th class="min-w-100px ps-3 pe-3">Kode</th>
                                <th class="min-w-150px ps-3 pe-3">Nama</th>
                                <th class="min-w-100px ps-3 pe-3">Satuan</th>
                                <th class="min-w-100px ps-3 pe-3">Harga</th>
                                <th class="w-50px ps-3 pe-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border fs-6 text-gray-800">'.$table_row.'</tbody>
                    </table>
                    </div>
                    <div id="pageOptionLayanan" class="mt-5">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label>
                                        <select id="selectPerPageOptionLayanan" name="selectPerPageOptionLayanan" aria-controls="selectPerPage"
                                            class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                            <option value="10"'.$table_per_page10.'>10</option>
                                            <option value="25"'.$table_per_page25.'>25</option>
                                            <option value="50"'.$table_per_page50.'>50</option>
                                            <option value="100"'.$table_per_page100.'>100</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="dataTables_info" id="selectPerPageInfoOptionLayanan" role="status" aria-live="polite">Showing <span id="startRecordOptionLayanan">'.$data_from_record.'</span> to '.$data_to_record.' of '.$data_total_record.' records</div>
                            </div>
                            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers" id="paginateOptionLayanan">
                                    <ul class="pagination">'.$table_pagination.'</ul>
                                </div>
                            </div>
                        </div>
                    </div>';

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $dataTable]);
        } else {
            return response()->json(['status' => 0, 'message' => $messageApi]);
        }
    }

    public function optionItem(Request $request) {
        $responseApi = ApiService::OptionItem($request->get('page'), $request->get('per_page'), $request->get('search'),
                            strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if ($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $data_per_page = $data->per_page;
            $data_link_page = $data->links;
            $data_from_record = $data->from;
            $data_to_record = $data->to;
            $data_total_record = $data->total;

            $table_row = '';
            $table_pagination = '';

            foreach ($data->data as $data) {
                $table_row .= '<tr class="fs-6 fw-bold text-gray-700">
                        <td class="ps-3 pe-3">'.$data->kode_item.'</td>
                        <td class="ps-3 pe-3">'.$data->nama_item.'</td>
                        <td class="ps-3 pe-3 text-end">'.number_format($data->harga_denda).'</td>
                        <td class="ps-3 pe-3 text-center">
                            <button id="selectOptionItem" class="btn btn-icon btn-bg-primary btn-sm me-1"
                                data-kode_item="'.$data->kode_item.'" data-nama_item="'.$data->nama_item.'"
                                data-harga_denda="'.number_format($data->harga_denda).'">
                                <i class="fa fa-check text-white"></i>
                            </button>
                        </td>
                    </tr>';
            }

            if(trim($table_row) == '') {
                $table_row .= '<tr class="fw-bolder text-muted fs-6">
                        <td class="text-center p-15" colspan="3">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                    </tr>';
            }

            foreach ($data_link_page as $data) {
                $label = $data->label;
                $disabled = ($data->url == null) ? 'disabled' : '';
                $active = ($data->active == true) ? 'active' : '';
                $item = 'page-item';
                $url = $data->url;

                if (Str::contains(strtolower(trim($data->label)), 'previous')) {
                    $label = '<';
                    $item = 'page-item previous';
                }

                if (Str::contains(strtolower(trim($data->label)), 'next')) {
                    $label = '>';
                    $item = 'page-item next';
                }

                if ($data->url == null) {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($disabled)).'">
                            <span class="page-link">' . trim($label) . '</span></span>
                        </li>';
                } else {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($active).' '.trim($disabled)).'">
                            <button id="paginationOptionItem" class="page-link" data-page="'.trim($url).'">'.trim($label).'</button>
                        </li>';
                }
            }

            $table_per_page10 = '';
            $table_per_page25 = '';
            $table_per_page50 = '';
            $table_per_page100 = '';
            if ($data_per_page == '10') {
                $table_per_page10 = 'selected';
            } elseif ($data_per_page == '25') {
                $table_per_page25 = 'selected';
            } elseif ($data_per_page == '50') {
                $table_per_page50 = 'selected';
            } elseif ($data_per_page == '100') {
                $table_per_page100 = 'selected';
            }

            $dataTable = '<div class="table-responsive">
                        <table id="tableOptionItem" class="table table-bordered align-middle table-nowrap mb-0">
                            <thead class="border">
                                <tr class="fs-7 fw-bolder text-gray-500">
                                    <th class="min-w-100px ps-3 pe-3">Kode</th>
                                    <th class="min-w-150px ps-3 pe-3">Nama</th>
                                    <th class="w-100px ps-3 pe-3" text-end>Denda</th>
                                    <th class="w-50px ps-3 pe-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border fs-6 text-gray-800">'.$table_row.'</tbody>
                        </table>
                    </div>
                    <div id="pageOptionItem" class="mt-5">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label>
                                        <select id="selectPerPageOptionItem" name="selectPerPageOptionItem" aria-controls="selectPerPage"
                                            class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                            <option value="10"'.$table_per_page10.'>10</option>
                                            <option value="25"'.$table_per_page25.'>25</option>
                                            <option value="50"'.$table_per_page50.'>50</option>
                                            <option value="100"'.$table_per_page100.'>100</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="dataTables_info" id="selectPerPageInfoOptionItem" role="status" aria-live="polite">Showing <span id="startRecordOptionItem">'.$data_from_record.'</span> to '.$data_to_record.' of '.$data_total_record.' records</div>
                            </div>
                            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers" id="paginateOptionItem">
                                    <ul class="pagination">'.$table_pagination.'</ul>
                                </div>
                            </div>
                        </div>
                    </div>';

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $dataTable]);
        } else {
            return response()->json(['status' => 0, 'message' => $messageApi]);
        }
    }

    public function optionPlatform(Request $request) {
        $responseApi = ApiService::OptionPlatform($request->get('page'), $request->get('per_page'), $request->get('search'),
                            strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if ($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $data_per_page = $data->per_page;
            $data_link_page = $data->links;
            $data_from_record = $data->from;
            $data_to_record = $data->to;
            $data_total_record = $data->total;

            $table_row = '';
            $table_pagination = '';

            foreach ($data->data as $data) {
                $table_row .= '<tr class="fs-6 fw-bold text-gray-700">
                        <td class="ps-3 pe-3">'.$data->kode_platform.'</td>
                        <td class="ps-3 pe-3">'.$data->nama_platform.'</td>
                        <td class="ps-3 pe-3 text-center">
                            <button id="selectOptionPlatform" class="btn btn-icon btn-bg-primary btn-sm me-1"
                                data-kode_platform="'.$data->kode_platform.'" data-nama_platform="'.$data->nama_platform.'">
                                <i class="fa fa-check text-white"></i>
                            </button>
                        </td>
                    </tr>';
            }

            if(trim($table_row) == '') {
                $table_row .= '<tr class="fw-bolder text-muted fs-6">
                        <td class="text-center p-15" colspan="3">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                    </tr>';
            }

            foreach ($data_link_page as $data) {
                $label = $data->label;
                $disabled = ($data->url == null) ? 'disabled' : '';
                $active = ($data->active == true) ? 'active' : '';
                $item = 'page-item';
                $url = $data->url;

                if (Str::contains(strtolower(trim($data->label)), 'previous')) {
                    $label = '<';
                    $item = 'page-item previous';
                }

                if (Str::contains(strtolower(trim($data->label)), 'next')) {
                    $label = '>';
                    $item = 'page-item next';
                }

                if ($data->url == null) {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($disabled)).'">
                            <span class="page-link">' . trim($label) . '</span></span>
                        </li>';
                } else {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($active).' '.trim($disabled)).'">
                            <button id="paginationOptionPlatform" class="page-link" data-page="'.trim($url).'">'.trim($label).'</button>
                        </li>';
                }
            }

            $table_per_page10 = '';
            $table_per_page25 = '';
            $table_per_page50 = '';
            $table_per_page100 = '';
            if ($data_per_page == '10') {
                $table_per_page10 = 'selected';
            } elseif ($data_per_page == '25') {
                $table_per_page25 = 'selected';
            } elseif ($data_per_page == '50') {
                $table_per_page50 = 'selected';
            } elseif ($data_per_page == '100') {
                $table_per_page100 = 'selected';
            }

            $dataTable = '<div class="table-responsive">
                        <table id="tableOptionPlatform" class="table table-bordered align-middle table-nowrap mb-0">
                            <thead class="border">
                                <tr class="fs-7 fw-bolder text-gray-500">
                                    <th class="min-w-100px ps-3 pe-3">Kode</th>
                                    <th class="min-w-150px ps-3 pe-3">Nama</th>
                                    <th class="w-50px ps-3 pe-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border fs-6 text-gray-800">'.$table_row.'</tbody>
                        </table>
                    </div>
                    <div id="pageOptionPlatform" class="mt-5">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label>
                                        <select id="selectPerPageOptionPlatform" name="selectPerPageOptionPlatform" aria-controls="selectPerPage"
                                            class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                            <option value="10"'.$table_per_page10.'>10</option>
                                            <option value="25"'.$table_per_page25.'>25</option>
                                            <option value="50"'.$table_per_page50.'>50</option>
                                            <option value="100"'.$table_per_page100.'>100</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="dataTables_info" id="selectPerPageInfoOptionPlatform" role="status" aria-live="polite">Showing <span id="startRecordOptionPlatform">'.$data_from_record.'</span> to '.$data_to_record.' of '.$data_total_record.' records</div>
                            </div>
                            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers" id="paginateOptionPlatform">
                                    <ul class="pagination">'.$table_pagination.'</ul>
                                </div>
                            </div>
                        </div>
                    </div>';

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $dataTable]);
        } else {
            return response()->json(['status' => 0, 'message' => $messageApi]);
        }
    }

    public function optionRoomReservasi(Request $request) {
        $responseApi = ApiService::OptionRoomReservasi(trim($request->get('kode_tipe')), $request->get('check_in'),
                            $request->get('check_out'), $request->get('page'), $request->get('per_page'),
                            $request->get('search'), strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if ($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $data_per_page = $data->per_page;
            $data_link_page = $data->links;
            $data_from_record = $data->from;
            $data_to_record = $data->to;
            $data_total_record = $data->total;

            $table_row = '';
            $table_pagination = '';


            foreach ($data->data as $data) {
                $table_row .= '<tr class="fs-6 fw-bold text-gray-700">
                        <td class="ps-3 pe-3">'.$data->kode_room.'</td>
                        <td class="ps-3 pe-3">'.$data->tipe.'</td>
                        <td class="ps-3 pe-3 text-end">'.number_format($data->kapasitas).'</td>
                        <td class="ps-3 pe-3 text-end">'.number_format($data->longtime).'</td>
                        <td class="ps-3 pe-3 text-end">'.number_format($data->shorttime).'</td>';


                if(strtoupper(trim($data->status)) == 'MAINTENANCE') {
                    $table_row .= '<td class="ps-3 pe-3 text-center"><span class="badge badge-light-danger fs-8 fw-boldest">'.$data->status.'</span></td>';
                } else {
                    $table_row .= '<td class="ps-3 pe-3 text-center"><span class="badge badge-light-success fs-8 fw-boldest">'.$data->status.'</span></td>';
                }

                $table_row .= '<td class="ps-3 pe-3 text-center">';
                $number = 0;
                $daftar_fasilitas = '';
                foreach($data->fasilitas as $fasilitas) {
                    $color = '';
                    $number = (double)$number + 1;

                    if($number == 1) {
                        $color = 'primary';
                    } elseif($number == 2) {
                        $color = 'success';
                    } elseif($number == 3) {
                        $color = 'danger';
                    } else {
                        $color = 'info';
                        $number = 0;
                    }

                    if(trim($daftar_fasilitas) == '') {
                        $daftar_fasilitas .= trim($fasilitas->nama_fasilitas);
                    } else {
                        $daftar_fasilitas .= ', '. trim($fasilitas->nama_fasilitas);
                    }

                    $table_row .= '<span class="badge badge-light-'.$color.' fs-8 fw-boldest">'.$fasilitas->nama_fasilitas.'</span>';
                }

                $table_row .= '</td>
                            <td class="ps-3 pe-3 text-center">
                            <button id="selectOptionRoom" class="btn btn-icon btn-bg-primary btn-sm me-1"
                                data-kode_room="'.$data->kode_room.'" data-tipe="'.$data->tipe.'"
                                data-longtime="'.number_format($data->longtime).'"  data-shorttime="'.number_format($data->shorttime).'"
                                data-kapasitas="'.number_format($data->kapasitas).'"
                                data-fasilitas="'.$daftar_fasilitas.'">
                                <i class="fa fa-check text-white"></i>
                            </button>
                        </td>
                    </tr>';
            }

            if(trim($table_row) == '') {
                $table_row .= '<tr class="fw-bolder text-muted fs-6">
                        <td class="text-center p-15" colspan="8">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                    </tr>';
            }

            foreach ($data_link_page as $data) {
                $label = $data->label;
                $disabled = ($data->url == null) ? 'disabled' : '';
                $active = ($data->active == true) ? 'active' : '';
                $item = 'page-item';
                $url = $data->url;

                if (Str::contains(strtolower(trim($data->label)), 'previous')) {
                    $label = '<';
                    $item = 'page-item previous';
                }

                if (Str::contains(strtolower(trim($data->label)), 'next')) {
                    $label = '>';
                    $item = 'page-item next';
                }

                if ($data->url == null) {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($disabled)).'">
                            <span class="page-link">' . trim($label) . '</span></span>
                        </li>';
                } else {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($active).' '.trim($disabled)).'">
                            <button id="paginationOptionRoom" class="page-link" data-page="'.trim($url).'">'.trim($label).'</button>
                        </li>';
                }
            }

            $table_per_page10 = '';
            $table_per_page25 = '';
            $table_per_page50 = '';
            $table_per_page100 = '';
            if ($data_per_page == '10') {
                $table_per_page10 = 'selected';
            } elseif ($data_per_page == '25') {
                $table_per_page25 = 'selected';
            } elseif ($data_per_page == '50') {
                $table_per_page50 = 'selected';
            } elseif ($data_per_page == '100') {
                $table_per_page100 = 'selected';
            }

            $dataTable = '<div class="table-responsive">
                    <table id="tableOptionRoom" class="table table-bordered align-middle table-nowrap mb-0">
                        <thead class="border">
                            <tr class="fs-7 fw-bolder text-gray-500">
                                <th class="min-w-20px ps-3 pe-3">Kode</th>
                                <th class="min-w-50px ps-3 pe-3">Tipe</th>
                                <th class="min-w-20px ps-3 pe-3 text-end">Kapasitas</th>
                                <th class="min-w-50px ps-3 pe-3 text-end">Longtime</th>
                                <th class="min-w-50px ps-3 pe-3 text-end">Shorttime</th>
                                <th class="min-w-50px ps-3 pe-3 text-center">Status</th>
                                <th class="min-w-100px ps-3 pe-3 text-center">Fasilitas</th>
                                <th class="w-50px ps-3 pe-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border fs-6 text-gray-800">'.$table_row.'</tbody>
                    </table>
                    </div>
                    <div id="pageOptionRoom" class="mt-5">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label>
                                        <select id="selectPerPageOptionRoom" name="selectPerPageOptionRoom" aria-controls="selectPerPage"
                                            class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                            <option value="10"'.$table_per_page10.'>10</option>
                                            <option value="25"'.$table_per_page25.'>25</option>
                                            <option value="50"'.$table_per_page50.'>50</option>
                                            <option value="100"'.$table_per_page100.'>100</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="dataTables_info" id="selectPerPageInfoOptionRoom" role="status" aria-live="polite">Showing <span id="startRecordOptionRoom">'.$data_from_record.'</span> to '.$data_to_record.' of '.$data_total_record.' records</div>
                            </div>
                            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers" id="paginateOptionRoom">
                                    <ul class="pagination">'.$table_pagination.'</ul>
                                </div>
                            </div>
                        </div>
                    </div>';

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $dataTable]);
        } else {
            return response()->json(['status' => 0, 'message' => $messageApi]);
        }
    }

    public function optionRoomTipe(Request $request) {
        $responseApi = ApiService::optionRoomTipe($request->get('page'), $request->get('per_page'), $request->get('search'),
                            strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if ($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $data_per_page = $data->per_page;
            $data_link_page = $data->links;
            $data_from_record = $data->from;
            $data_to_record = $data->to;
            $data_total_record = $data->total;

            $table_row = '';
            $table_pagination = '';

            foreach ($data->data as $data) {
                $table_row .= '<tr class="fs-6 fw-bold text-gray-700">
                        <td class="ps-3 pe-3">'.$data->kode_tipe.'</td>
                        <td class="ps-3 pe-3">'.$data->nama_tipe.'</td>
                        <td class="ps-3 pe-3">'.$data->grade.'</td>
                        <td class="ps-3 pe-3 text-end">'.number_format($data->harga).'</td>
                        <td class="ps-3 pe-3 text-center">
                            <button id="selectOptionRoomTipe" class="btn btn-icon btn-bg-primary btn-sm me-1"
                                data-kode_tipe="'.$data->kode_tipe.'" data-nama_tipe="'.$data->nama_tipe.'"
                                data-harga_tipe="'.number_format($data->harga).'" data-grade="'.$data->grade.'">
                                <i class="fa fa-check text-white"></i>
                            </button>
                        </td>
                    </tr>';
            }

            if(trim($table_row) == '') {
                $table_row .= '<tr class="fw-bolder text-muted fs-6">
                        <td class="text-center p-15" colspan="5">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                    </tr>';
            }

            foreach ($data_link_page as $data) {
                $label = $data->label;
                $disabled = ($data->url == null) ? 'disabled' : '';
                $active = ($data->active == true) ? 'active' : '';
                $item = 'page-item';
                $url = $data->url;

                if (Str::contains(strtolower(trim($data->label)), 'previous')) {
                    $label = '<';
                    $item = 'page-item previous';
                }

                if (Str::contains(strtolower(trim($data->label)), 'next')) {
                    $label = '>';
                    $item = 'page-item next';
                }

                if ($data->url == null) {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($disabled)).'">
                            <span class="page-link">' . trim($label) . '</span></span>
                        </li>';
                } else {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($active).' '.trim($disabled)).'">
                            <button id="paginationOptionRoomTipe" class="page-link" data-page="'.trim($url).'">'.trim($label).'</button>
                        </li>';
                }
            }

            $table_per_page10 = '';
            $table_per_page25 = '';
            $table_per_page50 = '';
            $table_per_page100 = '';
            if ($data_per_page == '10') {
                $table_per_page10 = 'selected';
            } elseif ($data_per_page == '25') {
                $table_per_page25 = 'selected';
            } elseif ($data_per_page == '50') {
                $table_per_page50 = 'selected';
            } elseif ($data_per_page == '100') {
                $table_per_page100 = 'selected';
            }

            $dataTable = '<div class="table-responsive">
                        <table id="tableOptionRoomTipe" class="table table-bordered align-middle table-nowrap mb-0">
                            <thead class="border">
                                <tr class="fs-7 fw-bolder text-gray-500">
                                    <th class="min-w-100px ps-3 pe-3">Kode</th>
                                    <th class="min-w-150px ps-3 pe-3">Nama</th>
                                    <th class="w-50px ps-3 pe-3">Grade</th>
                                    <th class="min-w-100px ps-3 pe-3 text-end">Harga</th>
                                    <th class="w-50px ps-3 pe-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border fs-6 text-gray-800">'.$table_row.'</tbody>
                        </table>
                    </div>
                    <div id="pageOptionRoomTipe" class="mt-5">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label>
                                        <select id="selectPerPageOptionRoomTipe" name="selectPerPageOptionRoomTipe" aria-controls="selectPerPage"
                                            class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                            <option value="10"'.$table_per_page10.'>10</option>
                                            <option value="25"'.$table_per_page25.'>25</option>
                                            <option value="50"'.$table_per_page50.'>50</option>
                                            <option value="100"'.$table_per_page100.'>100</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="dataTables_info" id="selectPerPageInfoOptionRoomTipe" role="status" aria-live="polite">Showing <span id="startRecordOptionRoomTipe">'.$data_from_record.'</span> to '.$data_to_record.' of '.$data_total_record.' records</div>
                            </div>
                            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers" id="paginateOptionRoomTipe">
                                    <ul class="pagination">'.$table_pagination.'</ul>
                                </div>
                            </div>
                        </div>
                    </div>';

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $dataTable]);
        } else {
            return response()->json(['status' => 0, 'message' => $messageApi]);
        }
    }
}
