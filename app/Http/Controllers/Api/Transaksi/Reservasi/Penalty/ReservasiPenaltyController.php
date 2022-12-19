<?php

namespace App\Http\Controllers\Api\Transaksi\Reservasi\Penalty;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class ReservasiPenaltyController extends Controller
{
    public function daftarReservasiPenalty(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Page, per page, dan company harus terisi');
            }

            $sql = DB::table('reservasi')
                    ->selectRaw("ifnull(reservasi.kode_reservasi, '') as kode_reservasi,
                                ifnull(date_format(reservasi.tanggal_reservasi, '%d/%m/%Y'), '') as tanggal_reservasi,
                                ifnull(reservasi.no_referensi, '') as no_referensi,
                                ifnull(reservasi.kode_room, '') as kode_room,
                                ifnull(room.kode_tipe, '') as kode_tipe,
                                ifnull(room_tipe.nama_tipe, '') as nama_tipe,
                                ifnull(platform.nama_platform, '') as platform,
                                ifnull(date_format(reservasi.check_in, '%d/%m/%Y'), '') as tanggal_check_in,
                                ifnull(date_format(reservasi.check_out, '%d/%m/%Y'), '') as tanggal_check_out,
                                ifnull(date_format(reservasi.check_in, '%H:%i:%s'), '') as jam_check_in,
                                ifnull(date_format(reservasi.check_out, '%H:%i:%s'), '') as jam_check_out,
                                ifnull(reservasi.no_identitas, '') as no_identitas,
                                ifnull(customer.jenis_identitas, '') as jenis_identitas,
                                ifnull(customer.nama, '') as nama_customer,
                                ifnull(customer.kota, '') as kota_customer,
                                ifnull(customer.telepon, '') as telepon_customer,
                                ifnull(reservasi.nama_cp, '') as nama_contact_person,
                                ifnull(reservasi.keterangan, '') as keterangan,
                                ifnull(reservasi.penalty, 0) as penalty,
                                ifnull(reservasi.catatan, '') as catatan,
                                case
                                    when ifnull(reservasi.status_end, 0) = 0 and ifnull(reservasi.canceled, 0) = 0 and
                                            ifnull(reservasi.status_out, 0) = 0 and ifnull(reservasi.status_in, 0) = 1 then 'IN-HOUSE'
                                else 'UNDEFINED'
                                end as status_reservasi,
                                case
                                    when    ifnull(reservasi.status_end, 0) = 1 then ''
                                    when 	ifnull(reservasi.status_in, 0) = 0 and ifnull(reservasi.status_end, 0) = 0 and
                                            now() >= ifnull(reservasi.check_in, '') then 'CHECKIN'
                                    when 	ifnull(reservasi.status_in, 0) = 1 and ifnull(reservasi.status_out, 0) = 0 and
                                            now() >= ifnull(reservasi.check_out, '') then 'CHECKOUT'
                                else 	''
                                end as indicator")
                    ->leftJoin('platform', function($join) {
                        $join->on('platform.kode_platform', '=', 'reservasi.kode_platform')
                            ->on('platform.companyid', '=', 'reservasi.companyid');
                    })
                    ->leftJoin('room', function($join) {
                        $join->on('room.kode_room', '=', 'reservasi.kode_room')
                            ->on('room.companyid', '=', 'reservasi.companyid');
                    })
                    ->leftJoin('room_tipe', function($join) {
                        $join->on('room_tipe.kode_tipe', '=', 'room.kode_tipe')
                            ->on('room_tipe.companyid', '=', 'reservasi.companyid');
                    })
                    ->leftJoin('customer', function($join) {
                        $join->on('customer.no_identitas', '=', 'reservasi.no_identitas')
                            ->on('customer.companyid', '=', 'reservasi.companyid');
                    })
                    ->where('reservasi.companyid', $request->get('companyid'))
                    ->whereRaw("ifnull(reservasi.status_end, 0) = 0 and ifnull(reservasi.canceled, 0) = 0 and
                                    ifnull(reservasi.status_out, 0) = 0 and ifnull(reservasi.status_in, 0) = 1")
                    ->orderByRaw("reservasi.tanggal_reservasi desc, reservasi.kode_reservasi desc");

            if(!empty($request->get('search')) && $request->get('search') != '') {
                if(strtoupper(trim($request->get('filter'))) == 'TIPEROOM') {
                    $sql->where('room_tipe.kode_tipe', $request->get('search'))
                        ->orWhere('room_tipe.nama_tipe', $request->get('search'));
                } elseif(strtoupper(trim($request->get('filter'))) == 'ROOM') {
                    $sql->where('reservasi.kode_room', $request->get('search'));
                } elseif(strtoupper(trim($request->get('filter'))) == 'KODERESERVASI') {
                    $sql->where('reservasi.kode_reservasi', $request->get('search'));
                }
            }

            $result = $sql->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $result);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailReservasiPenalty(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'companyid'         => 'required',
                'user_id'           => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data transaksi reservasi terlebih dahulu');
            }

            $sql = DB::table('reservasi')
                    ->selectRaw("ifnull(reservasi.kode_reservasi, '') as kode_reservasi,
                                ifnull(date_format(reservasi.tanggal_reservasi, '%Y-%m-%d'), '') as tanggal_reservasi,
                                ifnull(date_format(reservasi.check_in, '%Y-%m-%d'), '') as tanggal_check_in,
                                ifnull(date_format(reservasi.check_out, '%Y-%m-%d'), '') as tanggal_check_out,
                                ifnull(time(reservasi.check_in), '') as jam_check_in,
                                ifnull(time(reservasi.check_out), '') as jam_check_out,
                                ifnull(reservasi.no_identitas, '') as no_identitas,
                                ifnull(customer.nama, '') as nama_customer,
                                ifnull(customer.kota, '') as kota_customer,
                                ifnull(room.kode_tipe, '') as kode_tipe,
                                ifnull(room_tipe.nama_tipe, '') as nama_tipe,
                                ifnull(room_tipe.grade, '') as grade,
                                ifnull(reservasi.kode_room, '') as kode_room,
                                ifnull(reservasi.penalty, 0) as penalty,
                                ifnull(reservasi.pembayaran_penalty, 0) as jumlah_pembayaran,
                                ifnull(reservasi.sisa_pembayaran_penalty, 0) as sisa_pembayaran")
                    ->leftJoin('company', function($join) {
                        $join->on('company.companyid', '=', 'reservasi.companyid');
                    })
                    ->leftJoin('customer', function($join) {
                        $join->on('customer.no_identitas', '=', 'reservasi.no_identitas')
                            ->on('customer.companyid', '=', 'reservasi.companyid');
                    })
                    ->leftJoin('room', function($join) {
                        $join->on('room.kode_room', '=', 'reservasi.kode_room')
                            ->on('room.companyid', '=', 'reservasi.companyid');
                    })
                    ->leftJoin('room_tipe', function($join) {
                        $join->on('room_tipe.kode_tipe', '=', 'room.kode_tipe')
                            ->on('room_tipe.companyid', '=', 'reservasi.companyid');
                    })
                    ->where('reservasi.kode_reservasi', $request->get('kode_reservasi'))
                    ->whereRaw("ifnull(reservasi.status_end, 0) = 0 and ifnull(reservasi.canceled, 0) = 0 and
                                ifnull(reservasi.status_out, 0) = 0 and ifnull(reservasi.status_in, 0) = 1")
                    ->where('reservasi.companyid', $request->get('companyid'))
                    ->get();

            $jumlah_data = 0;
            $data_reservasi = new Collection();

            foreach($sql as $data) {
                $jumlah_data = (double)$jumlah_data + 1;

                $data_reservasi->push((object) [
                    'kode_reservasi'            => strtoupper(trim($data->kode_reservasi)),
                    'tanggal_reservasi'         => trim($data->tanggal_reservasi),
                    'check_in_tanggal'          => trim($data->tanggal_check_in),
                    'check_in_jam'              => trim($data->jam_check_in),
                    'check_out_tanggal'         => trim($data->tanggal_check_out),
                    'check_out_jam'             => trim($data->jam_check_out),
                    'no_identitas_customer'     => strtoupper(trim($data->no_identitas)),
                    'nama_customer'             => trim($data->nama_customer),
                    'kota_customer'             => trim($data->kota_customer),
                    'kode_tipe_room'            => strtoupper(trim($data->kode_tipe)),
                    'nama_tipe_room'            => trim($data->nama_tipe),
                    'grade_tipe_room'           => strtoupper(trim($data->grade)),
                    'kode_room'                 => strtoupper(trim($data->kode_room)),
                    'penalty'                   => (double)$data->penalty,
                    'jumlah_pembayaran'         => (double)$data->jumlah_pembayaran,
                    'sisa_pembayaran'           => (double)$data->sisa_pembayaran,
                ]);
            }

            if((double)$jumlah_data <= 0) {
                return ApiResponse::responseWarning('Data transaksi reservasi tidak ditemukan');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_reservasipenaltytmp_transfer (?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('kode_reservasi'))),
                        trim(strtoupper($request->get('companyid')))
                ]);
            });

            $result = $data_reservasi->first();

            $data = [
                'kode_reservasi'    => strtoupper(trim($result->kode_reservasi)),
                'tanggal'           => trim($result->tanggal_reservasi),
                'check_in'          => [
                    'tanggal'       => trim($result->check_in_tanggal),
                    'jam'           => trim($result->check_in_jam)
                ],
                'check_out'         => [
                    'tanggal'       => trim($result->check_out_tanggal),
                    'jam'           => trim($result->check_out_jam)
                ],
                'customer'          => [
                    'no_identitas'  => strtoupper(trim($result->no_identitas_customer)),
                    'nama'          => trim($result->nama_customer),
                    'kota'          => trim($result->kota_customer),
                ],
                'room'              => [
                    'kode_room'     => strtoupper(trim($result->kode_room)),
                    'kode_tipe'     => strtoupper(trim($result->kode_tipe_room)),
                    'nama'          => trim($result->nama_tipe_room),
                    'grade'         => strtoupper(trim($result->grade_tipe_room))
                ],
                'penalty'           => (double)$result->penalty,
                'total_pembayaran'  => (double)$result->jumlah_pembayaran,
                'sisa_pembayaran'   => (double)$result->sisa_pembayaran,
            ];

            return ApiResponse::responseSuccess('success', $data);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanReservasiPenalty(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'companyid'         => 'required',
                'user_id'           => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data transaksi reservasi terlebih dahulu');
            }

            $sql = DB::table('penaltytmp')
                    ->where('kode_key', $request->get('user_id'))
                    ->where('kode_reservasi', $request->get('kode_reservasi'))
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_key)) {
                return ApiResponse::responseWarning('Isi data penalty terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_reservasipenalty_simpan (?,?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('kode_reservasi'))),
                        (empty($request->get('pembayaran_penalty'))) ? 0 : (double)$request->get('pembayaran_penalty'),
                        trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Penalty Berhasil Disimpan');
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function hapusReservasiPenalty(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'companyid'         => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data transaksi reservasi terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_reservasipenalty_hapus (?,?)', [
                    trim(strtoupper($request->get('kode_reservasi'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Penalty Berhasil Dihapus');
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
