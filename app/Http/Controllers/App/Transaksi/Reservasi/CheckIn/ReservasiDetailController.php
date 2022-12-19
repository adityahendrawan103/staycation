<?php

namespace App\Http\Controllers\App\Transaksi\Reservasi\CheckIn;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ReservasiDetailController extends Controller
{
    public function daftarReservasiCheckInDetail(Request $request) {
        $responseApi = ApiService::ReservasiCheckInDetailDaftar($request->get('kode_reservasi'),
                (double)$request->get('diskon_layanan_prosentase'), (double)$request->get('ppn_layanan_prosentase'),
                strtoupper(trim($request->session()->get('app_user_id'))),
                strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi = json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $dataRow = '';
            $decimalInput = "this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');";

            foreach($data->layanan as $detail) {
                $dataRow .= '<tr class="fs-6 fw-bold text-gray-700">
                    <td class="ps-3 pe-3 text-center">
                        <button id="btnEditDetail" class="btn btn-icon btn-light-primary btn-sm mt-1" data-kode='.strtoupper(trim($detail->kode_layanan)).'
                            data-nama_layanan='.trim($detail->nama_layanan).' data-harga='.number_format($detail->harga).' data-qty='.number_format($detail->qty).'
                            data-diskon='.number_format($detail->disc, 2).'>
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                    <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                </svg>
                            </span>
                        </button>
                        <button id="btnHapusDetail" class="btn btn-icon btn-light-danger btn-sm mt-1" data-kode='.strtoupper(trim($detail->kode_layanan)).'>
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                    <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                    <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                                </svg>
                            </span>
                        </button>
                    </td>
                    <td class="ps-3 pe-3">'.strtoupper(trim($detail->kode_layanan)).'</td>
                    <td class="ps-3 pe-3">'.trim($detail->nama_layanan).'</td>
                    <td class="ps-3 pe-3 text-end">'.number_format($detail->harga).'</td>
                    <td class="ps-3 pe-3 text-end">'.number_format($detail->qty).'</td>
                    <td class="ps-3 pe-3 text-end">'.number_format($detail->disc, 2).'</td>
                    <td class="ps-3 pe-3 text-end">'.number_format($detail->total).'</td>
                </tr>';
            }

            if($dataRow == '') {
                $dataRow = '<tr class="fw-bolder text-muted fs-6">
                    <td class="text-center p-20" colspan="7">- TIDAK ADA DATA YANG DITAMPILKAN -</td>
                </tr>';
            }

            $dataTable = '<div id="tableDetailReservasi" class="table-responsive">
            <table class="table table-bordered align-middle table-nowrap mb-0">
                <thead class="border">
                    <tr class="fs-7 fw-bolder text-gray-500">
                        <th class="w-100px ps-3 pe-3 text-center">Action</th>
                        <th class="w-100px ps-3 pe-3">Kode</th>
                        <th class="min-w-150px ps-3 pe-3">Keterangan</th>
                        <th class="w-150px m ps-3 pe-3 text-end">Harga</th>
                        <th class="w-100px ps-3 pe-3 text-end">Qty</th>
                        <th class="w-100px ps-3 pe-3 text-end">Disc(%)</th>
                        <th class="w-150px ps-3 pe-3 text-end">Total</th>
                    </tr>
                </thead>
                <tbody class="border">'.$dataRow.'</tbody>
                <tfoot class="border">
                    <tr>
                        <td colspan="4" class="fw-bolder text-muted text-end fs-6 ps-3 pe-3">Sub Total</td>
                        <td colspan="3" class="fw-bolder text-dark text-end fs-6 ps-3 pe-3">
                            <input id="inputTotalLayanan" name="total_layanan" type="text" class="form-control form-control-solid text-end" readonly
                                oninput="'.$decimalInput.'" value="'.number_format($data->sub_total).'">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="fw-bolder text-muted text-end fs-6 ps-3 pe-3">Diskon (%)</td>
                        <td class="fw-bolder text-dark fs-6">
                            <input id="inputDiskonLayananProsentase" name="diskon_layanan_prosentase" type="text" class="form-control text-end" required
                                maxlength="5" oninput="'.$decimalInput.'" value="'.number_format($data->diskon_prosentase, 2).'">
                        </td>
                        <td colspan="2" class="fw-bolder text-dark text-end fs-6 ps-3 pe-3">
                            <input id="inputDiskonLayananNominal" name="diskon_layanan_nominal" type="text" class="form-control form-control-solid text-end" value="0" readonly
                                oninput="'.$decimalInput.'" value="'.number_format($data->diskon_nominal).'">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="fw-bolder text-muted text-end fs-6 ps-3 pe-3">PPN (%)</td>
                        <td class="fw-bolder text-dark fs-6">
                            <input id="inputPPNLayananProsentase" name="ppn_layanan_prosentase" type="text" class="form-control form-control-solid text-end" readonly
                                maxlength="5" oninput="'.$decimalInput.'" value="'.number_format($data->ppn_prosentase, 2).'">
                        </td>
                        <td colspan="2" class="fw-bolder text-dark text-end fs-6 ps-3 pe-3">
                            <input id="inputPPNLayananNominal" name="ppn_layanan_nominal" type="text" class="form-control form-control-solid text-end" readonly
                                oninput="'.$decimalInput.'" value="'.number_format($data->ppn_nominal).'">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="fw-bolder text-muted text-end fs-6 ps-3 pe-3">Total Layanan</td>
                        <td colspan="3" class="fw-bolder text-dark text-end fs-6 ps-3 pe-3">
                            <input id="inputGrandTotalLayanan" name="grand_total_layanan" type="text" class="form-control form-control-solid text-end" readonly
                                oninput="'.$decimalInput.'" value="'.number_format($data->total).'">
                        </td>
                    </tr>
                </tfoot>
            </table>
            </div>';

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $dataTable]);
        } else {
            return response()->json(['status' => 0, 'message' => $messageApi]);
        }
    }

    public function formReservasiCheckInDetail(Request $request) {
        $responseApi = ApiService::ReservasiCheckInDetailForm($request->get('kode_reservasi'), $request->get('kode_layanan'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanReservasiCheckInDetail(Request $request) {
        $responseApi = ApiService::ReservasiCheckInDetailSimpan($request->get('kode_reservasi'),
                        $request->get('kode_layanan'), (double)$request->get('jumlah'), (double)$request->get('diskon'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));

        return json_decode($responseApi, true);
    }

    public function hapusReservasiCheckInDetail(Request $request) {
        $responseApi = ApiService::ReservasiCheckInDetailHapus($request->get('kode_reservasi'),
                        $request->get('kode_layanan'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));

        return json_decode($responseApi, true);
    }
}
