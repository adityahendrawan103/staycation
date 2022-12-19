<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class PlatformController extends Controller
{
    public function daftarPlatform(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('platform')
                    ->selectRaw("ifnull(kode_platform, '') as kode_platform, ifnull(nama_platform, '') as nama_platform")
                    ->where('platform.companyid', $request->get('companyid'))
                    ->orderBy('platform.kode_platform', 'asc');

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('platform.kode_platform', 'like', $request->get('search').'%')
                    ->orWhere('platform.nama_platform', 'like', $request->get('search').'%');
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailPlatform(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_platform'     => 'required',
                'companyid'         => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data platform terlebih dahulu');
            }

            $sql = DB::table('platform')
                    ->selectRaw("ifnull(kode_platform, '') as kode_platform, ifnull(nama_platform, '') as nama_platform")
                    ->where('platform.kode_platform', $request->get('kode_platform'))
                    ->where('platform.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_platform)) {
                return ApiResponse::responseWarning('Data platform tidak ditemukan');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekPlatformTidakTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_platform' => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode platform terlebih dahulu');
            }

            $sql = DB::table('platform')
                    ->selectRaw("ifnull(kode_platform, '') as kode_platform")
                    ->where('kode_platform', $request->get('kode_platform'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(!empty($sql->kode_platform)) {
                return ApiResponse::responseWarning('Kode platform sudah terdaftar');
            }

            return ApiResponse::responseSuccess('success', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function cekPlatformTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_platform' => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode platform terlebih dahulu');
            }

            $sql = DB::table('platform')
                    ->selectRaw("ifnull(kode_platform, '') as kode_platform, ifnull(nama_platform, '') as nama_platform")
                    ->where('kode_platform', $request->get('kode_platform'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_platform)) {
                return ApiResponse::responseWarning('Kode platform tidak terdaftar');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanPlatform(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_platform' => 'required',
                'nama_platform'    => 'required',
                'companyid'     => 'required',
                'user_id'       => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data platform secara lengkap');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_platform_simpan (?,?,?,?)', [
                    trim(strtoupper($request->get('kode_platform'))), trim($request->get('nama_platform')),
                    trim(strtoupper($request->get('companyid'))), trim(strtoupper($request->get('user_id')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function hapusPlatform(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_platform' => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data platform terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::delete('call sp_platform_hapus (?,?)', [
                    trim(strtoupper($request->get('kode_platform'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }
}
