<?php

namespace App\Http\Controllers\App\Transaksi\Reservasi\InHouse;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReservasiInHouseController extends Controller
{
    public function daftarReservasiInHouse(Request $request) {
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

            return view ('layouts.transaksi.reservasi.inhouse.reservasiinhouse', [
                'data_reservasi'    => $data,
                'data_filter'       => $data_filter->first(),
                'data_page'         => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formReservasiInHouse($kode_reservasi, Request $request) {
        $responseApi = ApiService::ReservasiInHouseDetail($kode_reservasi, strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            return view('layouts.transaksi.reservasi.inhouse.reservasiinhouseform', [
                'title'                     => 'Edit Data Reservasi',
                'default_jam_reservasi'     => $data->default->shorttime,
                'default_jam_check_in'      => $data->default->check_in,
                'default_jam_check_out'     => $data->default->check_out,
                'default_layanan_disc_prosentase' => (double)$data->reservasi->layanan->disc->prosentase,
                'default_layanan_ppn_prosentase'  => (double)$data->reservasi->layanan->ppn->prosentase,
                'kode_reservasi'            => strtoupper(trim($data->kode_reservasi)),
                'tanggal_reservasi'         => trim($data->tanggal),
                'nama_platform'             => trim($data->platform->nama),
                'status_longtime'           => (int)$data->status->longtime,
                'tanggal_check_in'          => trim($data->check_in->tanggal),
                'tanggal_check_out'         => trim($data->check_out->tanggal),
                'jam_check_in'              => trim($data->check_in->jam),
                'jam_check_out'             => trim($data->check_out->jam),
                'lama_inap'                 => (double)$data->reservasi->room->lama_inap,
                'no_identitas'              => strtoupper(trim($data->customer->no_identitas)),
                'nama_customer'             => trim($data->customer->nama),
                'kota_customer'             => trim($data->customer->kota),
                'keterangan'                => trim($data->keterangan),
                'catatan'                   => trim($data->catatan),
                'alasan'                    => trim($data->alasan),
                'kode_tipe'                 => strtoupper(trim($data->room->tipe->kode_tipe)),
                'nama_tipe'                 => trim($data->room->tipe->nama),
                'kode_room'                 => strtoupper(trim($data->room->room->kode_room)),
                'harga_room'                => (double)$data->reservasi->room->harga,
                'sub_total_room'            => (double)$data->reservasi->room->sub_total,
                'diskon_room_prosentase'    => (double)$data->reservasi->room->disc->prosentase,
                'diskon_room_nominal'       => (double)$data->reservasi->room->disc->nominal,
                'ppn_room_prosentase'       => (double)$data->reservasi->room->ppn->prosentase,
                'ppn_room_nominal'          => (double)$data->reservasi->room->ppn->nominal,
                'total_room'                => (double)$data->reservasi->room->total,
                'total_layanan'             => (double)$data->reservasi->layanan->total,
                'biaya_lain'                => (double)$data->biaya_lain,
                'grand_total'               => (double)$data->grand_total,
                'total_pembayaran'          => (double)$data->pembayaran->total,
                'sisa_pemabayaran'          => (double)$data->pembayaran->sisa,
                'status_in'                 => (int)$data->status->in,
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function simpanEditRoom(Request $request) {
        $responseApi = ApiService::ReservasiInHouseSimpanEditRoom($request->get('kode_reservasi'),
                $request->get('kode_room'), $request->get('alasan'), $request->get('diskon_room_prosentase'),
                strtoupper(trim($request->session()->get('app_user_company_id'))),
                strtoupper(trim($request->session()->get('app_user_id'))));

        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('transaksi.reservasi.inhouse.form-reservasi-inhouse', trim($request->get('kode_reservasi')))->with('success', $messageApi);
        } else {
            return redirect()->route('transaksi.reservasi.inhouse.form-reservasi-inhouse', trim($request->get('kode_reservasi')))->with('failed', $messageApi);
        }
    }

    public function simpanExtendRoom(Request $request) {
        $responseApi = ApiService::ReservasiInHouseSimpanExtendRoom($request->get('kode_reservasi'),
                $request->get('tanggal_check_out').' '.$request->get('jam_check_out'),
                strtoupper(trim($request->session()->get('app_user_company_id'))),
                strtoupper(trim($request->session()->get('app_user_id'))));

        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('transaksi.reservasi.inhouse.form-reservasi-inhouse', trim($request->get('kode_reservasi')))->with('success', $messageApi);
        } else {
            return redirect()->route('transaksi.reservasi.inhouse.form-reservasi-inhouse', trim($request->get('kode_reservasi')))->with('failed', $messageApi);
        }
    }

    public function simpanReservasiInHouse(Request $request) {
        $responseApi = ApiService::ReservasiInHouseSimpan($request->get('kode_reservasi'),
                (double)str_replace(',', '', trim($request->get('diskon_layanan_prosentase'))),
                (double)str_replace(',', '', trim($request->get('ppn_layanan_prosentase'))),
                (double)str_replace(',', '', trim($request->get('biaya_lain'))),
                (double)str_replace(',', '', trim($request->get('total_pembayaran'))),
                trim($request->get('catatan')),
                strtoupper(trim($request->session()->get('app_user_company_id'))),
                strtoupper(trim($request->session()->get('app_user_id'))));

        return json_decode($responseApi, true);
    }
}
