<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class KaryawanController extends Controller
{
    public function daftarKaryawan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('karyawan')
                    ->selectRaw("ifnull(karyawan.nik, '')	as nik, ifnull(karyawan.nama, '') as nama,
                                ifnull(jabatan.nama_jabatan, '') as jabatan, ifnull(karyawan.jenis_kelamin, '') as jenis_kelamin,
                                ifnull(date_format(karyawan.tanggal_lahir, '%Y-%m-%d'), '') as tanggal_lahir,
                                ifnull(karyawan.agama, '') as agama, ifnull(karyawan.alamat, '') as alamat,
                                ifnull(karyawan.kabupaten, '') as kabupaten, ifnull(karyawan.provinsi, '') as provinsi,
                                ifnull(karyawan.provinsi, '') as provinsi,
                                ifnull(karyawan.telepon, '') as telepon, ifnull(karyawan.status, '') as status,
                                ifnull(karyawan.foto, '') as foto")
                    ->leftJoin('jabatan', function($join) {
                        $join->on('jabatan.kode_jabatan', '=', 'karyawan.kode_jabatan')
                            ->on('jabatan.companyid', '=', 'karyawan.companyid');
                    })
                    ->where('karyawan.companyid', $request->get('companyid'))
                    ->orderBy('karyawan.nik', 'asc');

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('karyawan.nik', 'like', $request->get('search').'%')
                    ->orWhere('karyawan.nama', 'like', $request->get('search').'%');
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailKaryawan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'nik'       => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data karyawan terlebih dahulu');
            }

            $sql = DB::table('karyawan')
                    ->selectRaw("ifnull(karyawan.nik, '') as nik, ifnull(karyawan.no_ktp, '') as no_ktp, ifnull(karyawan.nama, '') as nama,
                                ifnull(karyawan.kode_jabatan, '') as kode_jabatan, ifnull(jabatan.nama_jabatan, '') as nama_jabatan,
                                ifnull(karyawan.tempat_lahir, '') as tempat_lahir, ifnull(karyawan.tanggal_lahir, '') as tanggal_lahir,
                                ifnull(karyawan.jenis_kelamin, '') as jenis_kelamin, ifnull(karyawan.alamat, '') as alamat,
                                ifnull(karyawan.rt, '') as rt, ifnull(karyawan.rw, '') as rw, ifnull(karyawan.kelurahan, '') as kelurahan,
                                ifnull(karyawan.kecamatan, '') as kecamatan, ifnull(karyawan.kabupaten, '') as kabupaten,
                                ifnull(karyawan.provinsi, '') as provinsi, ifnull(karyawan.agama, '') as agama,
                                ifnull(karyawan.telepon, '') as telepon, ifnull(karyawan.status, '') as status,
                                ifnull(karyawan.foto, '') as foto")
                    ->leftJoin('jabatan', function($join) {
                        $join->on('jabatan.kode_jabatan', '=', 'karyawan.kode_jabatan')
                            ->on('jabatan.companyid', '=', 'karyawan.companyid');
                    })
                    ->where('karyawan.nik', $request->get('nik'))
                    ->where('karyawan.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->nik)) {
                return ApiResponse::responseWarning('Data karyawan tidak ditemukan');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekNikTidakTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'nik'       => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi NIK karyawan terlebih dahulu');
            }

            $sql = DB::table('karyawan')
                    ->selectRaw("ifnull(nik, '') as nik, ifnull(nama, '') as nama_karyawan")
                    ->where('nik', $request->get('nik'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(!empty($sql->nik)) {
                return ApiResponse::responseWarning('NIK Karyawan sudah terdaftar');
            }

            return ApiResponse::responseSuccess('success', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekNoKTPTidakTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'nik'       => 'required',
                'no_ktp'    => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Nomor NIK dan nomor KTP tidak boleh kosong');
            }

            $sql = DB::table('karyawan')
                    ->selectRaw("ifnull(nik, '') as nik, ifnull(no_ktp, '') as no_ktp,
                                ifnull(nama, '') as nama_karyawan")
                    ->where('no_ktp', $request->get('no_ktp'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(!empty($sql->no_ktp)) {
                if(strtoupper(trim($sql->nik)) != strtoupper(trim($request->get('nik')))) {
                    return ApiResponse::responseWarning('Nomor KTP Karyawan sudah terdaftar');
                }
            }
            return ApiResponse::responseSuccess('success', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekNikTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'nik'       => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih atau isi NIK karyawan terlebih dahulu');
            }

            $sql = DB::table('karyawan')
                    ->selectRaw("ifnull(nik, '') as nik, ifnull(nama, '') as nama_karyawan")
                    ->where('nik', $request->get('nik'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->nik)) {
                return ApiResponse::responseWarning('NIK Karyawan tidak terdaftar');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanKaryawan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'nik'       => 'required',
                'no_ktp'    => 'required',
                'nama'      => 'required',
                'jabatan'   => 'required',
                'tempat_lahir'  => 'required',
                'tanggal_lahir' => 'required',
                'status'    => 'required',
                'companyid' => 'required',
                'user_id'   => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('NIK, no KTP, nama, jabatan, tempat lahir, tanggal lahir, dan status karyawan tidak boleh kosong');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_karyawan_simpan (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    trim(strtoupper($request->get('nik'))), trim(strtoupper($request->get('no_ktp'))),
                    trim($request->get('nama')), trim($request->get('jenis_kelamin')), trim(strtoupper($request->get('jabatan'))),
                    trim($request->get('tempat_lahir')), $request->get('tanggal_lahir'), trim($request->get('alamat')), trim($request->get('rt')),
                    trim($request->get('rw')), trim($request->get('kelurahan')), trim($request->get('kecamatan')),
                    trim($request->get('kabupaten')), trim($request->get('provinsi')), trim($request->get('agama')),
                    trim($request->get('telepon')), trim($request->get('foto')), trim(strtoupper($request->get('status'))),
                    trim(strtoupper($request->get('companyid'))), trim(strtoupper($request->get('user_id')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function hapusKaryawan(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'nik'       => 'required',
                'companyid' => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data karyawan terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::delete('call sp_karyawan_hapus (?,?)', [
                    trim(strtoupper($request->get('nik'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
