<?php

namespace App\Http\Controllers\App\Transaksi\Reservasi\CheckIn;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class ReservasiController extends Controller
{
    public function daftarReservasiCheckIn(Request $request) {
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

    public function formAddReservasiCheckIn(Request $request) {
        $responseApi = ApiService::CompanyDetail(strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $deposit_default = (double)$data->deposit;
            $ppn_prosentase_reservasi = (double)$data->ppn_prosentase_reservasi;
            $ppn_prosentase_layanan = (double)$data->ppn_prosentase_layanan;

            $responseApi = ApiService::ReservasiCheckInHapusTmp(strtoupper(trim($request->session()->get('app_user_id'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));
            $statusApi = json_decode($responseApi)->status;
            $messageApi =  json_decode($responseApi)->message;

            if($statusApi == 1) {
                $default_tanggal_checkout = Carbon::now();
                $default_tanggal_checkout->addDays(1);

                return view('layouts.transaksi.reservasi.checkin.reservasiform', [
                    'title'                         => 'Tambah Data Reservasi',
                    'default_jam_reservasi'         => $data->shorttime_default,
                    'default_tanggal_check_in'      => date('Y-m-d'),
                    'default_tanggal_check_out'     => $default_tanggal_checkout->format('Y-m-d'),
                    'default_jam_check_in'          => $data->check_in,
                    'default_jam_check_out'         => $data->check_out,
                    'default_layanan_disc_prosentase' => number_format(0, 2),
                    'default_layanan_ppn_prosentase'  => number_format((double)$ppn_prosentase_layanan, 2),
                    'default_deposit'               => (empty($deposit_default)) ? 0 : (double)$deposit_default,
                    'kode_reservasi'                => strtoupper(trim($request->session()->get('app_user_id'))),
                    'tanggal_reservasi'             => date('Y-m-d'),
                    'harga_room'                    => 0,
                    'diskon_room_prosentase'        => number_format(0, 2),
                    'diskon_room_nominal'           => 0,
                    'ppn_room_prosentase'           => number_format((double)$ppn_prosentase_reservasi, 2),
                    'ppn_room_nominal'              => 0,
                    'grand_total_room'              => 0,
                    'biaya_lain'                    => 0,
                    'total_pembayaran'              => 0,
                    'sisa_pembayaran'               => 0,
                    'deposit'                       => 0,
                    'status_longtime'               => 1,
                    'status_in'                     => 0,
                    'status_out'                    => 0,
                    'status_canceled'               => 0,
                ]);
            } else {
                return redirect()->back()->withInput()->with('failed', $messageApi);
            }
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formEditReservasiCheckIn($kode_reservasi, Request $request) {
        $responseApi = ApiService::ReservasiCheckInDetail($kode_reservasi, strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $fasilitas_room = '';
            foreach($data->room->room->fasilitas as $fasilitas) {
                if($fasilitas_room == '') {
                    $fasilitas_room = trim($fasilitas->nama_fasilitas);
                } else {
                    $fasilitas_room .= ', '.trim($fasilitas->nama_fasilitas);
                }
            }

            return view('layouts.transaksi.reservasi.checkin.reservasiform', [
                'title'                     => 'Edit Data Reservasi',
                'default_jam_reservasi'     => $data->default->shorttime,
                'default_jam_check_in'      => $data->default->check_in,
                'default_jam_check_out'     => $data->default->check_out,
                'default_layanan_disc_prosentase' => (double)$data->reservasi->layanan->disc->prosentase,
                'default_layanan_ppn_prosentase'  => (double)$data->reservasi->layanan->ppn->prosentase,
                'default_deposit'           => (double)$data->default->deposit,
                'kode_reservasi'            => strtoupper(trim($data->kode_reservasi)),
                'tanggal_reservasi'         => trim($data->tanggal),
                'kode_platform'             => strtoupper(trim($data->platform->kode)),
                'nama_platform'             => trim($data->platform->nama),
                'nomor_referensi'           => strtoupper(trim($data->platform->no_referensi)),
                'status_longtime'           => (int)$data->status->longtime,
                'tanggal_check_in'          => trim($data->check_in->tanggal),
                'tanggal_check_out'         => trim($data->check_out->tanggal),
                'jam_check_in'              => trim($data->check_in->jam),
                'jam_check_out'             => trim($data->check_out->jam),
                'nama_cp'                   => trim($data->customer->contact_person->nama),
                'telepon_cp'                => trim($data->customer->contact_person->telepon),
                'no_identitas'              => strtoupper(trim($data->customer->no_identitas)),
                'nama_customer'             => trim($data->customer->nama),
                'tanggal_lahir_customer'    => trim($data->customer->tanggal_lahir),
                'kota_customer'             => trim($data->customer->kota),
                'keterangan'                => trim($data->keterangan),
                'catatan'                   => trim($data->catatan),
                'alasan'                    => trim($data->alasan),
                'kode_tipe'                 => strtoupper(trim($data->room->tipe->kode_tipe)),
                'nama_tipe'                 => trim($data->room->tipe->nama),
                'grade'                     => strtoupper(trim($data->room->tipe->grade)),
                'kode_room'                 => strtoupper(trim($data->room->room->kode_room)),
                'harga_longtime'            => (double)$data->room->room->longtime,
                'harga_shorttime'           => (double)$data->room->room->shorttime,
                'fasilitas_room'            => trim($fasilitas_room),
                'harga_room'                => (double)$data->reservasi->room->harga,
                'diskon_room_prosentase'    => (double)$data->reservasi->room->disc->prosentase,
                'diskon_room_nominal'       => (double)$data->reservasi->room->disc->nominal,
                'ppn_room_prosentase'       => (double)$data->reservasi->room->ppn->prosentase,
                'ppn_room_nominal'          => (double)$data->reservasi->room->ppn->nominal,
                'total_room'                => (double)$data->reservasi->room->total,
                'biaya_lain'                => (double)$data->biaya_lain,
                'grand_total'               => (double)$data->grand_total,
                'total_pembayaran'          => (double)$data->pembayaran->total,
                'sisa_pemabayaran'          => (double)$data->pembayaran->sisa,
                'deposit'                   => (double)$data->pembayaran->deposit,
                'status_in'                 => (int)$data->status->in,
                'status_out'                => (int)$data->status->out,
                'status_canceled'           => (int)$data->status->canceled,
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function cekNoReferensiReservasiCheckIn(Request $request) {
        $responseApi = ApiService::ReservasiCheckInCekNoReferensi(strtoupper(trim($request->get('kode_reservasi'))),
                        strtoupper(trim($request->get('no_referensi'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function checkInReservasiCheckIn(Request $request) {
        $responseApi = ApiService::ReservasiCheckInCheckIn(strtoupper(trim($request->get('kode_reservasi'))),
            trim($request->get('password')), trim($request->get('password_confirm')),
            strtoupper(trim($request->session()->get('app_user_company_id'))),
            strtoupper(trim($request->session()->get('app_user_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanReservasiCheckIn(Request $request) {
        $status_longtime = 0;
        $status_check_in = 0;
        if($request->get('btnSimpan') == 'check_in') {
            $status_check_in = 1;
        }
        if(strtoupper(trim($request->get('status_longtime'))) == 'ON') {
            $status_longtime = 1;
        }

        $responseApi = ApiService::ReservasiCheckInSimpan(strtoupper(trim($request->get('kode_reservasi'))),
            strtoupper(trim($request->get('no_identitas'))), strtoupper(trim($request->get('kode_room'))),
            strtoupper(trim($request->get('kode_platform'))), strtoupper(trim($request->get('nomor_referensi'))),
            trim($request->get('nama_cp')), trim($request->get('telepon_cp')), $status_longtime,
            trim($request->get('tanggal_check_in')), trim($request->get('tanggal_check_out')),
            trim($request->get('jam_check_in')), trim($request->get('jam_check_out')),
            (double)str_replace(',', '', trim($request->get('diskon_room_prosentase'))),
            (double)str_replace(',', '', trim($request->get('ppn_room_prosentase'))),
            (double)str_replace(',', '', trim($request->get('diskon_layanan_prosentase'))),
            (double)str_replace(',', '', trim($request->get('ppn_layanan_prosentase'))),
            (double)str_replace(',', '', trim($request->get('biaya_lain'))),
            (double)str_replace(',', '', trim($request->get('total_pembayaran'))),
            (double)str_replace(',', '', trim($request->get('deposit'))),
            trim($request->get('keterangan')), trim($request->get('catatan')),
            trim($request->get('alasan')), $status_check_in,
            strtoupper(trim($request->session()->get('app_user_company_id'))),
            strtoupper(trim($request->session()->get('app_user_id'))));

        return json_decode($responseApi, true);
    }

    public function hapusReservasiCheckIn(Request $request) {
        $responseApi = ApiService::ReservasiCheckInHapus(strtoupper(trim($request->get('kode_reservasi'))),
            strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
