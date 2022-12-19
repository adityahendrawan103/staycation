<?php

namespace App\Http\Controllers\Api\Transaksi\Refund;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class RefundController extends Controller
{
    public function daftarRefund(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'year'      => 'required',
                'month'     => 'required',
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data bulan dan tahun terlebih dahulu');
            }

            $sql = DB::table('refund')
                    ->selectRaw("ifnull(refund.kode_refund, '') as kode_refund,
                                ifnull(date_format(refund.tanggal_refund, '%Y-%m-%d'), '') as tanggal,
                                ifnull(refund.kode_reservasi, '') as kode_reservasi,
                                ifnull(reservasi.no_referensi, '') as no_referensi,
                                ifnull(refund.alasan, '') as alasan,
                                ifnull(reservasi.uang_muka, 0) as uang_muka,
                                ifnull(refund.total, 0) as total_refund")
                    ->leftJoin('reservasi', function($join) {
                        $join->on('reservasi.kode_reservasi', '=', 'refund.kode_reservasi')
                            ->on('reservasi.companyid', '=', 'refund.companyid');
                    })
                    ->whereYear('refund.tanggal_refund', $request->get('year'))
                    ->whereMonth('refund.tanggal_refund', $request->get('month'))
                    ->where('refund.companyid', $request->get('companyid'))
                    ->orderByRaw("refund.tanggal_refund desc, refund.kode_booking desc")
                    ->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function detailRefund(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_refund'   => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data transaksi refund terlebih dahulu');
            }

            $sql = DB::table('refund')
                    ->selectRaw("ifnull(refund.kode_refund, '') as kode_refund,
                                ifnull(date_format(refund.tanggal_refund, '%Y-%m-%d'), '') as tanggal,
                                ifnull(refund.kode_booking, '') as kode_booking, ifnull(booking.no_referensi, '') as no_referensi,
                                ifnull(booking.contact_person, '') as contact_person, ifnull(booking.keterangan, '') as keterangan,
                                ifnull(refund.alasan, '') as alasan, ifnull(booking.uang_muka, 0) as uang_muka,
                                ifnull(refund.total, 0) as total_refund")
                    ->leftJoin('booking', function($join) {
                        $join->on('booking.kode_booking', '=', 'refund.kode_booking')
                            ->on('booking.companyid', '=', 'refund.companyid');
                    })
                    ->where('refund.kode_refund', $request->get('kode_refund'))
                    ->where('refund.companyid', $request->get('companyid'))
                    ->first();

            if(empty($sql->kode_refund)) {
                return ApiResponse::responseWarning('Data transaksi refund tidak ditemukan');
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function cekStatusRefund(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_booking'  => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data transaksi booking terlebih dahulu');
            }

            $sql = DB::table('booking')
                    ->selectRaw("ifnull(booking.kode_booking, '') as kode_booking, ifnull(booking.no_referensi, '') as no_referensi,
                                ifnull(booking.contact_person, '') as contact_person, ifnull(booking.keterangan, '') as keterangan,
                                ifnull(booking.uang_muka, 0) as uang_muka, ifnull(booking.check_in, '') as check_in,
                                ifnull(booking.check_out, '') as check_out, ifnull(company.refund_hari, 0) as batas_waktu_refund,
                                datediff(date_format(booking.check_in, '%Y-%m-%d'), date_format(now(), '%Y-%m-%d')) as jtp_refund,
                                ifnull(booking.success, 0) as status_success, ifnull(booking.canceled, 0) as status_cancel,
                                ifnull(company.refund_prosentase, 0) as prosentase_refund,
                                round((ifnull(booking.uang_muka, 0) * ifnull(company.refund_prosentase, 0)) / 100, 0) as total_refund")
                    ->leftJoin('company', function($join) {
                        $join->on('company.companyid', '=', 'booking.companyid');
                    })
                    ->where('booking.kode_booking', strtoupper(trim($request->get('kode_booking'))))
                    ->where('booking.companyid', strtoupper(trim($request->get('companyid'))))
                    ->first();

            if(empty($sql->kode_booking)) {
                return ApiResponse::responseWarning('Data transaksi booking tidak ditemukan');
            } else {
                $status_success = (double)$sql->status_success;
                $status_cancel = (double)$sql->status_cancel;
                $jtp_refund = $sql->jtp_refund;
                $batas_waktu_refund = $sql->batas_waktu_refund;

                if($status_success == 1) {
                    return ApiResponse::responseWarning('Data transaksi booking yang anda entry tidak bisa di refund karena sudah melakukan proses reservasi');
                }
                if($status_cancel == 1) {
                    return ApiResponse::responseWarning('Data transaksi booking yang anda entry sudah pernah melakukan proses refund pada transaksi sebelumnya');
                }
                if((double)$batas_waktu_refund > (double)$jtp_refund) {
                    return ApiResponse::responseWarning('Data transaksi booking yang anda entry tidak bisa di refund, karena sudah melebihi batas waktu yang sudah ditentukan. Maksimal jangka waktu refund '.(double)$batas_waktu_refund.' hari sebelum tanggal check in');
                }
            }

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function simpanRefund(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_refund'   => 'required',
                'kode_booking'  => 'required',
                'tanggal'       => 'required',
                'alasan'        => 'required',
                'total'         => 'required',
                'companyid'     => 'required',
                'user_id'       => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data transaksi refund secara lengkap');
            }

            $sql = DB::table('booking')
                    ->selectRaw("ifnull(booking.kode_booking, '') as kode_booking, ifnull(booking.no_referensi, '') as no_referensi,
                                ifnull(booking.contact_person, '') as contact_person, ifnull(booking.keterangan, '') as keterangan,
                                ifnull(booking.uang_muka, 0) as uang_muka, ifnull(booking.check_in, '') as check_in,
                                ifnull(booking.check_out, '') as check_out, ifnull(company.refund_hari, 0) as batas_waktu_refund,
                                datediff(date_format(booking.check_in, '%Y-%m-%d'), date_format(now(), '%Y-%m-%d')) as jtp_refund,
                                ifnull(booking.success, 0) as status_success, ifnull(booking.canceled, 0) as status_cancel,
                                ifnull(company.refund_prosentase, 0) as prosentase_refund,
                                round((ifnull(booking.uang_muka, 0) * ifnull(company.refund_prosentase, 0)) / 100, 0) as total_refund")

                    ->leftJoin('company', function($join) {
                        $join->on('company.companyid', '=', 'booking.companyid');
                    })
                    ->where('booking.kode_booking', strtoupper(trim($request->get('kode_booking'))))
                    ->where('booking.companyid', strtoupper(trim($request->get('companyid'))))
                    ->first();

            if(empty($sql->kode_booking)) {
                return ApiResponse::responseWarning('Data transaksi booking tidak ditemukan');
            } else {
                $total_refund = (double)$sql->total_refund;
                $status_success = (double)$sql->status_success;
                $status_cancel = (double)$sql->status_cancel;
                $jtp_refund = $sql->jtp_refund;
                $batas_waktu_refund = $sql->batas_waktu_refund;

                if($status_success == 1) {
                    return ApiResponse::responseWarning('Data transaksi booking yang anda entry tidak bisa di refund karena sudah melakukan proses reservasi');
                }
                if($status_cancel == 1) {
                    return ApiResponse::responseWarning('Data transaksi booking yang anda entry sudah pernah melakukan proses refund pada transaksi sebelumnya');
                }
                if((double)$batas_waktu_refund > (double)$jtp_refund) {
                    return ApiResponse::responseWarning('Data transaksi booking yang anda entry tidak bisa di refund, karena sudah melebihi batas waktu yang sudah ditentukan. Maksimal jangka waktu refund '.(double)$batas_waktu_refund.' hari sebelum tanggal check in');
                }
                if((double)$request->get('total') > (double)$sql->total_refund) {
                    return ApiResponse::responseWarning('Jumlah nominal maksimal refund Rp. '.number_format((double)$total_refund));
                }
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_refund_simpan (?,?,?,?,?,?,?)', [
                    trim(strtoupper($request->get('kode_refund'))), trim(strtoupper($request->get('kode_booking'))),
                    trim($request->get('tanggal')), trim($request->get('alasan')), (double)$request->get('total'),
                    trim(strtoupper($request->get('companyid'))), trim(strtoupper($request->get('user_id')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }

    public function hapusRefund(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'kode_refund'   => 'required',
                'companyid'     => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data transaksi refund terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_refund_hapus (?,?)', [
                    trim(strtoupper($request->get('kode_refund'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
