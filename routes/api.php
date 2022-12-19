<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'authBasic'], function() {
    Route::post('company/detail', 'Api\Master\CompanyController@detailCompany');

    Route::name('auth.')->group(function () {
        Route::post('auth/login', 'Api\Auth\AuthController@login');
        Route::post('auth/daftar', 'Api\Auth\AuthController@daftarUser');
        Route::post('auth/cek/userid', 'Api\Auth\AuthController@cekUserId');
        Route::post('auth/cek/email', 'Api\Auth\AuthController@cekEmail');
        Route::post('auth/simpan', 'Api\Auth\AuthController@simpanUser');
        Route::post('auth/hapus', 'Api\Auth\AuthController@hapusUser');
        Route::post('auth/ubahpassword', 'Api\Auth\AuthController@ubahPasswordUser');
        Route::post('auth/forgotpassword', 'Api\Auth\AuthController@forgotPassword');
    });

    Route::name('fasilitas.')->group(function () {
        Route::post('fasilitas/daftar', 'Api\Master\FasilitasController@daftarFasilitas');
        Route::post('fasilitas/detail', 'Api\Master\FasilitasController@detailFasilitas');
        Route::post('fasilitas/cek/terdaftar', 'Api\Master\FasilitasController@cekKodeFasilitasTerdaftar');
        Route::post('fasilitas/cek/tidak-terdaftar', 'Api\Master\FasilitasController@cekKodeFasilitasTidakTerdaftar');
        Route::post('fasilitas/simpan', 'Api\Master\FasilitasController@simpanFasilitas');
        Route::post('fasilitas/hapus', 'Api\Master\FasilitasController@hapusFasilitas');
    });

    Route::name('karyawan.')->group(function () {
        Route::post('karyawan/daftar', 'Api\Master\KaryawanController@daftarKaryawan');
        Route::post('karyawan/detail', 'Api\Master\KaryawanController@detailKaryawan');
        Route::post('karyawan/cek/terdaftar', 'Api\Master\KaryawanController@cekNikTerdaftar');
        Route::post('karyawan/cek/nik/tidak-terdaftar', 'Api\Master\KaryawanController@cekNikTidakTerdaftar');
        Route::post('karyawan/cek/ktp/tidak-terdaftar', 'Api\Master\KaryawanController@cekNoKTPTidakTerdaftar');
        Route::post('karyawan/simpan', 'Api\Master\KaryawanController@simpanKaryawan');
        Route::post('karyawan/hapus', 'Api\Master\KaryawanController@hapusKaryawan');
    });

    Route::name('customer.')->group(function () {
        Route::post('customer/daftar', 'Api\Master\CustomerController@daftarCustomer');
        Route::post('customer/detail', 'Api\Master\CustomerController@detailCustomer');
        Route::post('customer/cek/terdaftar', 'Api\Master\CustomerController@cekCustomerTerdaftar');
        Route::post('customer/cek/tidak-terdaftar', 'Api\Master\CustomerController@cekCustomerTidakTerdaftar');
        Route::post('customer/simpan', 'Api\Master\CustomerController@simpanCustomer');
        Route::post('customer/hapus', 'Api\Master\CustomerController@hapusCustomer');
    });

    Route::name('item.')->group(function () {
        Route::post('item/daftar', 'Api\Master\ItemController@daftarItem');
        Route::post('item/detail', 'Api\Master\ItemController@detailItem');
        Route::post('item/cek/terdaftar', 'Api\Master\ItemController@cekItemTerdaftar');
        Route::post('item/cek/tidak-terdaftar', 'Api\Master\ItemController@cekItemTidakTerdaftar');
        Route::post('item/simpan', 'Api\Master\ItemController@simpanItem');
        Route::post('item/hapus', 'Api\Master\ItemController@hapusItem');
    });

    Route::name('layanan.')->group(function () {
        Route::post('layanan/daftar', 'Api\Master\LayananController@daftarLayanan');
        Route::post('layanan/detail', 'Api\Master\LayananController@detailLayanan');
        Route::post('layanan/cek/terdaftar', 'Api\Master\LayananController@cekLayananTerdaftar');
        Route::post('layanan/cek/tidak-terdaftar', 'Api\Master\LayananController@cekLayananTidakTerdaftar');
        Route::post('layanan/simpan', 'Api\Master\LayananController@simpanLayanan');
        Route::post('layanan/hapus', 'Api\Master\LayananController@hapusLayanan');
    });

    Route::name('jabatan.')->group(function () {
        Route::post('jabatan/daftar', 'Api\Master\JabatanController@daftarJabatan');
        Route::post('jabatan/detail', 'Api\Master\JabatanController@detailJabatan');
        Route::post('jabatan/cek/terdaftar', 'Api\Master\JabatanController@cekJabatanTerdaftar');
        Route::post('jabatan/cek/tidak-terdaftar', 'Api\Master\JabatanController@cekJabatanTidakTerdaftar');
        Route::post('jabatan/simpan', 'Api\Master\JabatanController@simpanJabatan');
        Route::post('jabatan/hapus', 'Api\Master\JabatanController@hapusJabatan');
    });

    Route::name('platform.')->group(function () {
        Route::post('platform/daftar', 'Api\Master\PlatformController@daftarPlatform');
        Route::post('platform/detail', 'Api\Master\PlatformController@detailPlatform');
        Route::post('platform/cek/terdaftar', 'Api\Master\PlatformController@cekPlatformTerdaftar');
        Route::post('platform/cek/tidak-terdaftar', 'Api\Master\PlatformController@cekPlatformTidakTerdaftar');
        Route::post('platform/simpan', 'Api\Master\PlatformController@simpanPlatform');
        Route::post('platform/hapus', 'Api\Master\PlatformController@hapusPlatform');
    });

    Route::name('room.')->group(function () {
        Route::name('detail.')->group(function () {
            Route::post('room/detail/daftar', 'Api\Master\RoomDetailController@daftarRoomDetail');
            Route::post('room/detail/checklist', 'Api\Master\RoomDetailController@checkListRoomDetail');
            Route::post('room/detail/simpan', 'Api\Master\RoomDetailController@simpanRoomDetail');
            Route::post('room/detail/hapus', 'Api\Master\RoomDetailController@hapusRoomDetail');
        });

        Route::name('tipe.')->group(function () {
            Route::post('room/tipe/daftar', 'Api\Master\RoomTipeController@daftarRoomTipe');
            Route::post('room/tipe/detail', 'Api\Master\RoomTipeController@detailRoomTipe');
            Route::post('room/tipe/cek/terdaftar', 'Api\Master\RoomTipeController@cekKodeRoomTipeTerdaftar');
            Route::post('room/tipe/cek/tidak-terdaftar', 'Api\Master\RoomTipeController@cekKodeRoomTipeTidakTerdaftar');
            Route::post('room/tipe/simpan', 'Api\Master\RoomTipeController@simpanRoomTipe');
            Route::post('room/tipe/hapus', 'Api\Master\RoomTipeController@hapusRoomTipe');
        });

        Route::name('fasilitas.')->group(function () {
            Route::post('room/fasilitas/daftar', 'Api\Master\RoomFasilitasController@daftarRoomFasilitas');
            Route::post('room/fasilitas/detail', 'Api\Master\RoomFasilitasController@detailRoomFasilitas');
            Route::post('room/fasilitas/cek/terdaftar', 'Api\Master\RoomFasilitasController@cekKodeRoomFasilitasTerdaftar');
            Route::post('room/fasilitas/cek/tidak-terdaftar', 'Api\Master\RoomFasilitasController@cekKodeRoomFasilitasTidakTerdaftar');
            Route::post('room/fasilitas/simpan', 'Api\Master\RoomFasilitasController@simpanRoomFasilitas');
            Route::post('room/fasilitas/hapus', 'Api\Master\RoomFasilitasController@hapusRoomFasilitas');
        });

        Route::post('room/daftar', 'Api\Master\RoomController@daftarRoom');
        Route::post('room/detail', 'Api\Master\RoomController@detailRoom');
        Route::post('room/cek/tidak-terdaftar', 'Api\Master\RoomController@cekKodeRoomTidakTerdaftar');
        Route::post('room/simpan', 'Api\Master\RoomController@simpanRoom');
        Route::post('room/hapus', 'Api\Master\RoomController@hapusRoom');
        Route::post('room/hapustmp', 'Api\Master\RoomController@hapusRoomTmp');
    });

    Route::name('reservasi.')->group(function () {
        Route::name('checkin.')->group(function () {
            Route::name('detail.')->group(function () {
                Route::post('reservasi/checkin/detail/daftar', 'Api\Transaksi\Reservasi\CheckIn\ReservasiDetailController@daftarReservasiDetail');
                Route::post('reservasi/checkin/detail/form', 'Api\Transaksi\Reservasi\CheckIn\ReservasiDetailController@detailReservasiDetail');
                Route::post('reservasi/checkin/detail/simpan', 'Api\Transaksi\Reservasi\CheckIn\ReservasiDetailController@simpanReservasiDetail');
                Route::post('reservasi/checkin/detail/hapus', 'Api\Transaksi\Reservasi\CheckIn\ReservasiDetailController@hapusReservasiDetail');
            });

            Route::post('reservasi/checkin/daftar', 'Api\Transaksi\Reservasi\CheckIn\ReservasiController@daftarReservasi');
            Route::post('reservasi/checkin/detail', 'Api\Transaksi\Reservasi\CheckIn\ReservasiController@detailReservasi');
            Route::post('reservasi/checkin/cek/noreferensi', 'Api\Transaksi\Reservasi\CheckIn\ReservasiController@cekNoReferensiReservasi');
            Route::post('reservasi/checkin/check-in', 'Api\Transaksi\Reservasi\CheckIn\ReservasiController@checkInReservasi');
            Route::post('reservasi/checkin/simpan', 'Api\Transaksi\Reservasi\CheckIn\ReservasiController@simpanReservasi');
            Route::post('reservasi/checkin/hapus', 'Api\Transaksi\Reservasi\CheckIn\ReservasiController@hapusReservasi');
            Route::post('reservasi/checkin/hapustmp', 'Api\Transaksi\Reservasi\CheckIn\ReservasiController@hapusReservasiTemp');
        });

        Route::name('inhouse.')->group(function () {
            Route::post('reservasi/inhouse/daftar', 'Api\Transaksi\Reservasi\InHouse\ReservasiInHouseController@daftarReservasiInHouse');
            Route::post('reservasi/inhouse/detail', 'Api\Transaksi\Reservasi\InHouse\ReservasiInHouseController@detailReservasiInHouse');
            Route::post('reservasi/inhouse/simpan/editroom', 'Api\Transaksi\Reservasi\InHouse\ReservasiInHouseController@simpanReservasiInHouseEditRoom');
            Route::post('reservasi/inhouse/simpan/extendroom', 'Api\Transaksi\Reservasi\InHouse\ReservasiInHouseController@simpanReservasiInHouseExtendRoom');
            Route::post('reservasi/inhouse/simpan', 'Api\Transaksi\Reservasi\InHouse\ReservasiInHouseController@simpanReservasiInHouse');
        });

        Route::name('penalty.')->group(function () {
            Route::name('detail.')->group(function () {
                Route::post('reservasi/penalty/detail/daftar', 'Api\Transaksi\Reservasi\Penalty\ReservasiPenaltyDetailController@daftarReservasiPenaltyDetail');
                Route::post('reservasi/penalty/detail/form', 'Api\Transaksi\Reservasi\Penalty\ReservasiPenaltyDetailController@detailReservasiPenaltyDetail');
                Route::post('reservasi/penalty/detail/simpan', 'Api\Transaksi\Reservasi\Penalty\ReservasiPenaltyDetailController@simpanReservasiPenaltyDetail');
                Route::post('reservasi/penalty/detail/hapus', 'Api\Transaksi\Reservasi\Penalty\ReservasiPenaltyDetailController@hapusReservasiPenaltyDetail');
            });

            Route::post('reservasi/penalty/daftar', 'Api\Transaksi\Reservasi\Penalty\ReservasiPenaltyController@daftarReservasiPenalty');
            Route::post('reservasi/penalty/detail', 'Api\Transaksi\Reservasi\Penalty\ReservasiPenaltyController@detailReservasiPenalty');
            Route::post('reservasi/penalty/simpan', 'Api\Transaksi\Reservasi\Penalty\ReservasiPenaltyController@simpanReservasiPenalty');
            Route::post('reservasi/penalty/hapus', 'Api\Transaksi\Reservasi\Penalty\ReservasiPenaltyController@hapusReservasiPenalty');
        });

        Route::name('checkout.')->group(function () {
            Route::post('reservasi/checkout/detail', 'Api\Transaksi\Reservasi\CheckOut\ReservasiCheckOutController@detailReservasiCheckOut');
            Route::post('reservasi/checkout/detail/cek/pembayaran', 'Api\Transaksi\Reservasi\CheckOut\ReservasiCheckOutController@cekStatusPembayaranReservasi');
            Route::post('reservasi/checkout/detail/simpan', 'Api\Transaksi\Reservasi\CheckOut\ReservasiCheckOutController@simpanReservasiCheckOut');
        });

        Route::name('history.')->group(function () {
            Route::post('reservasi/history/daftar', 'Api\Transaksi\Reservasi\History\ReservasiHistoryController@daftarReservasiHistory');
            Route::post('reservasi/history/detail', 'Api\Transaksi\Reservasi\History\ReservasiHistoryController@detailReservasiHistory');
        });
    });

    Route::name('refund.')->group(function () {
        Route::post('refund/daftar', 'Api\Transaksi\Refund\RefundController@daftarRefund');
        Route::post('refund/detail', 'Api\Transaksi\Refund\RefundController@detailRefund');
        Route::post('refund/cek/status', 'Api\Transaksi\Refund\RefundController@cekStatusRefund');
        Route::post('refund/simpan', 'Api\Transaksi\Refund\RefundController@simpanRefund');
        Route::post('refund/hapus', 'Api\Transaksi\Refund\RefundController@hapusRefund');
    });


    Route::name('option.')->group(function () {
        Route::post('option/customer', 'Api\Option\OptionController@optionCustomer');
        Route::post('option/jabatan', 'Api\Option\OptionController@optionJabatan');
        Route::post('option/item', 'Api\Option\OptionController@optionItem');
        Route::post('option/platform', 'Api\Option\OptionController@optionPlatform');
        Route::post('option/layanan', 'Api\Option\OptionController@optionLayanan');
        Route::post('option/room', 'Api\Option\OptionController@optionRoom');
        Route::post('option/room/reservasi', 'Api\Option\OptionController@optionRoomReservasi');
        Route::post('option/room/tipe', 'Api\Option\OptionController@optionRoomTipe');
    });
});
