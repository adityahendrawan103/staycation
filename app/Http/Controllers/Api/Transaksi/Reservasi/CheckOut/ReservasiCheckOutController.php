<?php

namespace App\Http\Controllers\Api\Transaksi\Reservasi\CheckOut;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class ReservasiCheckOutController extends Controller
{
    public function daftarReservasiCheckOut(Request $request) {
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

    public function detailReservasiCheckOut(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'companyid'         => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih kode reservasi terlebih dahulu');
            }

            $sql = "select  ifnull(reservasi.companyid, '') as companyid, ifnull(company.logo, '') as logo_company,
                            ifnull(company.nama, '') as nama_company, ifnull(company.alamat, '') as alamat_company,
                            ifnull(company.kota, '') as kota_company, ifnull(reservasi.kode_reservasi, '') as kode_reservasi,
                            ifnull(reservasi.kode_platform, '') as kode_platform, ifnull(platform.nama_platform, '') as nama_platform,
                            ifnull(reservasi.no_referensi, '') as nomor_referensi, ifnull(reservasi.nama_cp, '') as nama_cp,
                            ifnull(reservasi.telepon_cp, '') as telepon_cp, ifnull(reservasi.no_identitas, '') as no_identitas,
                            ifnull(customer.nama, '') as nama_customer, ifnull(customer.kota, '') as kota_customer,
                            ifnull(customer.telepon, '') as telepon_customer, ifnull(reservasi.kode_room, '') as kode_room,
                            ifnull(room.kode_tipe, '') as kode_tipe, ifnull(room_tipe.nama_tipe, '') as nama_tipe_room,
                            ifnull(reservasi.check_in, '') as check_in, ifnull(reservasi.check_out, '') as check_out,
                            ifnull(reservasi.status_longtime, 0) as status_longtime, ifnull(reservasi.harga_satuan_room, 0) as harga_satuan_room,
                            ifnull(reservasi.harga_room, 0) as sub_total_room, ifnull(reservasi.lama_inap, 0) as lama_inap,
                            ifnull(reservasi.disc, 0) as disc_room, ifnull(reservasi.disc_rp, 0) as disc_rp_room, ifnull(reservasi.ppn, 0) as ppn_room,
                            ifnull(reservasi.ppn_rp, 0) as ppn_rp_room, ifnull(reservasi.total_room, 0) as total_room,
                            ifnull(reservasi_detail.kode_layanan, '') as kode_layanan, ifnull(layanan.nama_layanan, '') as nama_layanan,
                            ifnull(reservasi_detail.qty, 0) as qty_layanan, ifnull(reservasi_detail.harga, 0) as harga_layanan,
                            ifnull(reservasi.disc, 0) as disc_detail_layanan, ifnull(reservasi_detail.total, 0) as total_detail_layanan,
                            ifnull(reservasi_layanan.total, 0) as sub_total_layanan, ifnull(reservasi_layanan.disc, 0) as disc_layanan,
                            ifnull(reservasi_layanan.disc_rp, 0) as disc_rp_layanan, ifnull(reservasi_layanan.ppn, 0) as ppn_layanan,
                            ifnull(reservasi_layanan.ppn_rp, 0) as ppn_rp_layanan, ifnull(reservasi.total_layanan, 0) as total_layanan,
                            ifnull(reservasi.biaya_lain, 0) as biaya_lain, ifnull(reservasi.grand_total, 0) as grand_total,
                            ifnull(reservasi.total_pembayaran, 0) as total_pembayaran, ifnull(reservasi.sisa_pembayaran, 0) as sisa_pembayaran,
                            ifnull(reservasi.penalty, 0) as penalty, ifnull(reservasi.pembayaran_penalty, 0) as pembayaran_penalty,
                            ifnull(reservasi.sisa_pembayaran_penalty, 0) as sisa_pembayaran_penalty, ifnull(reservasi.keterangan, '') as keterangan,
                            ifnull(reservasi.catatan, '') as catatan, ifnull(reservasi.alasan, '') as alasan, ifnull(reservasi.deposit, 0) as deposit
                    from
                    (
                    select  reservasi.companyid, reservasi.kode_reservasi, reservasi.kode_platform, reservasi.no_identitas,
                            reservasi.nama_cp, reservasi.telepon_cp, reservasi.kode_room, reservasi.status_longtime,
                            reservasi.check_in, reservasi.check_out, reservasi.lama_inap, reservasi.harga_satuan_room,
                            reservasi.harga_room, reservasi.disc, reservasi.disc_rp, reservasi.ppn, reservasi.ppn_rp,
                            reservasi.total_room, reservasi.total_layanan, reservasi.biaya_lain, reservasi.grand_total,
                            reservasi.total_pembayaran, reservasi.sisa_pembayaran, reservasi.penalty, reservasi.pembayaran_penalty,
                            reservasi.sisa_pembayaran_penalty, reservasi.keterangan, reservasi.catatan, reservasi.alasan,
                            reservasi.no_referensi, reservasi.deposit
                    from    reservasi
                    where   reservasi.kode_reservasi=? and reservasi.companyid=?
                    )   reservasi
                            inner join company on reservasi.companyid=company.companyid
                            left join platform on reservasi.kode_platform=platform.kode_platform and
                                reservasi.companyid=platform.companyid
                            left join room on reservasi.kode_room=room.kode_room and
                                reservasi.companyid=room.companyid
                            left join room_tipe on room.kode_tipe=room_tipe.kode_tipe and
                                reservasi.companyid=room_tipe.companyid
                            left join customer on reservasi.no_identitas=customer.no_identitas and
                                reservasi.companyid=customer.companyid
                            left join reservasi_detail on reservasi.kode_reservasi=reservasi_detail.kode_reservasi and
                                reservasi.companyid=reservasi_detail.companyid
                            left join layanan on reservasi_detail.kode_layanan=layanan.kode_layanan and
                                reservasi.companyid=layanan.companyid
                            left join reservasi_layanan on reservasi.kode_reservasi=reservasi_layanan.kode_reservasi and
                                reservasi.companyid=reservasi_layanan.companyid
                    order by reservasi.companyid asc, reservasi.kode_reservasi asc, reservasi_detail.kode_layanan asc";

            $result = DB::select($sql, [ $request->get('kode_reservasi'), $request->get('companyid') ]);

            $data_detail_layanan = [];

            foreach($result as $data) {
                $data_detail_layanan[] = [
                    'kode_layanan'      => strtoupper(trim($data->kode_layanan)),
                    'nama_layanan'      => strtoupper(trim($data->nama_layanan)),
                    'harga'             => (double)$data->harga_layanan,
                    'qty'               => (double)$data->qty_layanan,
                    'disc_detail'       => (double)$data->disc_detail_layanan,
                    'total'             => (double)$data->total_detail_layanan,
                ];

                $companyid      = strtoupper(trim($data->companyid));
                $logo_company   = trim($data->logo_company);
                $nama_company   = trim($data->nama_company);
                $alamat_company = trim($data->alamat_company);
                $kota_company   = trim($data->kota_company);
                $kode_reservasi = strtoupper(trim($data->kode_reservasi));
                $kode_platform   = trim($data->kode_platform);
                $nama_platform   = trim($data->nama_platform);
                $nomor_referensi   = trim($data->nomor_referensi);
                $nama_cp   = trim($data->nama_cp);
                $telepon_cp   = trim($data->telepon_cp);
                $no_identitas   = trim($data->no_identitas);
                $nama_customer  = trim($data->nama_customer);
                $kota_customer  = trim($data->kota_customer);
                $telepon_customer = trim($data->telepon_customer);
                $kode_room      = trim($data->kode_room);
                $kode_tipe      = trim($data->kode_tipe);
                $nama_tipe_room = trim($data->nama_tipe_room);
                $check_in       = trim($data->check_in);
                $check_out      = trim($data->check_out);
                $harga_satuan_room = (double)$data->harga_satuan_room;
                $lama_inap      = (double)$data->lama_inap;
                $status_longtime = trim($data->status_longtime);
                $sub_total_room     = (double)$data->sub_total_room;
                $disc_room      = (double)$data->disc_room;
                $disc_rp_room   = (double)$data->disc_rp_room;
                $ppn_room       = (double)$data->ppn_room;
                $ppn_rp_room    = (double)$data->ppn_rp_room;
                $total_room     = (double)$data->total_room;
                $sub_total_layanan = (double)$data->sub_total_layanan;
                $disc_layanan   = (double)$data->disc_layanan;
                $disc_rp_layanan = (double)$data->disc_rp_layanan;
                $ppn_layanan    = (double)$data->ppn_layanan;
                $ppn_rp_layanan = (double)$data->ppn_rp_layanan;
                $total_layanan  = (double)$data->total_layanan;
                $biaya_lain     = (double)$data->biaya_lain;
                $grand_total    = (double)$data->grand_total;
                $total_pembayaran = (double)$data->total_pembayaran;
                $sisa_pembayaran = (double)$data->sisa_pembayaran;
                $total_penalty = (double)$data->penalty;
                $total_pembayaran_penalty = (double)$data->pembayaran_penalty;
                $sisa_pembayaran_penalty = (double)$data->sisa_pembayaran_penalty;
                $deposit = (double)$data->deposit;
                $status_longtime = (int)$data->status_longtime;
                $keterangan = trim($data->keterangan);
                $alasan = trim($data->alasan);
                $catatan = trim($data->catatan);
                $status_pembayaran_reservasi = ((double)$data->grand_total > (double)$data->total_pembayaran) ? 0 : 1;
                $status_pembayaran_penalty = ((double)$data->penalty > (double)$data->pembayaran_penalty) ? 0 : 1;
            }

            $data_penalty = DB::table('penalty')
                    ->selectRaw("ifnull(penalty.kode_reservasi, '') as kode_reservasi,
                                ifnull(penalty.kode_item, '') as kode_item,
                                ifnull(item.nama_item, '') as nama_item,
                                ifnull(penalty.keterangan, '') as keterangan,
                                ifnull(penalty.qty, 0) as qty,
                                ifnull(penalty.denda, 0) as denda,
                                ifnull(penalty.total, 0) as total")
                    ->leftJoin('item', function($join) {
                        $join->on('item.kode_item', '=', 'penalty.kode_item')
                            ->on('item.companyid', '=', 'penalty.companyid');
                    })
                    ->where('penalty.kode_reservasi', $request->get('kode_reservasi'))
                    ->where('penalty.companyid', $request->get('companyid'))
                    ->get();

            $data = [
                'kode_reservasi' => $kode_reservasi,
                'company'       => [
                    'companyid' => $companyid,
                    'nama'      => $nama_company,
                    'alamat'    => $alamat_company,
                    'kota'      => $kota_company,
                    'logo'      => $logo_company,
                ],
                'platform'          => [
                    'no_referensi'  => $nomor_referensi,
                    'kode'          => $kode_platform,
                    'nama'          => $nama_platform,
                ],
                'customer'          => [
                    'customer'      => [
                        'no_identitas'  => $no_identitas,
                        'nama'          => $nama_customer,
                        'kota'          => $kota_customer,
                        'telepon'       => $telepon_customer,
                    ],
                    'contact_person' => [
                        'nama'          => $nama_cp,
                        'telepon'       => $telepon_cp,
                    ],
                ],
                'reservasi'     => [
                    'check_in'      => $check_in,
                    'check_out'     => $check_out,
                    'room'      => [
                        'kode_room'     => $kode_room,
                        'kode_tipe'     => $kode_tipe,
                        'nama_tipe'     => $nama_tipe_room,
                        'harga'         => (double)$harga_satuan_room,
                        'lama_inap'     => (double)$lama_inap,
                        'sub_total'     => (double)$sub_total_room,
                        'status_longtime' => $status_longtime,
                        'disc_room'     => (double)$disc_room,
                        'disc_rp_room'  => (double)$disc_rp_room,
                        'ppn_room'      => (double)$ppn_room,
                        'ppn_rp_room'   => (double)$ppn_rp_room,
                        'total_room'    => (double)$total_room,
                    ],
                    'layanan'   => [
                        'sub_total_layanan' => (double)$sub_total_layanan,
                        'disc_layanan'      => (double)$disc_layanan,
                        'disc_rp_layanan'   => (double)$disc_rp_layanan,
                        'ppn_layanan'       => (double)$ppn_layanan,
                        'ppn_rp_layanan'    => (double)$ppn_rp_layanan,
                        'total_layanan'     => (double)$total_layanan,
                        'detail'            => $data_detail_layanan
                    ],
                    'total_room'        => (double)$total_room,
                    'total_layanan'     => (double)$total_layanan,
                    'biaya_lain'        => (double)$biaya_lain,
                    'grand_total'       => (double)$grand_total,
                    'total_pembayaran'  => (double)$total_pembayaran,
                    'sisa_pembayaran'   => (double)$sisa_pembayaran,
                    'penalty'           => [
                        'total'         => (double)$total_penalty,
                        'total_pembayaran'  => (double)$total_pembayaran_penalty,
                        'sisa_pembayaran'   => (double)$sisa_pembayaran_penalty,
                        'detail'        => $data_penalty
                    ],
                    'deposit'           => (double)$deposit,
                    'keterangan'        => $keterangan,
                    'alasan'            => $alasan,
                    'catatan'           => $catatan,
                    'status'            => [
                        'status_pembayaran_reservasi'   => (int)$status_pembayaran_reservasi,
                        'status_pembayaran_penalty'     => (int)$status_pembayaran_penalty
                    ],
                ],
            ];
            return ApiResponse::responseSuccess('success', $data);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekStatusPembayaranReservasi(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'companyid'         => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih kode reservasi terlebih dahulu');
            }

            $sql = DB::table('reservasi')
                    ->selectRaw("ifnull(reservasi.kode_reservasi, '') as kode_reservasi,
                                ifnull(reservasi.grand_total, 0) as grand_total,
                                ifnull(reservasi.total_pembayaran, 0) as total_pembayaran,
                                ifnull(reservasi.sisa_pembayaran, 0) as sisa_pembayaran,
                                ifnull(reservasi.penalty, 0) as penalty,
                                ifnull(reservasi.pembayaran_penalty, 0) as pembayaran_penalty,
                                ifnull(reservasi.sisa_pembayaran_penalty, 0) as sisa_pembayaran_penalty")
                    ->where('reservasi.kode_reservasi', $request->get('kode_reservasi'))
                    ->where('reservasi.companyid', $request->get('companyid'))
                    ->whereRaw("ifnull(reservasi.status_end, 0) = 0 and ifnull(reservasi.canceled, 0) = 0 and
                                ifnull(reservasi.status_out, 0) = 0 and ifnull(reservasi.status_in, 0) = 1")
                    ->first();

            if(empty($sql->kode_reservasi)) {
                return ApiResponse::responseWarning('Kode reservasi tidak terdaftar');
            } else {
                $status_pembayaran = 1;
                $keterangan = '';
                $keterangan_pembayaran_room = '';
                $keterangan_pembayaran_penalty = '';

                if((double)$sql->grand_total > (double)$sql->total_pembayaran) {
                    $status_pembayaran = 0;
                    $keterangan_pembayaran_room = 'Pembayaran reservasi ruangan dan layanan masih belum selesai';
                }

                if((double)$sql->penalty > (double)$sql->pembayaran_penalty) {
                    $status_pembayaran = 0;
                    $keterangan_pembayaran_penalty = 'Pembayaran penalty masih belum selesai';
                }

                if($status_pembayaran == 0) {
                    if($keterangan_pembayaran_room != '' && $keterangan_pembayaran_penalty != '') {
                        $keterangan = 'Pembayaran reservasi ruangan, layanan, dan penalty masih belum selesai';
                    } else {
                        if($keterangan_pembayaran_room != '') {
                            $keterangan = $keterangan_pembayaran_room;
                        } else {
                            $keterangan = $keterangan_pembayaran_penalty;
                        }
                    }
                    return ApiResponse::responseWarning($keterangan);
                } else {
                    return ApiResponse::responseSuccess('success', '');
                }
            }
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanReservasiCheckOut(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_reservasi'    => 'required',
                'password'          => 'required',
                'password_confirm'  => 'required',
                'companyid'         => 'required',
                'user_id'           => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih kode reservasi terlebih dahulu dan isikan password anda');
            }

            if(trim($request->get('password')) != $request->get('password_confirm')) {
                return ApiResponse::responseWarning('Data password dan data password konfirmasi tidak sama');
            }

            $sql = DB::table('users')
                    ->selectRaw("ifnull(users.user_id, '') as user_id, ifnull(users.password, '') as password")
                    ->where('users.user_id', $request->get('user_id'))
                    ->where('users.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->user_id)) {
                return ApiResponse::responseWarning('Data user id anda tidak terdaftar');
            }

            if((Hash::check(trim($request->get('password')), $sql->password, [])) == false) {
                return ApiResponse::responseWarning('Data password yang anda entry salah');
            }


            $sql = DB::table('reservasi')
                    ->select('kode_reservasi')
                    ->where('reservasi.kode_reservasi', $request->get('kode_reservasi'))
                    ->where('reservasi.companyid', $request->get('companyid'))
                    ->whereRaw("ifnull(reservasi.status_end, 0) = 0 and ifnull(reservasi.canceled, 0) = 0 and
                                ifnull(reservasi.status_out, 0) = 0 and ifnull(reservasi.status_in, 0) = 1")
                    ->first();

            if(empty($sql->kode_reservasi)) {
                return ApiResponse::responseWarning('Kode reservasi tidak terdaftar');
            } else {
                DB::transaction(function () use ($request) {
                    DB::insert('call sp_reservasi_checkout (?,?,?)', [
                        strtoupper(trim($request->get('kode_reservasi'))), strtoupper(trim($request->get('companyid'))),
                        strtoupper(trim($request->get('user_id')))
                    ]);
                });
            }
            return ApiResponse::responseSuccess('Data Reservasi Berhasil Di Check Out');
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
