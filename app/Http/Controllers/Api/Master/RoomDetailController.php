<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class RoomDetailController extends Controller
{
    public function daftarRoomDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'user_id'   => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data ruangan terlebih dahulu');
            }

            $sql = DB::table('room_detailtmp')
                    ->selectRaw("ifnull(room_detailtmp.kode_key, '') as kode_key,
                                ifnull(room_detailtmp.kode_fasilitas, '') as kode_fasilitas,
                                ifnull(fasilitas.nama_fasilitas, '') as nama_fasilitas")
                    ->leftJoin('fasilitas', function($join) {
                        $join->on('fasilitas.kode_fasilitas', '=', 'room_detailtmp.kode_fasilitas')
                            ->on('fasilitas.companyid', '=', 'room_detailtmp.companyid');
                    })
                    ->where('room_detailtmp.kode_key', strtoupper($request->get('user_id')))
                    ->where('room_detailtmp.companyid', $request->get('companyid'))
                    ->orderBy('room_detailtmp.usertime', 'desc')
                    ->get();

            $data = [
                'kode_key'      => strtoupper(trim($request->get('user_id'))),
                'kode_room'     => strtoupper(trim($request->get('kode_room'))),
                'fasilitas'     => $sql
            ];

            return ApiResponse::responseSuccess('success', $data);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function checkListRoomDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'user_id'   => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data ruangan terlebih dahulu');
            }

            $sql = DB::table('fasilitas')
                    ->selectRaw("ifnull(fasilitas.kode_fasilitas, '') as kode_fasilitas,
                                ifnull(fasilitas.nama_fasilitas, '') as nama_fasilitas,
                                if(ifnull(room_detailtmp.kode_key, '')='', 0, 1) as checked")
                    ->leftJoin('room_detailtmp', function($join) use($request) {
                        $join->on('room_detailtmp.kode_fasilitas', '=', 'fasilitas.kode_fasilitas')
                            ->on('room_detailtmp.companyid', '=', 'fasilitas.companyid')
                            ->on('room_detailtmp.kode_key', '=', DB::raw("'".strtoupper(trim($request->get('user_id')))."'"));
                    })
                    ->where('fasilitas.companyid', $request->get('companyid'))
                    ->orderBy('fasilitas.usertime', 'desc');

            if(!empty($request->get('search')) && trim($request->get('search')) != '') {
                $sql->where('fasilitas.kode_fasilitas', $request->get('search'))
                    ->orWhere('fasilitas.nama_fasilitas', $request->get('search'));
            }

            $result = $sql->paginate((empty($request->get('per_page'))) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanRoomDetail(Request $request) {
        $validate = Validator::make($request->all(), [
            'fasilitas'     => 'required',
            'user_id'       => 'required',
            'companyid'     => 'required',
        ]);

        if($validate->fails()) {
            return ApiResponse::responseWarning('Isi data tipe ruangan secara lengkap');
        }

        $fasilitas = json_decode($request->get('fasilitas'), true);
        $fasilitas_simpan = [];

        foreach($fasilitas as $data) {
            $fasilitas_simpan[] = [
                'kode_key'          => strtoupper(trim($request->get('user_id'))),
                'kode_fasilitas'    => strtoupper(trim($data)),
                'companyid'         => strtoupper(trim($request->get('companyid'))),
                'usertime'          => date('Y-m-d').'='.date('H:i:s').'='.strtoupper(trim($request->get('companyid'))).'='.strtoupper(trim($request->get('user_id')))
            ];
        }

        DB::transaction(function () use ($request, $fasilitas_simpan) {
            DB::delete('call sp_roomtmp_hapus (?,?)', [
                trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('companyid')))
            ]);

            DB::table('room_detailtmp')->insert($fasilitas_simpan);
        });

        return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        try {

        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function hapusRoomDetail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_fasilitas' => 'required',
                'user_id'       => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data tipe ruangan secara lengkap');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_roomdetailtmp_hapus (?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('kode_fasilitas'))),
                    trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
