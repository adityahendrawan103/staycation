<?php

namespace App\Http\Controllers\App\Transaksi\Refund;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class RefundController extends Controller
{
    public function daftarReservasiRefund(Request $request) {
        $responseApi = ApiService::ReservasiCheckInDaftar($request->get('page'),
                        $request->get('per_page'), $request->get('status'),
                        $request->get('filter'), $request->get('search'),
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
                'status'    => (empty($request->get('status'))) ? 'ALL' : strtoupper(trim($request->get('status'))),
                'filter'    => trim($request->get('filter')),
                'search'    => trim($request->get('search'))
            ]);

            return view ('layouts.transaksi.reservasi.checkin.reservasi', [
                'data_reservasi'    => $data,
                'data_filter'       => $data_filter->first(),
                'data_page'         => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }
}
