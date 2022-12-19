<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class CustomerController extends Controller
{
    public function daftarCustomer(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('customer')
                    ->selectRaw("ifnull(no_identitas, '') as no_identitas, ifnull(nama, '') as nama, ifnull(jenis_identitas, '') as jenis_identitas,
                                ifnull(jenis_kelamin, '') as jenis_kelamin, ifnull(alamat, '') as alamat, ifnull(kota, '') as kota,
                                ifnull(date_format(tanggal_lahir, '%Y-%m-%d'), '') as tanggal_lahir, ifnull(telepon, '') as telepon,
                                ifnull(email, '') as email, ifnull(date_format(created_at, '%Y-%m-%d'), '') as tanggal_registrasi")
                    ->where('customer.companyid', $request->get('companyid'))
                    ->orderBy('customer.created_at', 'desc');

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('customer.no_identitas', 'like', $request->get('search').'%')
                    ->orWhere('customer.nama', 'like', $request->get('search').'%');
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailCustomer(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'no_identitas'  => 'required',
                'companyid'     => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data customer terlebih dahulu');
            }

            $sql = DB::table('customer')
                    ->selectRaw("ifnull(no_identitas, '') as no_identitas, ifnull(jenis_identitas, '') as jenis_identitas,
                                ifnull(nama, '') as nama, ifnull(tempat_lahir, '') as tempat_lahir,
                                ifnull(tanggal_lahir, '') as tanggal_lahir, ifnull(jenis_kelamin, '') as jenis_kelamin,
                                ifnull(alamat, '') as alamat, ifnull(kota, '') as kota,
                                ifnull(pekerjaan, '') as pekerjaan, ifnull(telepon, '') as telepon,
                                ifnull(email, '') as email")
                    ->where('no_identitas', strtoupper(trim($request->get('no_identitas'))))
                    ->where('companyid', strtoupper(trim($request->get('companyid'))))
                    ->first();

            if(empty($sql->no_identitas)) {
                return ApiResponse::responseWarning('Data customer tidak ditemukan');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekCustomerTidakTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'no_identitas'  => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi nomor identitas customer terlebih dahulu');
            }

            $sql = DB::table('customer')
                    ->selectRaw("ifnull(no_identitas, '') as no_identitas, ifnull(nama, '') as nama")
                    ->where('customer.no_identitas', $request->get('no_identitas'))
                    ->where('customer.companyid', $request->get('companyid'))
                    ->first();

            if(!empty($sql->no_identitas)) {
                return ApiResponse::responseWarning('Data customer sudah terdaftar');
            }

            return ApiResponse::responseSuccess('success', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekCustomerTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'no_identitas'  => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi nomor identitas customer terlebih dahulu');
            }

            $sql = DB::table('customer')
                    ->selectRaw("ifnull(no_identitas, '') as no_identitas, ifnull(nama, '') as nama")
                    ->where('customer.no_identitas', $request->get('no_identitas'))
                    ->where('customer.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->no_identitas)) {
                return ApiResponse::responseWarning('Data customer tidak terdaftar');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanCustomer(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'no_identitas'  => 'required',
                'jenis_identitas' => 'required',
                'nama'          => 'required',
                'jenis_kelamin' => 'required',
                'tempat_lahir'  => 'required',
                'tanggal_lahir' => 'required',
                'telepon'       => 'required',
                'email'         => 'required',
                'companyid'     => 'required',
                'user_id'       => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Nomor identitas, jenis identitas, nama, telepon, dan email tidak boleh kosong');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_customer_simpan (?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    trim(strtoupper($request->get('no_identitas'))), trim(strtoupper($request->get('jenis_identitas'))),
                    trim($request->get('nama')), trim($request->get('tempat_lahir')), $request->get('tanggal_lahir'),
                    trim($request->get('jenis_kelamin')), trim($request->get('alamat')), trim($request->get('kota')),
                    trim($request->get('pekerjaan')), trim($request->get('telepon')), trim($request->get('email')),
                    trim(strtoupper($request->get('companyid'))), trim(strtoupper($request->get('user_id')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function hapusCustomer(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'no_identitas'  => 'required',
                'companyid'     => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data customer terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_customer_hapus (?,?)', [
                    trim(strtoupper($request->get('no_identitas'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
