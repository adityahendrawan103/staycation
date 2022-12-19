<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class RoomController extends Controller
{
    public function daftarRoom(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('room')
                    ->selectRaw("ifnull(room.kode_room, '') as kode, ifnull(room_tipe.nama_tipe, '') as tipe,
                                ifnull(room.longtime, 0) as longtime, ifnull(room.shorttime, 0) as shorttime,
                                ifnull(room.lantai, 0) as lantai, ifnull(room.kapasitas, 0) as kapasitas,
                                ifnull(room.status, '') as status, ifnull(room.keterangan, '') as keterangan")
                    ->leftJoin('room_tipe', function($join) {
                        $join->on('room_tipe.kode_tipe', '=', 'room.kode_tipe')
                            ->on('room_tipe.companyid', '=', 'room.companyid');
                    })
                    ->where('room.companyid', $request->get('companyid'))
                    ->orderBy('room.kode_room', 'asc');

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('room.kode_room', 'like', $request->get('search').'%');
            }

            $query = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            $result = collect($query)->toArray();

            $current_page = $result['current_page'];
            $data = $result['data'];
            $first_page_url = $result['first_page_url'];
            $from = $result['from'];
            $last_page = $result['last_page'];
            $last_page_url = $result['last_page_url'];
            $links = $result['links'];
            $next_page_url = $result['next_page_url'];
            $path = $result['path'];
            $per_page = $result['per_page'];
            $prev_page_url = $result['prev_page_url'];
            $to = $result['to'];
            $total = $result['total'];

            if((double)$total > 0) {
                $kode_room = '';
                foreach ($data as $record) {
                    if(trim($kode_room) == '') {
                        $kode_room = "'".$record->kode."'";
                    } else {
                        $kode_room .= ",'".$record->kode."'";
                    }
                }

                $kode_room = '('.trim($kode_room).')';

                $sql = "select 	reservasi.companyid, reservasi.kode_room,
                                case
                                    when ifnull(reservasi.status_in, 0)=1 and ifnull(reservasi.status_out, 0)=0 then 'IN-HOUSE'
                                    when ifnull(reservasi.canceled, 0)=0 and ifnull(reservasi.status_in, 0)=0 and
                                        ifnull(reservasi.status_out, 0)=0 then 'BOOKED'
                                else 	'READY'
                                end as status
                        from
                        (
                            select 	reservasi.companyid, reservasi.kode_room,
                                    max(reservasi.kode_reservasi) as kode_reservasi
                            from 	reservasi
                            where 	reservasi.kode_room in ".$kode_room." and
                                        reservasi.companyid=? and
                                        cast(reservasi.check_in as date)=?
                            group by reservasi.companyid, reservasi.kode_room
                        ) 	check_in
                                left join reservasi on check_in.kode_reservasi=reservasi.kode_reservasi and
                                        check_in.companyid=reservasi.companyid
                        order by check_in.kode_room asc";

                $sql = DB::select($sql, [ $request->get('companyid'), '2022-11-03' ]);

                $data_room_status = new Collection();
                foreach($sql as $status) {
                    $data_room_status->push((object) [
                        'kode_room'     => strtoupper(trim($status->kode_room)),
                        'status'        => strtoupper(trim($status->status)),
                    ]);
                }

                $data_room = [];
                foreach($data as $data) {
                    $status_room = '';
                    foreach($data_room_status as $status) {
                        if(strtoupper(trim($status->kode_room)) == strtoupper(trim($data->kode))) {
                            $status_room = $status->status;
                        }
                    }

                    if(strtoupper(trim($data->status)) == 'MAINTENANCE') {
                        $status_room = 'MAINTENANCE';
                    }
                    if(strtoupper(trim($data->status)) == 'HOUSEKEEPING') {
                        $status_room = 'HOUSEKEEPING';
                    }
                    if($status_room == '') {
                        $status_room = 'READY';
                    }

                    $data_room[] = [
                        'kode'          => strtoupper(trim($data->kode)),
                        'tipe'          => trim($data->tipe),
                        'longtime'      => (double)$data->longtime,
                        'shorttime'     => (double)$data->shorttime,
                        'lantai'        => strtoupper(trim($data->lantai)),
                        'kapasitas'     => (double)$data->kapasitas,
                        'keterangan'    => trim($data->keterangan),
                        'status'        => $status_room
                    ];
                }
            }

            $result = [
                'current_page'  => $current_page,
                'data'          => $data_room,
                'first_page_url' => $first_page_url,
                'from'          => $from,
                'last_page'     => $last_page,
                'last_page_url' => $last_page_url,
                'links'         => $links,
                'next_page_url' => $next_page_url,
                'path'          => $path,
                'per_page'      => $per_page,
                'prev_page_url' => $prev_page_url,
                'to'            => $to,
                'total'         => $total
            ];

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailRoom(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_room' => 'required',
                'user_id'   => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data ruangan terlebih dahulu');
            }

            $sql = DB::table('room')
                    ->selectRaw("ifnull(room.kode_room, '') as kode_room, ifnull(room.kode_tipe, '') as kode_tipe,
                                ifnull(room_tipe.nama_tipe, '') as nama_tipe, ifnull(room.longtime, 0) as longtime,
                                ifnull(room.shorttime, 0) as shorttime, ifnull(room.lantai, 0) as lantai,
                                ifnull(room.kapasitas, 0) as kapasitas, ifnull(room.status, '') as status,
                                ifnull(room.keterangan, '') as keterangan")
                    ->leftJoin('room_tipe', function($join) {
                        $join->on('room_tipe.kode_tipe', '=', 'room.kode_tipe')
                            ->on('room_tipe.companyid', '=', 'room.companyid');
                    })
                    ->where('room.kode_room', $request->get('kode_room'))
                    ->where('room.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_room)) {
                return ApiResponse::responseWarning('Data ruangan tidak ditemukan');
            }

            $data = [
                'kode_room'     => strtoupper(trim($sql->kode_room)),
                'kode_tipe'     => strtoupper(trim($sql->kode_tipe)),
                'nama_tipe'     => strtoupper(trim($sql->nama_tipe)),
                'longtime'      => (double)$sql->longtime,
                'shorttime'     => (double)$sql->shorttime,
                'lantai'        => strtoupper(trim($sql->lantai)),
                'kapasitas'     => (double)$sql->kapasitas,
                'status'        => strtoupper(trim($sql->status)),
                'keterangan'    => strtoupper(trim($sql->keterangan)),
            ];


            DB::transaction(function () use ($request) {
                DB::insert('call sp_roomtmp_transfer (?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('kode_room'))),
                        trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('success', $data);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekKodeRoomTidakTerdaftar(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_room' => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data kode ruangan terlebih dahulu');
            }

            $sql = DB::table('room')
                    ->selectRaw("ifnull(room.kode_room, '') as kode_room")
                    ->where('room.kode_room', $request->get('kode_room'))
                    ->where('room.companyid', $request->get('companyid'))
                    ->first();

            if(!empty($sql->kode_room)) {
                return ApiResponse::responseWarning('Data kode ruangan sudah terdaftar');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanRoom(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_room'     => 'required',
                'kode_tipe'     => 'required',
                'longtime'      => 'required',
                'shorttime'     => 'required',
                'kapasitas'     => 'required',
                'lantai'        => 'required',
                'status'        => 'required',
                'user_id'       => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Kode room, tipe, harga long time, harga short time, lantai, status room tidak boleh kosong');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_room_simpan (?,?,?,?,?,?,?,?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('kode_room'))),
                    trim(strtoupper($request->get('kode_tipe'))), trim($request->get('keterangan')),
                    (double)$request->get('kapasitas'), (double)$request->get('longtime'), (double)$request->get('shorttime'),
                    trim(strtoupper($request->get('status'))), trim($request->get('lantai')),
                    trim(strtoupper($request->get('companyid')))
                ]);
            });
            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function hapusRoomTmp(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'user_id'   => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('UserId dan company kosong');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_roomtmp_hapus (?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('companyid')))
                ]);
            });
            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function hapusRoom(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_room'     => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih kode room terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_room_hapus (?,?)', [
                    trim(strtoupper($request->get('kode_room'))), trim(strtoupper($request->get('companyid')))
                ]);
            });
            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
