<?php

namespace App\Http\Controllers\App\Transaksi\Reservasi\CheckOut;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReservasiCheckOutController extends Controller
{
    public function daftarReservasiCheckOut(Request $request) {
        $responseApi = ApiService::ReservasiInHouseDaftar($request->get('page'),
                        $request->get('per_page'), $request->get('filter'), $request->get('search'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $data_page = new Collection();
            $data_page->push((object) [
                'search'        => $request->get('search'),
                'current_page'  => $data->from,
                'from'          => $data->from,
                'to'            => $data->to,
                'total'         => $data->total,
                'page'          => $data->current_page,
                'per_page'      => $data->per_page
            ]);

            $data_filter = new Collection();
            $data_filter->push((object) [
                'filter'    => trim($request->get('filter')),
                'search'    => trim($request->get('search'))
            ]);

            return view ('layouts.transaksi.reservasi.checkout.reservasicheckout', [
                'data_reservasi'    => $data,
                'data_filter'       => $data_filter->first(),
                'data_page'         => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formCheckOutReservasi($kode_reservasi, Request $request) {
        $responseApi = ApiService::ReservasiCheckOutDetail($kode_reservasi,
                strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $dataApi = json_decode($responseApi)->data;

            return view ('layouts.transaksi.reservasi.checkout.reservasicheckoutform', [
                'title'     => 'Data Reservasi',
                'data'      => $dataApi
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function cekPembayaranReservasiCheckOut(Request $request) {
        $responseApi = ApiService::ReservasiCheckOutCekPembayaran(strtoupper(trim($request->get('kode_reservasi'))),
                strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanReservasiCheckOut(Request $request) {
        $responseApi = ApiService::ReservasiCheckOutSimpan(strtoupper(trim($request->get('kode_reservasi'))),
                trim($request->get('password')), trim($request->get('password_confirm')),
                strtoupper(trim($request->session()->get('app_user_company_id'))),
                strtoupper(trim($request->session()->get('app_user_id'))));

        return json_decode($responseApi, true);
    }
}
