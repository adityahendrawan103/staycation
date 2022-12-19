<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class JabatanController extends Controller
{
    public function daftarJabatan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('jabatan')
                    ->selectRaw("ifnull(kode_jabatan, '') as kode_jabatan, ifnull(nama_jabatan, '') as nama_jabatan")
                    ->where('jabatan.companyid', $request->get('companyid'))
                    ->orderBy('jabatan.kode_jabatan', 'asc');

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('jabatan.kode_jabatan', 'like', $request->get('search').'%')
                    ->orWhere('jabatan.nama_jabatan', 'like', $request->get('search').'%');
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailJabatan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_jabatan'  => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data jabatan terlebih dahulu');
            }

            $sql = DB::table('jabatan')
                    ->selectRaw("ifnull(kode_jabatan, '') as kode_jabatan, ifnull(nama_jabatan, '') as nama_jabatan")
                    ->where('jabatan.kode_jabatan', $request->get('kode_jabatan'))
                    ->where('jabatan.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_jabatan)) {
                return ApiResponse::responseWarning('Data jabatan tidak ditemukan');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekJabatanTidakTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_jabatan'  => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode jabatan terlebih dahulu');
            }

            $sql = DB::table('jabatan')
                    ->selectRaw("ifnull(kode_jabatan, '') as kode_jabatan")
                    ->where('kode_jabatan', $request->get('kode_jabatan'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(!empty($sql->kode_jabatan)) {
                return ApiResponse::responseWarning('Kode jabatan sudah terdaftar');
            }

            return ApiResponse::responseSuccess('success', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function cekJabatanTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_jabatan'  => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode jabatan terlebih dahulu');
            }

            $sql = DB::table('jabatan')
                    ->selectRaw("ifnull(kode_jabatan, '') as kode_jabatan, ifnull(nama_jabatan, '') as nama_jabatan")
                    ->where('kode_jabatan', $request->get('kode_jabatan'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_jabatan)) {
                return ApiResponse::responseWarning('Kode jabatan tidak terdaftar');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanJabatan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_jabatan'  => 'required',
                'nama_jabatan'    => 'required',
                'companyid'     => 'required',
                'user_id'       => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data jabatan secara lengkap');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_jabatan_simpan (?,?,?,?)', [
                    trim(strtoupper($request->get('kode_jabatan'))), trim($request->get('nama_jabatan')),
                    trim(strtoupper($request->get('companyid'))), trim(strtoupper($request->get('user_id')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function hapusJabatan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_jabatan'     => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data jabatan terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::delete('call sp_jabatan_hapus (?,?)', [
                    trim(strtoupper($request->get('kode_jabatan'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }
}
