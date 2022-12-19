<?php

namespace App\Http\Controllers\Api\Transaksi\Reservasi\InHouse;

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


class ReservasiInHouseController extends Controller
{
    public function daftarReservasiInHouse(Request $request) {
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
                if(strtoupper(trim($request->get('filter'))) == 'PLATFORM') {
                    $sql->where('platform.kode_platform', $request->get('search'))
                        ->orWhere('platform.nama_platform', $request->get('search'));
                } elseif(strtoupper(trim($request->get('filter'))) == 'TIPEROOM') {
                    $sql->where('room_tipe.kode_tipe', $request->get('search'))
                        ->orWhere('room_tipe.nama_tipe', $request->get('search'));
                } elseif(strtoupper(trim($request->get('filter'))) == 'ROOM') {
                    $sql->where('reservasi.kode_room', $request->get('search'));
                } elseif(strtoupper(trim($request->get('filter'))) == 'NAMACP') {
                    $sql->where('reservasi.nama_cp', $request->get('search'));
                } elseif(strtoupper(trim($request->get('filter'))) == 'TELPCP') {
                    $sql->where('reservasi.telepon_cp', $request->get('search'));
                } elseif(strtoupper(trim($request->get('filter'))) == 'NOIDENTITAS') {
                    $sql->where('reservasi.no_identitas', $request->get('search'));
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

    public function detailReservasiInHouse(Request $request) {
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
                                ifnull(reservasi.kode_platform, '') as kode_platform,
                                ifnull(platform.nama_platform, '') as nama_platform,
                                ifnull(reservasi.no_referensi, '') as no_referensi,
                                ifnull(reservasi.status_longtime, 0) as status_longtime,
                                ifnull(date_format(reservasi.check_in, '%Y-%m-%d'), '') as tanggal_check_in,
                                ifnull(date_format(reservasi.check_out, '%Y-%m-%d'), '') as tanggal_check_out,
                                ifnull(time(reservasi.check_in), '') as jam_check_in,
                                ifnull(time(reservasi.check_out), '') as jam_check_out,
                                ifnull(reservasi.nama_cp, '') as nama_cp,
                                ifnull(reservasi.telepon_cp, '') as telepon_cp,
                                ifnull(reservasi.keterangan, '') as keterangan,
                                ifnull(reservasi.no_identitas, '') as no_identitas,
                                ifnull(customer.nama, '') as nama_customer,
                                ifnull(date_format(customer.tanggal_lahir, '%Y-%m-%d'), '') as tanggal_lahir_customer,
                                ifnull(customer.kota, '') as kota_customer,
                                ifnull(room.kode_tipe, '') as kode_tipe,
                                ifnull(room_tipe.nama_tipe, '') as nama_tipe,
                                ifnull(room_tipe.grade, '') as grade,
                                ifnull(reservasi.kode_room, '') as kode_room,
                                ifnull(room.longtime, 0) as longtime,
                                ifnull(room.shorttime, 0) as shorttime,
                                ifnull(reservasi.lama_inap, 0) as lama_inap,
                                ifnull(reservasi.harga_satuan_room, 0) as harga_room,
                                ifnull(reservasi.harga_room, 0) as sub_total_room,
                                ifnull(reservasi.total_layanan, 0) as total_harga_layanan,
                                ifnull(reservasi.total_room, 0) as total_harga_room,
                                ifnull(reservasi.disc, 0) as disc_room_prosentase,
                                ifnull(reservasi.disc_rp, 0) as disc_room_nominal,
                                ifnull(reservasi.ppn, 0) as ppn_room_prosentase,
                                ifnull(reservasi.ppn_rp, 0) as ppn_room_nominal,
                                ifnull(reservasi_layanan.total, 0) as sub_total_layanan,
                                ifnull(reservasi_layanan.disc, 0) as disc_layanan_prosentase,
                                ifnull(reservasi_layanan.disc_rp, 0) as disc_layanan_nominal,
                                ifnull(reservasi_layanan.ppn, 0) as ppn_layanan_prosentase,
                                ifnull(reservasi_layanan.ppn_rp, 0) as ppn_layanan_nominal,
                                ifnull(reservasi.biaya_lain, 0) as biaya_lain,
                                ifnull(reservasi.grand_total, 0) as grand_total,
                                ifnull(reservasi.total_pembayaran, 0) as total_pembayaran,
                                ifnull(reservasi.sisa_pembayaran, 0) as sisa_pembayaran,
                                ifnull(reservasi.deposit, 0) as deposit,
                                ifnull(reservasi.catatan, '') as catatan,
                                ifnull(reservasi.alasan, '') as alasan,
                                ifnull(reservasi.status_in, 0) as status_in,
                                ifnull(reservasi.status_out, 0) as status_out,
                                ifnull(reservasi.canceled, 0) as status_canceled,
                                ifnull(company.shorttime_default, 0) as shorttime_default,
                                ifnull(company.check_in, '') as check_in_default,
                                ifnull(company.check_out, '') as check_out_default")
                    ->leftJoin('company', function($join) {
                        $join->on('company.companyid', '=', 'reservasi.companyid');
                    })
                    ->leftJoin('platform', function($join) {
                        $join->on('platform.kode_platform', '=', 'reservasi.kode_platform')
                            ->on('platform.companyid', '=', 'reservasi.companyid');
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
                    ->leftJoin('reservasi_layanan', function($join) {
                        $join->on('reservasi_layanan.kode_reservasi', '=', 'reservasi.kode_reservasi')
                            ->on('reservasi_layanan.companyid', '=', 'reservasi.companyid');
                    })
                    ->where('reservasi.kode_reservasi', $request->get('kode_reservasi'))
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
                    'status_longtime'           => (int)$data->status_longtime,
                    'status_in'                 => (int)$data->status_in,
                    'status_out'                => (int)$data->status_out,
                    'status_canceled'           => (int)$data->status_canceled,
                    'no_identitas_customer'     => strtoupper(trim($data->no_identitas)),
                    'nama_customer'             => trim($data->nama_customer),
                    'tanggal_lahir_customer'    => trim($data->tanggal_lahir_customer),
                    'kota_customer'             => trim($data->kota_customer),
                    'nama_cp_customer'          => trim($data->nama_cp),
                    'telepon_cp_customer'       => trim($data->telepon_cp),
                    'kode_platform'             => strtoupper(trim($data->kode_platform)),
                    'nama_platform'             => trim($data->nama_platform),
                    'no_referensi'              => strtoupper(trim($data->no_referensi)),
                    'kode_tipe_room'            => strtoupper(trim($data->kode_tipe)),
                    'nama_tipe_room'            => trim($data->nama_tipe),
                    'grade_tipe_room'           => strtoupper(trim($data->grade)),
                    'kode_room'                 => strtoupper(trim($data->kode_room)),
                    'longtime'                  => (double)$data->longtime,
                    'shorttime'                 => (double)$data->shorttime,
                    'lama_inap'                 => (double)$data->lama_inap,
                    'keterangan'                => trim($data->keterangan),
                    'catatan'                   => trim($data->catatan),
                    'alasan'                    => trim($data->alasan),
                    'sub_total_room'            => (double)$data->sub_total_room,
                    'harga_room'                => (double)$data->harga_room,
                    'disc_room_prosentase'      => (double)$data->disc_room_prosentase,
                    'disc_room_nominal'         => (double)$data->disc_room_nominal,
                    'ppn_room_prosentase'       => (double)$data->ppn_room_prosentase,
                    'ppn_room_nominal'          => (double)$data->ppn_room_nominal,
                    'total_harga_room'          => (double)$data->total_harga_room,
                    'sub_total_layanan'         => (double)$data->sub_total_layanan,
                    'disc_layanan_prosentase'   => (double)$data->disc_layanan_prosentase,
                    'disc_layanan_nominal'      => (double)$data->disc_layanan_nominal,
                    'ppn_layanan_prosentase'    => (double)$data->ppn_layanan_prosentase,
                    'ppn_layanan_nominal'       => (double)$data->ppn_layanan_nominal,
                    'total_harga_layanan'       => (double)$data->total_harga_layanan,
                    'biaya_lain'                => (double)$data->biaya_lain,
                    'grand_total'               => (double)$data->grand_total,
                    'total_pembayaran'          => (double)$data->total_pembayaran,
                    'sisa_pembayaran'           => (double)$data->sisa_pembayaran,
                    'shorttime_default'         => (double)$data->shorttime_default,
                    'check_in_default'          => $data->check_in_default,
                    'check_out_default'         => $data->check_out_default
                ]);
            }

            if((double)$jumlah_data <= 0) {
                return ApiResponse::responseWarning('Data transaksi reservasi tidak ditemukan');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_reservasitmp_transfer (?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('kode_reservasi'))),
                        trim(strtoupper($request->get('companyid')))
                ]);
            });

            $result = $data_reservasi->first();

            $data = [
                'kode_reservasi'        => strtoupper(trim($result->kode_reservasi)),
                'tanggal'               => trim($result->tanggal_reservasi),
                'default'               => [
                    'shorttime'         => (double)$result->shorttime_default,
                    'check_in'          => $result->check_in_default,
                    'check_out'         => $result->check_out_default
                ],
                'check_in'              => [
                    'tanggal'           => trim($result->check_in_tanggal),
                    'jam'               => trim($result->check_in_jam)
                ],
                'check_out'             => [
                    'tanggal'           => trim($result->check_out_tanggal),
                    'jam'               => trim($result->check_out_jam)
                ],
                'status'                => [
                    'longtime'          => (int)$result->status_longtime,
                    'in'                => (int)$result->status_in,
                    'out'               => (int)$result->status_out,
                    'canceled'          => (int)$result->status_canceled
                ],
                'customer'              => [
                    'no_identitas'      => strtoupper(trim($result->no_identitas_customer)),
                    'nama'              => trim($result->nama_customer),
                    'tanggal_lahir'     => trim($result->tanggal_lahir_customer),
                    'kota'              => trim($result->kota_customer),
                    'contact_person'    => [
                        'nama'          => trim($result->nama_cp_customer),
                        'telepon'       => trim($result->telepon_cp_customer)
                    ],
                ],
                'platform'              => [
                    'kode'              => strtoupper(trim($result->kode_platform)),
                    'nama'              => trim($result->nama_platform),
                    'no_referensi'      => strtoupper(trim($result->no_referensi))
                ],
                'room'                  => [
                    'tipe'              => [
                        'kode_tipe'     => strtoupper(trim($result->kode_tipe_room)),
                        'nama'          => trim($result->nama_tipe_room),
                        'grade'         => strtoupper(trim($result->grade_tipe_room))
                    ],
                    'room'              => [
                        'kode_room'     => strtoupper(trim($result->kode_room)),
                        'longtime'      => (double)$result->longtime,
                        'shorttime'     => (double)$result->shorttime
                    ]
                ],
                'keterangan'            => trim($result->keterangan),
                'catatan'               => trim($result->catatan),
                'alasan'                => trim($result->alasan),
                'reservasi'             => [
                    'room'              => [
                        'harga'             => (double)$result->harga_room,
                        'lama_inap'         => (double)$result->lama_inap,
                        'sub_total'         => (double)$result->sub_total_room,
                        'disc'          => [
                            'prosentase'    => (double)$result->disc_room_prosentase,
                            'nominal'       => (double)$result->disc_room_nominal,
                        ],
                        'ppn'               => [
                            'prosentase'    => (double)$result->ppn_room_prosentase,
                            'nominal'       => (double)$result->ppn_room_nominal,
                        ],
                        'total'             => (double)$result->total_harga_room,
                    ],
                    'layanan'               => [
                        'harga'             => (double)$result->sub_total_layanan,
                        'disc'              => [
                            'prosentase'    => (double)$result->disc_layanan_prosentase,
                            'nominal'       => (double)$result->disc_layanan_nominal,
                        ],
                        'ppn'               => [
                            'prosentase'    => (double)$result->ppn_layanan_prosentase,
                            'nominal'       => (double)$result->ppn_layanan_nominal,
                        ],
                        'total'             => (double)$result->total_harga_layanan,
                    ],
                ],
                'biaya_lain'            => (double)$result->biaya_lain,
                'grand_total'           => (double)$result->grand_total,
                'pembayaran'            => [
                    'total'             => (double)$result->total_pembayaran,
                    'sisa'              => (double)$result->sisa_pembayaran
                ]
            ];

            return ApiResponse::responseSuccess('success', $data);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanReservasiInHouseEditRoom(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'kode_room'         => 'required',
                'alasan'            => 'required',
                'diskon'            => 'required',
                'companyid'         => 'required',
                'user_id'           => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data transaksi reservasi terlebih dahulu dan isi kode room berserta alasannya');
            }

            $sql = DB::table('room')
                    ->selectRaw("ifnull(room.companyid, '') as companyid, ifnull(room.kode_room, '') as kode_room,
                                ifnull(room.longtime, 0) as longtime, ifnull(room.shorttime, 0) as shorttime,
                                ifnull(room.status, '') as status, ifnull(company.shorttime_default, 0) as default_jam_shorttime")
                    ->leftJoin('company', function($join) {
                        $join->on('company.companyid', '=', 'room.companyid');
                    })
                    ->where('room.kode_room', $request->get('kode_room'))
                    ->where('room.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_room)) {
                return ApiResponse::responseWarning('Kode room yang anda entry tidak terdaftar');
            }

            $kode_room = strtoupper(trim($sql->kode_room));
            $longtime = (double)$sql->longtime;
            $shorttime = (double)$sql->shorttime;
            $harga_room = 0;

            $sql = DB::table('reservasi')
                    ->selectRaw("ifnull(reservasi.companyid, '') as companyid, ifnull(reservasi.kode_reservasi, '') as kode_reservasi,
                                ifnull(date_format(reservasi.tanggal_reservasi, '%Y-%m-%d'), '') as tanggal_reservasi,
                                ifnull(reservasi.no_identitas, '') as no_identitas, ifnull(reservasi.kode_room, '') as kode_room,
                                ifnull(reservasi.kode_platform, '') as kode_platform, ifnull(reservasi.no_referensi, '') as no_referensi,
                                ifnull(reservasi.nama_cp, '') as nama_cp, ifnull(reservasi.telepon_cp, '') as telepon_cp,
                                ifnull(reservasi.status_longtime, 0) as status_longtime, ifnull(reservasi.lama_inap, 0) as lama_inap,
                                ifnull(reservasi.check_in, '') as check_in, ifnull(reservasi.check_out, '') as check_out,
                                ifnull(reservasi.biaya_lain, 0) as biaya_lain, ifnull(reservasi.ppn, 0) as ppn,
                                ifnull(reservasi_layanan.disc, 0) as disc_layanan, ifnull(reservasi_layanan.ppn, 0) as ppn_layanan,
                                ifnull(reservasi.total_pembayaran, 0) as total_pembayaran, ifnull(reservasi.deposit, 0) as deposit,
                                ifnull(reservasi.status_in, 0) as status_in, ifnull(reservasi.keterangan, '') as keterangan,
                                ifnull(reservasi.catatan, '') as catatan")
                    ->leftJoin('reservasi_layanan', function($join) {
                        $join->on('reservasi_layanan.kode_reservasi', '=', 'reservasi.kode_reservasi')
                            ->on('reservasi_layanan.companyid', '=', 'reservasi.companyid');
                    })
                    ->where('reservasi.kode_reservasi', $request->get('kode_reservasi'))
                    ->where('reservasi.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_reservasi)) {
                return ApiResponse::responseWarning('Kode reservasi yang anda entry tidak terdaftar');
            }

            $checkRoom = DB::table('reservasi')
                        ->selectRaw("ifnull(reservasi.kode_reservasi, '') as kode_reservasi")
                        ->where('reservasi.companyid', $request->get('companyid'))
                        ->where('reservasi.kode_room', $kode_room)
                        ->whereRaw("ifnull(reservasi.status_end, 0)=0")
                        ->whereBetween('reservasi.check_in', [
                            date('Y-m-d H:i:s', strtotime($sql->check_in)),
                            date('Y-m-d H:i:s', strtotime($sql->check_out))
                        ])
                        ->first();

            if(!empty($checkRoom->kode_reservasi)) {
                if(strtoupper(trim($checkRoom->kode_reservasi)) != strtoupper(trim($request->get('kode_reservasi')))) {
                    return ApiResponse::responseWarning('Kode room sudah terpakai untuk nomor reservasi '.$checkRoom->kode_reservasi);
                }
            }

            if((int)$sql->status_longtime == 1) {
                $harga_room = (double)$longtime;
            } else {
                $harga_room = (double)$shorttime;
            }

            DB::transaction(function () use ($request, $sql, $harga_room) {
                DB::insert('call sp_reservasi_simpan (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    strtoupper(trim($request->get('user_id'))), strtoupper(trim($request->get('kode_reservasi'))),
                    strtoupper(trim($sql->no_identitas)), strtoupper(trim($request->get('kode_room'))),
                    strtoupper(trim($sql->kode_platform)), strtoupper(trim($sql->no_referensi)),
                    trim($sql->nama_cp), trim($sql->telepon_cp), (int)$sql->status_longtime,
                    trim($sql->check_in), trim($sql->check_out), (int)$sql->lama_inap, (double)$harga_room,
                    (double)$sql->biaya_lain, (double)$request->get('diskon'), (double)$sql->ppn,
                    (double)$sql->disc_layanan, (double)$sql->ppn_layanan, (double)$sql->total_pembayaran,
                    (double)$sql->deposit, (int)$sql->status_in, trim($sql->keterangan), trim($sql->catatan),
                    trim($request->get('alasan')), strtoupper(trim($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Room Berhasil Diubah', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanReservasiInHouseExtendRoom(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'check_out'         => 'required',
                'companyid'         => 'required',
                'user_id'           => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data transaksi reservasi terlebih dahulu dan isi tanggal check out');
            }

            $sql = DB::table('reservasi')
                    ->selectRaw("ifnull(reservasi.companyid, '') as companyid, ifnull(reservasi.kode_reservasi, '') as kode_reservasi,
                                ifnull(date_format(reservasi.tanggal_reservasi, '%Y-%m-%d'), '') as tanggal_reservasi,
                                ifnull(reservasi.no_identitas, '') as no_identitas, ifnull(reservasi.kode_room, '') as kode_room,
                                ifnull(reservasi.kode_platform, '') as kode_platform, ifnull(reservasi.no_referensi, '') as no_referensi,
                                ifnull(reservasi.nama_cp, '') as nama_cp, ifnull(reservasi.telepon_cp, '') as telepon_cp,
                                ifnull(reservasi.status_longtime, 0) as status_longtime, ifnull(reservasi.lama_inap, 0) as lama_inap,
                                ifnull(reservasi.check_in, '') as check_in, ifnull(reservasi.check_out, '') as check_out,
                                ifnull(reservasi.biaya_lain, 0) as biaya_lain, ifnull(reservasi.disc, 0) as disc, ifnull(reservasi.ppn, 0) as ppn,
                                ifnull(reservasi_layanan.disc, 0) as disc_layanan, ifnull(reservasi_layanan.ppn, 0) as ppn_layanan,
                                ifnull(reservasi.total_pembayaran, 0) as total_pembayaran, ifnull(reservasi.deposit, 0) as deposit,
                                ifnull(reservasi.status_in, 0) as status_in, ifnull(reservasi.keterangan, '') as keterangan,
                                ifnull(reservasi.catatan, '') as catatan, ifnull(company.shorttime_default, 0) as shorttime_default,
                                ifnull(reservasi.alasan, '') as alasan, ifnull(reservasi.harga_satuan_room, 0) as harga_satuan_room")
                    ->leftJoin('company', function($join) {
                        $join->on('company.companyid', '=', 'reservasi.companyid');
                    })
                    ->leftJoin('reservasi_layanan', function($join) {
                        $join->on('reservasi_layanan.kode_reservasi', '=', 'reservasi.kode_reservasi')
                            ->on('reservasi_layanan.companyid', '=', 'reservasi.companyid');
                    })
                    ->where('reservasi.kode_reservasi', $request->get('kode_reservasi'))
                    ->where('reservasi.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_reservasi)) {
                return ApiResponse::responseWarning('Kode reservasi yang anda entry tidak terdaftar');
            }

            $checkRoom = DB::table('reservasi')
                        ->selectRaw("ifnull(reservasi.kode_reservasi, '') as kode_reservasi")
                        ->where('reservasi.companyid', $request->get('companyid'))
                        ->where('reservasi.kode_room', $sql->kode_room)
                        ->where('reservasi.check_out', '>=', $sql->check_in)
                        ->where('reservasi.check_in', '<=', $request->get('check_out'))
                        ->first();

            if(!empty($checkRoom->kode_reservasi)) {
                if(strtoupper(trim($checkRoom->kode_reservasi)) != strtoupper(trim($request->get('kode_reservasi')))) {
                    return ApiResponse::responseWarning('Kode room sudah terpakai untuk nomor reservasi '.$checkRoom->kode_reservasi);
                }
            }

            if((int)$sql->status_longtime == 1) {
                $datetimeCheckIn = Carbon::parse(date('Y-m-d', strtotime($sql->check_in)));
                $datetimeCheckOut = Carbon::parse(date('Y-m-d', strtotime($request->get('check_out'))));

                $lama_reservasi = $datetimeCheckIn->diffInDays($datetimeCheckOut);
            } else {
                $datetimeCheckIn = Carbon::parse($sql->check_in);
                $datetimeCheckOut = Carbon::parse($request->get('check_out'));
                $lama_jam_reservasi = $datetimeCheckIn->diffInHours($datetimeCheckOut);

                $lama_reservasi = ceil((double)$lama_jam_reservasi / (double)$sql->shorttime_default);
            }

            DB::transaction(function () use ($request, $sql, $lama_reservasi) {
                DB::insert('call sp_reservasi_simpan (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    strtoupper(trim($request->get('user_id'))), strtoupper(trim($request->get('kode_reservasi'))),
                    strtoupper(trim($sql->no_identitas)), strtoupper(trim($sql->kode_room)),
                    strtoupper(trim($sql->kode_platform)), strtoupper(trim($sql->no_referensi)),
                    trim($sql->nama_cp), trim($sql->telepon_cp), (int)$sql->status_longtime,
                    trim($sql->check_in), trim($request->get('check_out')), (int)$lama_reservasi,
                    (double)$sql->harga_satuan_room, (double)$sql->biaya_lain, (double)$sql->disc, (double)$sql->ppn,
                    (double)$sql->disc_layanan, (double)$sql->ppn_layanan, (double)$sql->total_pembayaran,
                    (double)$sql->deposit, (int)$sql->status_in, trim($sql->keterangan), trim($sql->catatan),
                    trim($sql->alasan), strtoupper(trim($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Extend Room Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanReservasiInHouse(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'disc_layanan'      => 'required',
                'ppn_layanan'       => 'required',
                'companyid'         => 'required',
                'user_id'           => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data transaksi reservasi terlebih dahulu');
            }

            $sql = DB::table('reservasi')
                    ->selectRaw("ifnull(reservasi.companyid, '') as companyid, ifnull(reservasi.kode_reservasi, '') as kode_reservasi,
                                ifnull(date_format(reservasi.tanggal_reservasi, '%Y-%m-%d'), '') as tanggal_reservasi,
                                ifnull(reservasi.no_identitas, '') as no_identitas, ifnull(reservasi.kode_room, '') as kode_room,
                                ifnull(reservasi.kode_platform, '') as kode_platform, ifnull(reservasi.no_referensi, '') as no_referensi,
                                ifnull(reservasi.nama_cp, '') as nama_cp, ifnull(reservasi.telepon_cp, '') as telepon_cp,
                                ifnull(reservasi.status_longtime, 0) as status_longtime, ifnull(reservasi.lama_inap, 0) as lama_inap,
                                ifnull(reservasi.check_in, '') as check_in, ifnull(reservasi.check_out, '') as check_out,
                                ifnull(reservasi.biaya_lain, 0) as biaya_lain, ifnull(reservasi.disc, 0) as disc, ifnull(reservasi.ppn, 0) as ppn,
                                ifnull(reservasi_layanan.disc, 0) as disc_layanan, ifnull(reservasi_layanan.ppn, 0) as ppn_layanan,
                                ifnull(reservasi.total_pembayaran, 0) as total_pembayaran, ifnull(reservasi.deposit, 0) as deposit,
                                ifnull(reservasi.status_in, 0) as status_in, ifnull(reservasi.keterangan, '') as keterangan,
                                ifnull(reservasi.catatan, '') as catatan, ifnull(company.shorttime_default, 0) as shorttime_default,
                                ifnull(reservasi.alasan, '') as alasan, ifnull(reservasi.harga_satuan_room, 0) as harga_satuan_room")
                    ->leftJoin('company', function($join) {
                        $join->on('company.companyid', '=', 'reservasi.companyid');
                    })
                    ->leftJoin('reservasi_layanan', function($join) {
                        $join->on('reservasi_layanan.kode_reservasi', '=', 'reservasi.kode_reservasi')
                            ->on('reservasi_layanan.companyid', '=', 'reservasi.companyid');
                    })
                    ->where('reservasi.kode_reservasi', $request->get('kode_reservasi'))
                    ->where('reservasi.companyid', $request->get('companyid'))
                    ->first();
            if(empty($sql->kode_reservasi)) {
                return ApiResponse::responseWarning('Kode reservasi yang anda entry tidak terdaftar');
            }

            DB::transaction(function () use ($request, $sql) {
                DB::insert('call sp_reservasi_simpan (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    strtoupper(trim($request->get('user_id'))), strtoupper(trim($request->get('kode_reservasi'))),
                    strtoupper(trim($sql->no_identitas)), strtoupper(trim($sql->kode_room)),
                    strtoupper(trim($sql->kode_platform)), strtoupper(trim($sql->no_referensi)),
                    trim($sql->nama_cp), trim($sql->telepon_cp), (int)$sql->status_longtime,
                    trim($sql->check_in), trim($sql->check_out), (int)$sql->lama_inap,
                    (double)$sql->harga_satuan_room, (double)$request->get('biaya_lain'), (double)$sql->disc, (double)$sql->ppn,
                    (double)$request->get('disc_layanan'), (double)$request->get('ppn_layanan'), (double)$request->get('total_pembayaran'),
                    (double)$sql->deposit, (int)$sql->status_in, trim($sql->keterangan), trim($request->get('catatan')),
                    trim($sql->alasan), strtoupper(trim($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Reservasi In-House Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
