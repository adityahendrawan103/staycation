<?php

namespace App\Http\Controllers\Api\Transaksi\Reservasi\CheckIn;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class ReservasiDetailController extends Controller
{
    public function daftarReservasiDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'diskon'            => 'required',
                'ppn'               => 'required',
                'user_id'           => 'required',
                'companyid'         => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('UserId, diskon, ppn, dan company kosong');
            }

            $sql = DB::table('reservasi_detailtmp')
                    ->selectRaw("ifnull(reservasi_detailtmp.kode_key, '') as kode_key,
                                ifnull(reservasi_detailtmp.kode_layanan, '') as kode_layanan,
                                ifnull(layanan.nama_layanan, '') as nama_layanan,
                                ifnull(reservasi_detailtmp.harga, 0) as harga,
                                ifnull(reservasi_detailtmp.qty, 0) as qty,
                                ifnull(reservasi_detailtmp.disc, 0) as disc,
                                ifnull(reservasi_detailtmp.total, 0) as total")
                    ->leftJoin('layanan', function($join) {
                        $join->on('layanan.kode_layanan', '=', 'reservasi_detailtmp.kode_layanan')
                            ->on('layanan.companyid', '=', 'reservasi_detailtmp.companyid');
                    })
                    ->where('reservasi_detailtmp.kode_key', $request->get('user_id'))
                    ->where('reservasi_detailtmp.kode_reservasi', $request->get('kode_reservasi'))
                    ->where('reservasi_detailtmp.companyid', $request->get('companyid'))
                    ->orderBy('reservasi_detailtmp.usertime', 'asc')
                    ->get();

            $kode_key = '';
            $sub_total = 0;
            $data_detail_reservasi = [];

            foreach($sql as $data) {
                $kode_key = strtoupper(trim($data->kode_key));
                $sub_total = (double)$sub_total + (double)$data->total;

                $data_detail_reservasi[] = [
                    'kode_key'      => strtoupper(trim($data->kode_key)),
                    'kode_layanan'  => strtoupper(trim($data->kode_layanan)),
                    'nama_layanan'  => trim($data->nama_layanan),
                    'harga'         => (double)$data->harga,
                    'qty'           => (double)$data->qty,
                    'disc'          => (double)$data->disc,
                    'total'         => (double)$data->total,
                ];
            }

            $diskon_nominal = ((double)$sub_total * (double)$request->get('diskon')) / 100;
            $total_min_diskon = (double)$sub_total - (double)$diskon_nominal;
            $ppn_nominal = ((double)$total_min_diskon * (double)$request->get('ppn')) / 100;
            $total = (double)$total_min_diskon - (double)$ppn_nominal;

            $data = [
                'kode_key'          => $kode_key,
                'kode_reservasi'    => $request->get('kode_reservasi'),
                'sub_total'         => (double)$sub_total,
                'diskon_prosentase' => (double)$request->get('diskon'),
                'diskon_nominal'    => (double)$diskon_nominal,
                'ppn_prosentase'    => (double)$request->get('ppn'),
                'ppn_nominal'       => (double)$ppn_nominal,
                'total'             => (double)$total,
                'layanan'           => $data_detail_reservasi
            ];

            return ApiResponse::responseSuccess('success', $data);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailReservasiDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'kode_layanan'      => 'required',
                'user_id'           => 'required',
                'companyid'         => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih kode layanan terlebih dahulu');
            }

            $sql = DB::table('reservasi_detailtmp')
                    ->selectRaw("ifnull(reservasi_detailtmp.kode_key, '') as kode_key,
                                ifnull(reservasi_detailtmp.kode_reservasi, '') as kode_reservasi,
                                ifnull(reservasi_detailtmp.kode_layanan, '') as kode_layanan,
                                ifnull(layanan.nama_layanan, '') as nama_layanan,
                                ifnull(layanan.satuan, '') as satuan,
                                ifnull(reservasi_detailtmp.harga, 0) as harga,
                                ifnull(reservasi_detailtmp.qty, 0) as qty,
                                ifnull(reservasi_detailtmp.disc, 0) as diskon,
                                ifnull(reservasi_detailtmp.total, 0) as total")
                    ->leftJoin('layanan', function($join) {
                        $join->on('layanan.kode_layanan', '=', 'reservasi_detailtmp.kode_layanan')
                            ->on('layanan.companyid', '=', 'reservasi_detailtmp.companyid');
                    })
                    ->where('reservasi_detailtmp.kode_key', $request->get('user_id'))
                    ->where('reservasi_detailtmp.kode_reservasi', $request->get('kode_reservasi'))
                    ->where('reservasi_detailtmp.kode_layanan', $request->get('kode_layanan'))
                    ->where('reservasi_detailtmp.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_layanan)) {
                return ApiResponse::responseWarning('Kode layanan tidak terdaftar');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanReservasiDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'kode_layanan'      => 'required',
                'jumlah'            => 'required',
                'diskon'            => 'required',
                'user_id'           => 'required',
                'companyid'         => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data detail reservasi secara lengkap');
            }

            $harga_layanan = 0;
            $sql = DB::table('layanan')
                    ->selectRaw("ifnull(layanan.kode_layanan, '') as kode_layanan, ifnull(layanan.harga, 0) as harga ")
                    ->where('layanan.kode_layanan', $request->get('kode_layanan'))
                    ->where('layanan.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_layanan)) {
                return ApiResponse::responseWarning('Kode layanan tidak terdaftar');
            } else {
                $harga_layanan = (double)$sql->harga;
            }

            DB::transaction(function () use ($request, $harga_layanan) {
                DB::insert('call sp_reservasidetailtmp_simpan (?,?,?,?,?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('kode_reservasi'))),
                    trim(strtoupper($request->get('kode_layanan'))), (double)$harga_layanan,
                    (double)$request->get('jumlah'), (double)$request->get('diskon'),
                    trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function hapusReservasiDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'kode_layanan'      => 'required',
                'companyid'         => 'required',
                'user_id'           => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data layanan terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::delete('call sp_reservasidetailtmp_hapus (?,?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('kode_reservasi'))),
                    trim(strtoupper($request->get('kode_layanan'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
