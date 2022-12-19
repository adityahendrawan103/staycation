<?php

namespace App\Http\Controllers\Api\Transaksi\Reservasi\Penalty;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class ReservasiPenaltyDetailController extends Controller
{
    public function daftarReservasiPenaltyDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'user_id'           => 'required',
                'companyid'         => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('UserId dan company kosong');
            }

            $sql = DB::table('penaltytmp')
                    ->selectRaw("ifnull(penaltytmp.kode_key, '') as kode_key,
                                ifnull(penaltytmp.kode_item, '') as kode_item,
                                ifnull(item.nama_item, '') as nama_item,
                                ifnull(penaltytmp.keterangan, '') as keterangan,
                                ifnull(penaltytmp.qty, '') as qty,
                                ifnull(penaltytmp.denda, 0) as denda,
                                ifnull(penaltytmp.total, 0) as total")
                    ->leftJoin('item', function($join) {
                        $join->on('item.kode_item', '=', 'penaltytmp.kode_item')
                            ->on('item.companyid', '=', 'penaltytmp.companyid');
                    })
                    ->where('penaltytmp.kode_key', $request->get('user_id'))
                    ->where('penaltytmp.kode_reservasi', $request->get('kode_reservasi'))
                    ->where('penaltytmp.companyid', $request->get('companyid'))
                    ->orderBy('penaltytmp.usertime', 'desc')
                    ->get();

            $kode_key = '';
            $total_denda = 0;
            $data_detail_reservasi = [];

            foreach($sql as $data) {
                $kode_key = strtoupper(trim($data->kode_key));
                $total_denda = (double)$total_denda + ((double)$data->denda * (double)$data->qty);

                $data_detail_reservasi[] = [
                    'kode_key'      => strtoupper(trim($data->kode_key)),
                    'kode_item'     => strtoupper(trim($data->kode_item)),
                    'nama_item'     => trim($data->nama_item),
                    'keterangan'    => trim($data->keterangan),
                    'denda'         => (double)$data->denda,
                    'qty'           => (double)$data->qty,
                    'total'         => (double)$data->total,
                ];
            }

            $data = [
                'kode_key'          => $kode_key,
                'total_denda'       => (double)$total_denda,
                'penalty'           => $data_detail_reservasi
            ];

            return ApiResponse::responseSuccess('success', $data);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailReservasiPenaltyDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'kode_item'         => 'required',
                'user_id'           => 'required',
                'companyid'         => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih kode item terlebih dahulu');
            }

            $sql = DB::table('penaltytmp')
                    ->selectRaw("ifnull(penaltytmp.kode_key, '') as kode_key,
                                ifnull(penaltytmp.kode_reservasi, '') as kode_reservasi,
                                ifnull(penaltytmp.kode_item, '') as kode_item,
                                ifnull(item.nama_item, '') as nama_item,
                                ifnull(penaltytmp.keterangan, '') as keterangan,
                                ifnull(penaltytmp.denda, 0) as denda,
                                ifnull(penaltytmp.qty, 0) as qty,
                                ifnull(penaltytmp.total, 0) as total")
                    ->leftJoin('item', function($join) {
                        $join->on('item.kode_item', '=', 'penaltytmp.kode_item')
                            ->on('item.companyid', '=', 'penaltytmp.companyid');
                    })
                    ->where('penaltytmp.kode_key', $request->get('user_id'))
                    ->where('penaltytmp.kode_reservasi', $request->get('kode_reservasi'))
                    ->where('penaltytmp.kode_item', $request->get('kode_item'))
                    ->where('penaltytmp.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_item)) {
                return ApiResponse::responseWarning('Kode item tidak terdaftar');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanReservasiPenaltyDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'kode_item'         => 'required',
                'keterangan'        => 'required',
                'qty'               => 'required',
                'denda'             => 'required',
                'user_id'           => 'required',
                'companyid'         => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data detail penalty reservasi secara lengkap');
            }

            $sql = DB::table('item')
                    ->selectRaw("ifnull(item.kode_item, '') as kode_item")
                    ->where('kode_item', $request->get('kode_item'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_item)) {
                return ApiResponse::responseWarning('Kode item tidak terdaftar');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_reservasipenaltytmp_simpan (?,?,?,?,?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('kode_reservasi'))),
                    trim(strtoupper($request->get('kode_item'))), trim($request->get('keterangan')), (double)$request->get('qty'),
                    (double)$request->get('denda'), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function hapusReservasiPenaltyDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'kode_item'         => 'required',
                'companyid'         => 'required',
                'user_id'           => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data item terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::delete('call sp_reservasipenaltytmp_hapus (?,?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('kode_reservasi'))),
                    trim(strtoupper($request->get('kode_item'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
