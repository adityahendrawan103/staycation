<?php

namespace App\Http\Controllers\Api\Option;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class OptionController extends Controller
{
    public function optionCustomer(Request $request) {
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
                    ->selectRaw("ifnull(no_identitas, '') as no_identitas, ifnull(nama, '') as nama,
                                ifnull(jenis_identitas, '') as jenis_identitas,
                                ifnull(date_format(tanggal_lahir, '%d/%m/%Y'), '') as tanggal_lahir,
                                ifnull(kota, '') as kota, ifnull(telepon, '') as telepon")
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

    public function optionJabatan(Request $request) {
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

    public function optionLayanan(Request $request) {
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
                    ->where('companyid', $request->get('companyid'))
                    ->orderBy('kode_layanan', 'asc');

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('kode_layanan', 'like', $request->get('search').'%')
                    ->orWhere('nama_layanan', 'like', $request->get('search').'%');
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function optionPlatform(Request $request) {
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

    public function optionItem(Request $request) {
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

    public function optionRoom(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_tipe' => 'required',
                'status'    => 'required',
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('room')
                    ->selectRaw("ifnull(room.kode_room, '') as kode, ifnull(room_tipe.nama_tipe, '') as tipe,
                                ifnull(room.harga, 0) as harga, ifnull(room.lantai, 0) as lantai,
                                ifnull(room.status, '') as status")
                    ->leftJoin('room_tipe', function($join) {
                        $join->on('room_tipe.kode_tipe', '=', 'room.kode_tipe')
                            ->on('room_tipe.companyid', '=', 'room.companyid');
                    })
                    ->where('room.companyid', $request->get('companyid'))
                    ->orderBy('room.kode_room', 'asc');

            if(strtoupper(trim($request->get('kode_tipe'))) != 'ALL') {
                $sql->where('room.kode_tipe', strtoupper(trim($request->get('kode_tipe'))));
            }

            if(strtoupper(trim($request->get('status'))) != 'ALL') {
                $sql->where('room.status', strtoupper(trim($request->get('status'))));
            }

            if(!empty($request->get('search')) && $request->get('search') != '') {
                $sql->where('room.kode_room', 'like', $request->get('search').'%');
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function optionRoomReservasi(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_tipe' => 'required',
                'check_in'  => 'required',
                'check_out' => 'required',
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Tipe ruangan, tanggal check in, dan tanggal check out tidak boleh kosong');
            }

            $room = DB::table('room')
                    ->selectRaw("room.companyid, room.kode_room, room_tipe.kode_tipe, room_tipe.nama_tipe,
                                room_tipe.grade, room.lantai, room.kapasitas, room.longtime, room.shorttime,
                                room.status")
                    ->leftJoin('room_tipe', function($join) {
                        $join->on('room_tipe.kode_tipe', '=', 'room.kode_tipe')
                            ->on('room_tipe.companyid', '=', 'room.companyid');
                    })
                    ->where('room.status', 'READY')
                    ->where('room_tipe.kode_tipe', $request->get('kode_tipe'))
                    ->where('room.companyid', $request->get('companyid'));

            $sql = DB::table($room, 'room')
                    ->selectRaw("room.companyid, room.kode_room")
                    ->leftJoin('reservasi', function($join) use ($request) {
                        $join->on('reservasi.kode_room', '=', 'room.kode_room')
                            ->on('reservasi.companyid', '=', 'room.companyid')
                            ->on(DB::raw("ifnull(reservasi.status_end, 0)"), '=', DB::raw("0"))
                            ->on('reservasi.check_out', '>=', DB::raw("'".$request->get('check_in')."'"))
                            ->on('reservasi.check_in', '<=', DB::raw("'".$request->get('check_out')."'"));
                    })
                    ->whereRaw("reservasi.kode_reservasi is null and reservasi.kode_reservasi is null")
                    ->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            $result = collect($sql)->toArray();

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

            $data_kode_room = array();
            $data_room = [];
            if((double)$total > 0) {
                foreach ($data as $record) {
                    array_push($data_kode_room, $record->kode_room);
                }

                $sql = DB::table('room')
                        ->selectRaw("ifnull(room.kode_room, '') as kode_room, ifnull(room_tipe.nama_tipe, '') as tipe,
                                    ifnull(room.kapasitas, 0) as kapasitas, ifnull(room.longtime, 0) as longtime,
                                    ifnull(room.shorttime, 0) as shorttime, ifnull(room.lantai, 0) as lantai,
                                    ifnull(room.status, '') as status, ifnull(room_detail.kode_fasilitas, '') as kode_fasilitas,
                                    ifnull(fasilitas.nama_fasilitas, '') as nama_fasilitas")
                        ->leftJoin('room_tipe', function($join) {
                            $join->on('room_tipe.kode_tipe', '=', 'room.kode_tipe')
                                ->on('room_tipe.companyid', '=', 'room.companyid');
                        })
                        ->leftJoin('room_detail', function($join) {
                            $join->on('room_detail.kode_room', '=', 'room.kode_room')
                                ->on('room_detail.companyid', '=', 'room.companyid');
                        })
                        ->leftJoin('fasilitas', function($join) {
                            $join->on('fasilitas.kode_fasilitas', '=', 'room_detail.kode_fasilitas')
                                ->on('fasilitas.companyid', '=', 'room.companyid');
                        })
                        ->where('room.companyid', $request->get('companyid'))
                        ->whereIn('room.kode_room', $data_kode_room)
                        ->orderBy('room.kode_room', 'asc');

                if(!empty($request->get('search')) && $request->get('search') != '') {
                    $sql->where('fasilitas.kode_fasilitas', 'like', $request->get('search').'%')
                        ->orWhere('fasilitas.nama_fasilitas', 'like', $request->get('search').'%')
                        ->orWhere('room.kode_room', 'like', $request->get('search').'%');
                }

                $result = $sql->get();

                $data_detail_room = new Collection();
                $data_fasilitas = new Collection();

                foreach($result as $data) {
                    $data_fasilitas->push((object) [
                        'kode_room'         => strtoupper(trim($data->kode_room)),
                        'kode_fasilitas'    => strtoupper(trim($data->kode_fasilitas)),
                        'nama_fasilitas'    => trim($data->nama_fasilitas)
                    ]);

                    $data_detail_room->push((object) [
                        'kode_room'     => strtoupper(trim($data->kode_room)),
                        'tipe'          => trim($data->tipe),
                        'lantai'        => trim($data->lantai),
                        'kapasitas'     => (double)$data->kapasitas,
                        'longtime'      => (double)$data->longtime,
                        'shorttime'     => (double)$data->shorttime,
                        'status'        => strtoupper(trim($data->status))
                    ]);
                }

                $kode_room = '';
                foreach($data_detail_room as $data) {
                    if(strtoupper(trim($data->kode_room)) != strtoupper(trim($kode_room))) {
                        $data_room[] = [
                            'kode_room'     => strtoupper(trim($data->kode_room)),
                            'tipe'          => trim($data->tipe),
                            'lantai'        => trim($data->lantai),
                            'kapasitas'     => (double)$data->kapasitas,
                            'longtime'      => (double)$data->longtime,
                            'shorttime'     => (double)$data->shorttime,
                            'status'        => strtoupper(trim($data->status)),
                            'fasilitas'     => $data_fasilitas
                                                ->where('kode_room', strtoupper(trim($data->kode_room)))
                                                ->values()
                                                ->all()
                        ];
                        $kode_room = strtoupper(trim($data->kode_room));
                    }
                }
            }
            $data_option = [
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
            return ApiResponse::responseSuccess('success', $data_option);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function optionRoomTipe(Request $request) {
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
                    ->selectRaw("ifnull(kode_tipe, '') as kode_tipe,
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
}
