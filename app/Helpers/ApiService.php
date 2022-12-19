<?php

namespace App\Helpers;

use App\Helpers\ApiRequest;

class ApiService
{
    public static function AuthLogin($email_username = '', $password = '')
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'auth/login';
        $header = ['Authorization' => $credential];
        $body = [
            'email_username'    => $email_username,
            'password'          => $password
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function AuthForgotPassword($email_username)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'auth/forgotpassword';
        $header = ['Authorization' => $credential];
        $body = [
            'email_username'    => $email_username
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Company
    //==================================================================================
    public static function CompanyDetail($companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'company/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Reservasi CheckIn
    //==================================================================================
    public static function ReservasiCheckInDaftar($page, $per_page, $status, $filter, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'status'    => $status,
            'filter'    => $filter,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiCheckInHapusTmp($user_id, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/hapustmp';
        $header = ['Authorization' => $credential];
        $body = [
            'user_id'   => $user_id,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiCheckInSimpan($kode_reservasi, $no_identitas, $kode_room, $kode_platform, $no_referensi, $nama_cp, $telepon_cp,
            $status_longtime, $tanggal_check_in, $tanggal_check_out, $jam_check_in, $jam_check_out, $disc_room, $ppn_room, $disc_layanan, $ppn_layanan,
            $biaya_lain, $total_pembayaran, $deposit, $keterangan, $catatan, $alasan, $status_check_in, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'no_identitas'      => $no_identitas,
            'kode_room'         => $kode_room,
            'kode_platform'     => $kode_platform,
            'no_referensi'      => $no_referensi,
            'nama_cp'           => $nama_cp,
            'telepon_cp'        => $telepon_cp,
            'status_longtime'   => $status_longtime,
            'tanggal_check_in'  => $tanggal_check_in,
            'tanggal_check_out' => $tanggal_check_out,
            'jam_check_in'      => $jam_check_in,
            'jam_check_out'     => $jam_check_out,
            'disc_room'         => $disc_room,
            'ppn_room'          => $ppn_room,
            'disc_layanan'      => $disc_layanan,
            'ppn_layanan'       => $ppn_layanan,
            'biaya_lain'        => $biaya_lain,
            'total_pembayaran'  => $total_pembayaran,
            'deposit'           => $deposit,
            'keterangan'        => $keterangan,
            'catatan'           => $catatan,
            'alasan'            => $alasan,
            'status_check_in'   => $status_check_in,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiCheckInHapus($kode_reservasi, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiCheckInDetail($kode_reservasi, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiCheckInCekNoReferensi($kode_reservasi, $no_referensi, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/cek/noreferensi';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'no_referensi'      => $no_referensi,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiCheckInCheckIn($kode_reservasi, $password, $password_confirm, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/check-in';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'password'          => $password,
            'password_confirm'  => $password_confirm,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Reservasi CheckIn Detail
    //==================================================================================
    public static function ReservasiCheckInDetailDaftar($kode_reservasi, $diskon, $ppn, $user_id, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/detail/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'diskon'            => $diskon,
            'ppn'               => $ppn,
            'user_id'           => $user_id,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }


    public static function ReservasiCheckInDetailForm($kode_reservasi, $kode_layanan, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/detail/form';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'kode_layanan'      => $kode_layanan,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiCheckInDetailSimpan($kode_reservasi, $kode_layanan, $jumlah, $diskon, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/detail/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'kode_layanan'      => $kode_layanan,
            'jumlah'            => $jumlah,
            'diskon'            => $diskon,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiCheckInDetailHapus($kode_reservasi, $kode_layanan, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkin/detail/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'kode_layanan'      => $kode_layanan,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Reservasi In-House
    //==================================================================================
    public static function ReservasiInHouseDaftar($page, $per_page, $filter, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/inhouse/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'filter'    => $filter,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiInHouseDetail($kode_reservasi, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/inhouse/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiInHouseSimpanEditRoom($kode_reservasi, $kode_room, $alasan, $diskon, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/inhouse/simpan/editroom';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'kode_room'         => $kode_room,
            'alasan'            => $alasan,
            'diskon'            => $diskon,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiInHouseSimpanExtendRoom($kode_reservasi, $check_out, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/inhouse/simpan/extendroom';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'check_out'         => $check_out,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiInHouseSimpan($kode_reservasi, $disc_layanan, $ppn_layanan, $biaya_lain, $total_pembayaran, $catatan, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/inhouse/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'disc_layanan'      => $disc_layanan,
            'ppn_layanan'       => $ppn_layanan,
            'biaya_lain'        => $biaya_lain,
            'total_pembayaran'  => $total_pembayaran,
            'catatan'           => $catatan,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Reservasi Penalty
    //==================================================================================
    public static function ReservasiPenaltyDaftar($page, $per_page, $filter, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/penalty/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'filter'    => $filter,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiPenaltyForm($kode_reservasi, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/penalty/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiPenaltySimpan($kode_reservasi, $pembayaran_penalty, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/penalty/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'        => $kode_reservasi,
            'pembayaran_penalty'    => $pembayaran_penalty,
            'companyid'             => $companyid,
            'user_id'               => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiPenaltyHapus($kode_reservasi, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/penalty/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'        => $kode_reservasi,
            'companyid'             => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Reservasi Penalty Detail
    //==================================================================================
    public static function ReservasiPenaltyDetailDaftar($kode_reservasi, $user_id, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/penalty/detail/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'user_id'           => $user_id,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiPenaltyDetailForm($kode_reservasi, $kode_item, $user_id, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/penalty/detail/form';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'kode_item'         => $kode_item,
            'user_id'           => $user_id,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiPenaltyDetailSimpan($kode_reservasi, $kode_item, $keterangan, $qty, $denda, $user_id, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/penalty/detail/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'kode_item'         => $kode_item,
            'keterangan'        => $keterangan,
            'qty'               => $qty,
            'denda'             => $denda,
            'user_id'           => $user_id,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiPenaltyDetailHapus($kode_reservasi, $kode_item, $user_id, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/penalty/detail/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'kode_item'         => $kode_item,
            'user_id'           => $user_id,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Reservasi Out
    //==================================================================================
    public static function ReservasiCheckOutDetail($kode_reservasi, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkout/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiCheckOutCekPembayaran($kode_reservasi, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkout/detail/cek/pembayaran';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiCheckOutSimpan($kode_reservasi, $password, $password_confirm, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/checkout/detail/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'password'          => $password,
            'password_confirm'  => $password_confirm,
            'companyid'         => $companyid,
            'user_id'           => $user_id,
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Reservasi History
    //==================================================================================
    public static function ReservasiHistoryDaftar($start_date, $end_date, $page, $per_page, $filter, $search, $sortby, $ascdesc, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/history/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'start_date'    => $start_date,
            'end_date'      => $end_date,
            'page'          => $page ?? 1,
            'per_page'      => $per_page ?? 10,
            'filter'        => $filter,
            'search'        => $search,
            'sortby'        => $sortby ?? 'KODE_RESERVASI',
            'ascdesc'       => $ascdesc ?? 'asc',
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ReservasiHistoryDetail($kode_reservasi, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/history/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_reservasi'    => $kode_reservasi,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Refund
    //==================================================================================
    public static function RefundDaftar($year, $month, $page, $per_page, $filter, $search, $sortby, $ascdesc, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'reservasi/history/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'year'              => $year,
            'month'             => $month,
            'page'              => $page ?? 1,
            'per_page'          => $per_page ?? 10,
            'filter'            => $filter,
            'search'            => $search,
            'sortby'            => $sortby ?? 'KODE_RESERVASI',
            'ascdesc'           => $ascdesc ?? 'asc',
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Customer
    //==================================================================================
    public static function CustomerDaftar($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'customer/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function CustomerDetail($no_identitas, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'customer/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'no_identitas'    => $no_identitas,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function CustomerCekNoIdentitas($no_identitas, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'customer/cek/tidak-terdaftar';
        $header = ['Authorization' => $credential];
        $body = [
            'no_identitas'    => $no_identitas,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function CustomerSimpan($no_identitas, $jenis_identitas, $nama, $tempat_lahir, $tanggal_lahir,
                            $jenis_kelamin, $alamat, $kota, $pekerjaan, $telepon, $email, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'customer/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'no_identitas'      => $no_identitas,
            'jenis_identitas'   => $jenis_identitas,
            'nama'              => $nama,
            'tempat_lahir'      => $tempat_lahir,
            'tanggal_lahir'     => $tanggal_lahir,
            'jenis_kelamin'     => $jenis_kelamin,
            'alamat'            => $alamat,
            'kota'              => $kota,
            'pekerjaan'         => $pekerjaan,
            'telepon'           => $telepon,
            'email'             => $email,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function CustomerHapus($no_identitas, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'customer/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'no_identitas'  => $no_identitas,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Fasilitas
    //==================================================================================
    public static function FasilitasDaftar($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'fasilitas/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function FasilitasDetail($kode_fasilitas, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'fasilitas/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_fasilitas'    => $kode_fasilitas,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function FasilitasCekKodeFasilitas($kode_fasilitas, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'fasilitas/cek/tidak-terdaftar';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_fasilitas'    => $kode_fasilitas,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function FasilitasSimpan($kode_fasilitas, $nama_fasilitas, $harga, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'fasilitas/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_fasilitas'    => $kode_fasilitas,
            'nama_fasilitas'    => $nama_fasilitas,
            'harga'             => $harga,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function FasilitasHapus($kode_fasilitas, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'fasilitas/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_fasilitas'  => $kode_fasilitas,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Item
    //==================================================================================
    public static function ItemDaftar($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'item/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ItemDetail($kode_item, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'item/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_item'    => $kode_item,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ItemCekKodeItem($kode_item, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'item/cek/tidak-terdaftar';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_item'    => $kode_item,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ItemSimpan($kode_item, $nama_item, $denda, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'item/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_item'     => $kode_item,
            'nama_item'     => $nama_item,
            'denda'         => $denda,
            'companyid'     => $companyid,
            'user_id'       => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function ItemHapus($kode_item, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'item/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_item'  => $kode_item,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Jabatan
    //==================================================================================
    public static function JabatanDaftar($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'jabatan/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function JabatanDetail($kode_jabatan, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'jabatan/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_jabatan'  => $kode_jabatan,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function JabatanCekKodeJabatan($kode_jabatan, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'jabatan/cek/tidak-terdaftar';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_jabatan'  => $kode_jabatan,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function JabatanSimpan($kode_jabatan, $nama_jabatan, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'jabatan/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_jabatan'  => $kode_jabatan,
            'nama_jabatan'  => $nama_jabatan,
            'companyid'     => $companyid,
            'user_id'       => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function JabatanHapus($kode_jabatan, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'jabatan/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_jabatan'  => $kode_jabatan,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Karyawan
    //==================================================================================
    public static function KaryawanDaftar($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'karyawan/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function KaryawanDetail($nik, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'karyawan/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'nik'       => $nik,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function KaryawanCekNIK($nik, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'karyawan/cek/nik/tidak-terdaftar';
        $header = ['Authorization' => $credential];
        $body = [
            'nik'       => $nik,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function KaryawanCekKTP($nik, $no_ktp, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'karyawan/cek/ktp/tidak-terdaftar';
        $header = ['Authorization' => $credential];
        $body = [
            'nik'       => $nik,
            'no_ktp'    => $no_ktp,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function KaryawanSimpan($nik, $no_ktp, $nama, $jenis_kelamin, $jabatan, $tempat_lahir, $tanggal_lahir,
            $alamat, $rt, $rw, $kelurahan, $kecamatan, $kabupaten, $provinsi, $agama, $telepon, $foto, $status,
            $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'karyawan/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'nik'           => $nik,
            'no_ktp'        => $no_ktp,
            'nama'          => $nama,
            'jenis_kelamin' => $jenis_kelamin,
            'jabatan'       => $jabatan,
            'tempat_lahir'  => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat'        => $alamat,
            'rt'            => $rt,
            'rw'            => $rw,
            'kelurahan'     => $kelurahan,
            'kecamatan'     => $kecamatan,
            'kabupaten'     => $kabupaten,
            'provinsi'      => $provinsi,
            'agama'         => $agama,
            'telepon'       => $telepon,
            'foto'          => $foto,
            'status'        => $status,
            'companyid'     => $companyid,
            'user_id'       => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function KaryawanHapus($nik, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'karyawan/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'nik'       => $nik,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Layanan
    //==================================================================================
    public static function LayananDaftar($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'layanan/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function LayananDetail($kode_layanan, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'layanan/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_layanan'  => $kode_layanan,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function LayananCekKodeLayanan($kode_layanan, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'layanan/cek/tidak-terdaftar';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_layanan'  => $kode_layanan,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function LayananSimpan($kode_layanan, $nama_layanan, $satuan, $harga, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'layanan/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_layanan'  => $kode_layanan,
            'nama_layanan'  => $nama_layanan,
            'satuan'        => $satuan,
            'harga'         => $harga,
            'companyid'     => $companyid,
            'user_id'       => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function LayananHapus($kode_layanan, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'layanan/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_layanan'  => $kode_layanan,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Platform
    //==================================================================================
    public static function PlatformDaftar($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'platform/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function PlatformDetail($kode_platform, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'platform/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_platform'  => $kode_platform,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function PlatformCekKodePlatform($kode_platform, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'platform/cek/tidak-terdaftar';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_platform'  => $kode_platform,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function PlatformSimpan($kode_platform, $nama_platform, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'platform/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_platform'     => $kode_platform,
            'nama_platform'     => $nama_platform,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function PlatformHapus($kode_platform, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'platform/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_platform'  => $kode_platform,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Room
    //==================================================================================
    public static function RoomDaftar($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomCekKodeRoom($kode_room, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/cek/tidak-terdaftar';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_room' => $kode_room,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomDetail($kode_room, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_room' => $kode_room,
            'companyid' => $companyid,
            'user_id'   => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomHapusTmp($user_id, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/hapustmp';
        $header = ['Authorization' => $credential];
        $body = [
            'user_id'   => $user_id,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomHapus($kode_room, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_room' => $kode_room,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomSimpan($kode_room, $kode_tipe, $lantai, $kapasitas, $longtime, $shorttime, $keterangan, $status, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_room'     => $kode_room,
            'kode_tipe'     => $kode_tipe,
            'lantai'        => $lantai,
            'kapasitas'     => $kapasitas,
            'longtime'      => $longtime,
            'shorttime'     => $shorttime,
            'keterangan'    => $keterangan,
            'status'        => $status,
            'companyid'     => $companyid,
            'user_id'       => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Room Detail
    //==================================================================================
    public static function RoomDetailDaftar($user_id, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/detail/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'user_id'   => $user_id,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomDetailCheckList($page, $per_page, $search, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/detail/checklist';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid,
            'user_id'   => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomDetailSimpan($fasilitas, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/detail/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'fasilitas' => $fasilitas,
            'companyid' => $companyid,
            'user_id'   => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomDetailHapus($kode_fasilitas, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/detail/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_fasilitas'    => $kode_fasilitas,
            'companyid'         => $companyid,
            'user_id'           => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Room Tipe
    //==================================================================================
    public static function RoomTipeDaftar($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/tipe/daftar';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomTipeDetail($kode_tipe, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/tipe/detail';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_tipe'    => $kode_tipe,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomTipeCekKodeRoomTipe($kode_tipe, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/tipe/cek/tidak-terdaftar';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_tipe'    => $kode_tipe,
            'companyid'         => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomTipeSimpan($kode_tipe, $nama_tipe, $grade, $harga, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/tipe/simpan';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_tipe'     => $kode_tipe,
            'nama_tipe'     => $nama_tipe,
            'grade'         => $grade,
            'harga'         => $harga,
            'companyid'     => $companyid,
            'user_id'       => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function RoomTipeHapus($kode_tipe, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'room/tipe/hapus';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_tipe'  => $kode_tipe,
            'companyid'     => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Option
    //==================================================================================
    public static function OptionCustomer($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'option/customer';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function OptionJabatan($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'option/jabatan';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function OptionLayanan($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'option/layanan';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function OptionItem($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'option/item';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function OptionPlatform($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'option/platform';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function OptionRoomReservasi($kode_tipe, $check_in, $check_out, $page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'option/room/reservasi';
        $header = ['Authorization' => $credential];
        $body = [
            'kode_tipe' => $kode_tipe,
            'check_in'  => $check_in,
            'check_out' => $check_out,
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    public static function OptionRoomTipe($page, $per_page, $search, $companyid)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'option/room/tipe';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }

    //==================================================================================
    // Option Check List
    //==================================================================================
    public static function OptionCheckListRoomFasilitas($page, $per_page, $search, $companyid, $user_id)
    {
        $credential = 'Basic ' . base64_encode(config('constants.api_key.api_username') . ':' . config('constants.api_key.api_password'));
        $request = 'option/checklist/room/fasilitas';
        $header = ['Authorization' => $credential];
        $body = [
            'page'      => $page ?? 1,
            'per_page'  => $per_page ?? 10,
            'search'    => $search,
            'companyid' => $companyid,
            'user_id'   => $user_id
        ];
        $response = ApiRequest::requestPost($request, $header, $body);
        return $response;
    }
}
