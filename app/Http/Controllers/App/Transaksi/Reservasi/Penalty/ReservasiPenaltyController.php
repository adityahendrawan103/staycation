<?php

namespace App\Http\Controllers\App\Transaksi\Reservasi\Penalty;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReservasiPenaltyController extends Controller
{
    public function daftarReservasiPenalty(Request $request) {
        $responseApi = ApiService::ReservasiPenaltyDaftar($request->get('page'),
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

            return view ('layouts.transaksi.reservasi.penalty.reservasipenalty', [
                'data_reservasi'    => $data,
                'data_filter'       => $data_filter->first(),
                'data_page'         => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formReservasiPenalty($kode_reservasi, Request $request) {
        $responseApi = ApiService::ReservasiPenaltyForm($kode_reservasi, strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            return view('layouts.transaksi.reservasi.penalty.reservasipenaltyform', [
                'title'                     => 'Edit Data Reservasi',
                'kode_reservasi'            => strtoupper(trim($data->kode_reservasi)),
                'tanggal_reservasi'         => trim($data->tanggal),
                'tanggal_check_in'          => trim($data->check_in->tanggal),
                'tanggal_check_out'         => trim($data->check_out->tanggal),
                'jam_check_in'              => trim($data->check_in->jam),
                'jam_check_out'             => trim($data->check_out->jam),
                'no_identitas'              => strtoupper(trim($data->customer->no_identitas)),
                'nama_customer'             => trim($data->customer->nama),
                'kota_customer'             => trim($data->customer->kota),
                'kode_tipe'                 => strtoupper(trim($data->room->kode_tipe)),
                'nama_tipe'                 => trim($data->room->nama),
                'grade'                     => strtoupper(trim($data->room->grade)),
                'kode_room'                 => strtoupper(trim($data->room->kode_room)),
                'penalty'                   => (double)$data->penalty,
                'total_pembayaran'          => (double)$data->total_pembayaran,
                'sisa_pembayaran'           => (double)$data->sisa_pembayaran,
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function simpanReservasiPenalty(Request $request) {
        $responseApi = ApiService::ReservasiPenaltySimpan(strtoupper(trim($request->get('kode_reservasi'))),
            (double)str_replace(',', '', trim($request->get('total_pembayaran'))),
            strtoupper(trim($request->session()->get('app_user_company_id'))),
            strtoupper(trim($request->session()->get('app_user_id'))));

        return json_decode($responseApi, true);
    }

    public function hapusReservasiPenalty(Request $request) {
        $responseApi = ApiService::ReservasiPenaltyHapus(strtoupper(trim($request->get('kode_reservasi'))),
                strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
