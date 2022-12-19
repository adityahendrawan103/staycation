<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class RoomTipeController extends Controller
{
    public function daftarRoomTipe(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('room_tipe')
                    ->selectRaw("ifnull(kode_tipe, '') as kode,
                                ifnull(nama_tipe, '') as nama_tipe,
                                ifnull(grade, '') as grade,
                                ifnull(harga, 0) as harga")
                    ->where('companyid', $request->get('companyid'))
                    ->orderBy('kode_tipe', 'asc');

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('kode_tipe', 'like', $request->get('search').'%')
                    ->orWhere('nama_tipe', 'like', $request->get('search').'%');
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailRoomTipe(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_tipe'     => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data tipe ruangan terlebih dahulu');
            }

            $sql = DB::table('room_tipe')
                    ->selectRaw("ifnull(kode_tipe, '') as kode_tipe, ifnull(nama_tipe, '') as nama_tipe,
                            ifnull(grade, '') as grade, ifnull(harga, 0) as harga")
                    ->where('room_tipe.kode_tipe', $request->get('kode_tipe'))
                    ->where('room_tipe.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_tipe)) {
                return ApiResponse::responseWarning('Data tipe ruangan tidak ditemukan');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekKodeRoomTipeTidakTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_tipe'     => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode tipe ruangan terlebih dahulu');
            }

            $sql = DB::table('room_tipe')
                    ->selectRaw("ifnull(kode_tipe, '') as kode_tipe")
                    ->where('kode_tipe', $request->get('kode_tipe'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(!empty($sql->kode_tipe)) {
                return ApiResponse::responseWarning('Kode tipe ruangan sudah terdaftar');
            }

            return ApiResponse::responseSuccess('success', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function cekKodeRoomTipeTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_tipe'     => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode tipe ruangan terlebih dahulu');
            }

            $sql = DB::table('room_tipe')
                    ->selectRaw("ifnull(kode_tipe, '') as kode_tipe,
                                ifnull(nama_tipe, '') as nama_tipe")
                    ->where('kode_tipe', $request->get('kode_tipe'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_tipe)) {
                return ApiResponse::responseWarning('Kode tipe ruangan tidak terdaftar');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanRoomTipe(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_tipe'     => 'required',
                'nama_tipe'    => 'required',
                'grade'         => 'required',
                'harga'         => 'required',
                'user_id'       => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data tipe ruangan secara lengkap');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_roomtipe_simpan (?,?,?,?,?,?)', [
                    trim(strtoupper($request->get('kode_tipe'))), trim($request->get('nama_tipe')),
                    trim(strtoupper($request->get('grade'))), (double)$request->get('harga'),
                    trim(strtoupper($request->get('companyid'))), trim(strtoupper($request->get('user_id')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function hapusRoomTipe(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_tipe'     => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data fasilitas terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::delete('call sp_roomtipe_hapus (?,?)', [
                    trim(strtoupper($request->get('kode_tipe'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }
}
