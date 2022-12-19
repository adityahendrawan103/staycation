<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class ItemController extends Controller
{
    public function daftarItem(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('item')
                    ->selectRaw("ifnull(kode_item, '') as kode_item, ifnull(nama_item, '') as nama_item,
                                ifnull(harga_denda, 0) as harga_denda")
                    ->where('item.companyid', $request->get('companyid'))
                    ->orderBy('item.kode_item', 'asc');

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('item.kode_item', 'like', $request->get('search').'%')
                    ->orWhere('item.nama_item', 'like', $request->get('search').'%');
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailItem(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_item'     => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data item terlebih dahulu');
            }

            $sql = DB::table('item')
                    ->selectRaw("ifnull(kode_item, '') as kode_item, ifnull(nama_item, '') as nama_item,
                                ifnull(harga_denda, 0) as harga_denda")
                    ->where('item.kode_item', $request->get('kode_item'))
                    ->where('item.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_item)) {
                return ApiResponse::responseWarning('Data item tidak ditemukan');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekItemTidakTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_item'     => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode item terlebih dahulu');
            }

            $sql = DB::table('item')
                    ->selectRaw("ifnull(kode_item, '') as kode_item")
                    ->where('kode_item', $request->get('kode_item'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(!empty($sql->kode_item)) {
                return ApiResponse::responseWarning('Kode item sudah terdaftar');
            }

            return ApiResponse::responseSuccess('success', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function cekItemTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_item'     => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi kode item terlebih dahulu');
            }

            $sql = DB::table('item')
                    ->selectRaw("ifnull(kode_item, '') as kode_item, ifnull(nama_item, '') as nama_item,
                                ifnull(harga_denda, 0) as denda")
                    ->where('kode_item', $request->get('kode_item'))
                    ->where('companyid', $request->get('companyid'))
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

    public function simpanItem(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_item'     => 'required',
                'nama_item'     => 'required',
                'denda'         => 'required',
                'companyid'     => 'required',
                'user_id'       => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data item secara lengkap');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_item_simpan (?,?,?,?,?)', [
                    trim(strtoupper($request->get('kode_item'))), trim($request->get('nama_item')), (double)$request->get('denda'),
                    trim(strtoupper($request->get('companyid'))), trim(strtoupper($request->get('user_id')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }

    }

    public function hapusItem(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_item'     => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data item terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::delete('call sp_item_hapus (?,?)', [
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
