<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class FasilitasController extends Controller
{
    public function daftarFasilitas(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('fasilitas')
                    ->selectRaw("ifnull(kode_fasilitas, '') as kode_fasilitas,
                                ifnull(nama_fasilitas, '') as nama_fasilitas")
                    ->where('companyid', $request->get('companyid'))
                    ->orderBy('kode_fasilitas', 'asc');

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('kode_fasilitas', 'like', $request->get('search').'%')
                    ->orWhere('nama_fasilitas', 'like', $request->get('search').'%');
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailFasilitas(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_fasilitas'    => 'required',
                'companyid'         => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data fasilitas ruangan terlebih dahulu');
            }

            $sql = DB::table('fasilitas')
                    ->selectRaw("ifnull(kode_fasilitas, '') as kode_fasilitas,
                                ifnull(nama_fasilitas, '') as nama_fasilitas")
                    ->where('fasilitas.kode_fasilitas', $request->get('kode_fasilitas'))
                    ->where('fasilitas.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_fasilitas)) {
                return ApiResponse::responseWarning('Data fasilitas tidak ditemukan');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekKodeFasilitasTidakTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_fasilitas' => 'required',
                'companyid'      => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode fasilitas terlebih dahulu');
            }

            $sql = DB::table('fasilitas')
                    ->selectRaw("ifnull(kode_fasilitas, '') as kode_fasilitas")
                    ->where('kode_fasilitas', $request->get('kode_fasilitas'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(!empty($sql->kode_fasilitas)) {
                return ApiResponse::responseWarning('Kode fasilitas sudah terdaftar');
            }

            return ApiResponse::responseSuccess('success', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function cekKodeFasilitasTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_fasilitas' => 'required',
                'companyid'      => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode fasilitas terlebih dahulu');
            }

            $sql = DB::table('fasilitas')
                    ->selectRaw("ifnull(kode_fasilitas, '') as kode_fasilitas,
                                ifnull(nama_fasilitas, '') as nama_fasilitas")
                    ->where('kode_fasilitas', $request->get('kode_fasilitas'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_fasilitas)) {
                return ApiResponse::responseWarning('Kode fasilitas tidak terdaftar');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanFasilitas(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_fasilitas' => 'required',
                'nama_fasilitas'     => 'required',
                'companyid'      => 'required',
                'user_id'        => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data fasilitas secara lengkap');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_fasilitas_simpan (?,?,?,?)', [
                    trim(strtoupper($request->get('kode_fasilitas'))), trim($request->get('nama_fasilitas')),
                    trim(strtoupper($request->get('companyid'))), trim(strtoupper($request->get('user_id')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function hapusFasilitas(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_fasilitas' => 'required',
                'companyid'      => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data fasilitas terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::delete('call sp_fasilitas_hapus (?,?)', [
                    trim(strtoupper($request->get('kode_fasilitas'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }
}
