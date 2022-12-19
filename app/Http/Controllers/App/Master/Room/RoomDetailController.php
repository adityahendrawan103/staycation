<?php

namespace App\Http\Controllers\App\Master\Room;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomDetailController extends Controller
{
    public function daftarRoomDetail(Request $request) {
        $responseApi = ApiService::RoomDetailDaftar(strtoupper(trim($request->session()->get('app_user_id'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi = json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $dataRow = '';
            foreach($data->fasilitas as $data) {
                $dataRow .= '<tr class="fs-6 fw-bold text-gray-700">
                    <td class="ps-3 pe-3">'.strtoupper(trim($data->kode_fasilitas)).'</td>
                    <td class="ps-3 pe-3">'.trim($data->nama_fasilitas).'</td>
                    <td class="ps-3 pe-3">
                        <button id="btnHapusDetail" class="btn btn-icon btn-light-danger btn-sm" data-kode='.strtoupper(trim($data->kode_fasilitas)).'>
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                    <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                    <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                                </svg>
                            </span>
                        </button>
                    </td>
                </tr>';
            }

            if($dataRow == '') {
                $dataRow = '<tr class="fw-bolder text-muted fs-6">
                    <td class="text-center p-20" colspan="3">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                </tr>';
            }

            $dataTable = '<table class="table table-bordered align-middle table-nowrap mb-0">
                <thead class="border">
                    <tr class="fs-7 fw-bolder text-gray-500">
                        <th class="min-w-100px ps-3 pe-3">Kode</th>
                        <th class="min-w-50px ps-3 pe-3">Keterangan</th>
                        <th class="w-100px ps-3 pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="border">'.$dataRow.'</tbody>
            </table>';

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $dataTable]);
        } else {
            $dataTable = '<table class="table table-bordered align-middle table-nowrap mb-0">
                <thead class="border">
                    <tr class="fs-7 fw-bolder text-gray-500">
                        <th class="min-w-50px ps-3 pe-3">Kode</th>
                        <th class="min-w-150px ps-3 pe-3">Keterangan</th>
                        <th class="min-w-100px ps-3 pe-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="border">
                    <tr class="fw-bolder text-muted">
                        <td class="text-center p-20" colspan="3">- '.$messageApi.' -</td>
                    </tr>
                </tbody>
            </table>';
            return response()->json(['status' => 1, 'message' => 'success', 'data' => $dataTable]);
        }
    }

    public function checkListRoomDetail(Request $request) {
        $responseApi = ApiService::RoomDetailCheckList($request->get('page'), $request->get('per_page'), $request->get('search'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))), strtoupper(trim($request->session()->get('app_user_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi = json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $data_per_page = $data->per_page;
            $data_link_page = $data->links;
            $data_from_record = $data->from;
            $data_to_record = $data->to;
            $data_total_record = $data->total;

            $table_row = '';
            $table_pagination = '';

            $total_unchecked = 0;

            foreach ($data->data as $data) {
                $table_row .= '<tr class="fs-6 fw-bold text-gray-700">
                        <td class="ps-3 pe-3">
                            ';

                if((int)$data->checked == 1) {
                    $table_row .= '<div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input id="inputCheckRow" class="form-check-input widget-9-check" type="checkbox" value="1" checked>
                        </div>';
                } else {
                    $total_unchecked = (double)$total_unchecked + 1;
                    $table_row .= '<div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input id="inputCheckRow" class="form-check-input widget-9-check" type="checkbox" value="0">
                        </div>';
                }

                $table_row .= '</td>
                        <td class="ps-3 pe-3">'.$data->kode_fasilitas.'</td>
                        <td class="ps-3 pe-3">'.$data->nama_fasilitas.'</td>
                    </tr>';
            }

            if(trim($table_row) == '') {
                $table_row .= '<tr class="fw-bolder text-muted">
                        <td class="text-center p-15" colspan="3">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                    </tr>';
            }

            foreach ($data_link_page as $data) {
                $label = $data->label;
                $disabled = ($data->url == null) ? 'disabled' : '';
                $active = ($data->active == true) ? 'active' : '';
                $item = 'page-item';
                $url = $data->url;

                if (Str::contains(strtolower($data->label), 'previous')) {
                    $label = '<';
                    $item = 'page-item previous';
                }

                if (Str::contains(strtolower($data->label), 'next')) {
                    $label = '>';
                    $item = 'page-item next';
                }

                if ($data->url == null) {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($disabled)).'">
                            <span class="page-link">' . trim($label) . '</span></span>
                        </li>';
                } else {
                    $table_pagination .= '<li class="'.trim(trim($item).' '.trim($active).' '.trim($disabled)).'">
                            <button id="paginationOptionFasilitas" class="page-link" data-page="'.trim($url).'">'.trim($label).'</button>
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
                    <table id="tableOptionFasilitas" class="table table-bordered align-middle table-nowrap mb-0">
                        <thead class="border">
                            <tr class="fs-7 fw-bolder text-gray-500">
                                <th class="w-25px ps-3 pe-3">';

            if($total_unchecked > 0) {
                $dataTable .= '<div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input id="inputCheckRowAll" class="form-check-input" type="checkbox" value="0" data-kt-check="true" data-kt-check-target=".widget-9-check">
                    </div>';
            } else {
                $dataTable .= '<div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input id="inputCheckRowAll" class="form-check-input" type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-9-check" checked>
                    </div>';
            }


            $dataTable .= '</th>
                                <th class="min-w-100px ps-3 pe-3">Kode</th>
                                <th class="min-w-150px ps-3 pe-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="border">'.$table_row.'</tbody>
                    </table>
                    </div>
                    <div id="pageOptionFasilitas" class="mt-5">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label>
                                        <select id="selectPerPageOptionFasilitas" name="selectPerPageOptionFasilitas" aria-controls="selectPerPage"
                                            class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                            <option value="10"'.$table_per_page10.'>10</option>
                                            <option value="25"'.$table_per_page25.'>25</option>
                                            <option value="50"'.$table_per_page50.'>50</option>
                                            <option value="100"'.$table_per_page100.'>100</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="dataTables_info" id="selectPerPageInfoOptionFasilitas" role="status" aria-live="polite">Showing <span id="startRecordOptionFasilitas">'.$data_from_record.'</span> to '.$data_to_record.' of '.$data_total_record.' records</div>
                            </div>
                            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers" id="paginateOptionFasilitas">
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

    public function simpanRoomDetail(Request $request) {
        $responseApi = ApiService::RoomDetailSimpan($request->get('data_fasilitas'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));

        return json_decode($responseApi, true);
    }

    public function hapusRoomDetail(Request $request) {
        $responseApi = ApiService::RoomDetailHapus($request->get('kode'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));

        return json_decode($responseApi, true);
    }
}
