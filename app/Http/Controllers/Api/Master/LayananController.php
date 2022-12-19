<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class LayananController extends Controller
{
    public function daftarLayanan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('layanan')
                    ->selectRaw("ifnull(kode_layanan, '') as kode_layanan, ifnull(nama_layanan, '') as nama_layanan,
                                ifnull(satuan, '') as satuan, ifnull(harga, 0) as harga")
                    ->where('layanan.companyid', $request->get('companyid'))
                    ->orderBy('layanan.kode_layanan', 'asc');

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('layanan.kode_layanan', 'like', $request->get('search').'%')
                    ->orWhere('layanan.nama_layanan', 'like', $request->get('search').'%');
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailLayanan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_layanan'  => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data layanan terlebih dahulu');
            }

            $sql = DB::table('layanan')
                    ->selectRaw("ifnull(kode_layanan, '') as kode_layanan, ifnull(nama_layanan, '') as nama_layanan,
                                ifnull(satuan, '') as satuan, ifnull(harga, 0) as harga")
                    ->where('layanan.kode_layanan', $request->get('kode_layanan'))
                    ->where('layanan.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_layanan)) {
                return ApiResponse::responseWarning('Data layanan tidak ditemukan');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekLayananTidakTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_layanan'  => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode layanan terlebih dahulu');
            }

            $sql = DB::table('layanan')
                    ->selectRaw("ifnull(kode_layanan, '') as kode_layanan")
                    ->where('kode_layanan', $request->get('kode_layanan'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(!empty($sql->kode_layanan)) {
                return ApiResponse::responseWarning('Kode layanan sudah terdaftar');
            }

            return ApiResponse::responseSuccess('success', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function cekLayananTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_layanan'  => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode layanan terlebih dahulu');
            }

            $sql = DB::table('layanan')
                    ->selectRaw("ifnull(kode_layanan, '') as kode_layanan, ifnull(nama_layanan, '') as nama_layanan,
                                ifnull(satuan, '') as satuan, ifnull(harga, 0) as harga")
                    ->where('kode_layanan', $request->get('kode_layanan'))
                    ->where('companyid', $request->get('companyid'))
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

    public function simpanLayanan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_layanan'  => 'required',
                'nama_layanan'  => 'required',
                'satuan'        => 'required',
                'harga'         => 'required',
                'companyid'     => 'required',
                'user_id'       => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data layanan secara lengkap');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_layanan_simpan (?,?,?,?,?,?)', [
                    trim(strtoupper($request->get('kode_layanan'))), trim($request->get('nama_layanan')),
                    strtoupper(trim($request->get('satuan'))), (double)$request->get('harga'),
                    trim(strtoupper($request->get('companyid'))), trim(strtoupper($request->get('user_id')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function hapusLayanan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_layanan'  => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data layanan terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::delete('call sp_layanan_hapus (?,?)', [
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
