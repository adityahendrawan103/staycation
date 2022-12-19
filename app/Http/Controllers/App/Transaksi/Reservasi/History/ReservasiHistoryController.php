<?php

namespace App\Http\Controllers\App\Transaksi\Reservasi\History;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReservasiHistoryController extends Controller
{
    public function daftarReservasiHistory(Request $request) {
        $start_date = new Carbon('first day of last month');
        $end_date = new Carbon('last day of last month');

        $responseApi = ApiService::ReservasiHistoryDaftar($start_date, $end_date, $request->get('page'), $request->get('per_page'),
                        $request->get('filter'), $request->get('search'), $request->get('sortby'), $request->get('ascdesc'),
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
                'start_date'    => $start_date,
                'end_date'      => $end_date,
                'filter'        => trim($request->get('filter')),
                'filter_date'   => $request->get('filter') ?? 'TANGGALRESERVASI',
                'search'        => trim($request->get('search')),
                'sortby'        => $request->get('sortby') ?? 'KODERESERVASI',
                'ascdesc'       => $request->get('ascdesc') ?? 'asc',
            ]);

            return view ('layouts.transaksi.reservasi.history.reservasihistory', [
                'data_reservasi'    => $data,
                'data_filter'       => $data_filter->first(),
                'data_page'         => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formHistoryReservasi($kode_reservasi, Request $request) {
        $responseApi = ApiService::ReservasiHistoryDetail($kode_reservasi,
                strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $dataApi = json_decode($responseApi)->data;

            return view ('layouts.transaksi.reservasi.history.reservasihistoryform', [
                'title'     => 'Data Reservasi',
                'data'      => $dataApi
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }
}
