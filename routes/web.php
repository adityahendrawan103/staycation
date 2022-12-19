<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/email/resetpassword', function() {
    return view('layouts.auth.email.resetpassword');
});

Route::group(['middleware' => 'preventbackhistory'], function () {
    Route::get('/', 'App\Dashboard\DashboardController@index')->middleware('authLogin')->name('dashboard');

    Route::name('auth.')->group(function () {
        Route::get('/login', 'App\Auth\AuthController@index')->middleware('guest')->name('index');
        Route::post('/login/proses', 'App\Auth\AuthController@login')->middleware('guest')->name('login');

        Route::get('/forgotpassword', 'App\Auth\AuthController@forgotPassword')->middleware('guest')->name('forgot-password');
        Route::post('/forgotpassword/proses', 'App\Auth\AuthController@forgotPasswordProses')->middleware('guest')->name('forgot-password-proses');
    });

    Route::group(['middleware' => 'authLogin'], function () {
        Route::name('master.')->group(function () {
            Route::name('karyawan.')->group(function () {
                Route::name('jabatan.')->group(function () {
                    Route::get('/master/karyawan/jabatan/daftar', 'App\Master\Karyawan\JabatanController@daftarJabatan')->name('daftar-jabatan');
                    Route::get('/master/karyawan/jabatan/form', 'App\Master\Karyawan\JabatanController@formJabatan')->name('form-jabatan');
                    Route::post('/master/karyawan/jabatan/cek/kode', 'App\Master\Karyawan\JabatanController@cekKodeJabatan')->name('cek-kode-jabatan');
                    Route::post('/master/karyawan/jabatan/simpan', 'App\Master\Karyawan\JabatanController@simpanJabatan')->name('simpan-jabatan');
                    Route::post('/master/karyawan/jabatan/hapus', 'App\Master\Karyawan\JabatanController@hapusJabatan')->name('hapus-jabatan');
                });

                Route::name('karyawan.')->group(function () {
                    Route::get('/master/karyawan/karyawan/daftar', 'App\Master\Karyawan\KaryawanController@daftarKaryawan')->name('daftar-karyawan');
                    Route::get('/master/karyawan/karyawan/form/add', 'App\Master\Karyawan\KaryawanController@formAddKaryawan')->name('form-add-karyawan');
                    Route::get('/master/karyawan/karyawan/form/edit/{nik}', 'App\Master\Karyawan\KaryawanController@formEditKaryawan')->name('form-edit-karyawan');
                    Route::post('/master/karyawan/karyawan/cek/nik', 'App\Master\Karyawan\KaryawanController@cekNIKKaryawan')->name('cek-nik-karyawan');
                    Route::post('/master/karyawan/karyawan/cek/ktp', 'App\Master\Karyawan\KaryawanController@cekNoKTPKaryawan')->name('cek-ktp-karyawan');
                    Route::post('/master/karyawan/karyawan/simpan', 'App\Master\Karyawan\KaryawanController@simpanKaryawan')->name('simpan-karyawan');
                    Route::post('/master/karyawan/karyawan/hapus', 'App\Master\Karyawan\KaryawanController@hapusKaryawan')->name('hapus-karyawan');
                });
            });

            Route::name('customer.')->group(function () {
                Route::get('/master/customer/daftar', 'App\Master\Customer\CustomerController@daftarCustomer')->name('daftar-customer');
                Route::get('/master/customer/form/add', 'App\Master\Customer\CustomerController@formAddCustomer')->name('form-add-customer');
                Route::get('/master/customer/form/edit/{no_identitas}', 'App\Master\Customer\CustomerController@formEditCustomer')->name('form-edit-customer');
                Route::post('/master/customer/cek/identitas', 'App\Master\Customer\CustomerController@cekIdentitasCustomer')->name('cek-identitas-customer');
                Route::post('/master/customer/simpan', 'App\Master\Customer\CustomerController@simpanCustomer')->name('simpan-customer');
                Route::post('/master/customer/hapus', 'App\Master\Customer\CustomerController@hapusCustomer')->name('hapus-customer');
            });

            Route::name('item.')->group(function () {
                Route::get('/master/item/daftar', 'App\Master\Item\ItemController@daftarItem')->name('daftar-item');
                Route::get('/master/item/form', 'App\Master\Item\ItemController@formItem')->name('form-item');
                Route::post('/master/item/cek/kode', 'App\Master\Item\ItemController@cekKodeItem')->name('cek-kode-item');
                Route::post('/master/item/simpan', 'App\Master\Item\ItemController@simpanItem')->name('simpan-item');
                Route::post('/master/item/hapus', 'App\Master\Item\ItemController@hapusItem')->name('hapus-item');
            });

            Route::name('room.')->group(function () {
                Route::name('fasilitas.')->group(function () {
                    Route::get('/master/room/fasilitas/daftar', 'App\Master\Room\FasilitasController@daftarFasilitas')->name('daftar-fasilitas');
                    Route::get('/master/room/fasilitas/form', 'App\Master\Room\FasilitasController@formFasilitas')->name('form-fasilitas');
                    Route::post('/master/room/fasilitas/cek/kode', 'App\Master\Room\FasilitasController@cekKodeFasilitas')->name('cek-kode-fasilitas');
                    Route::post('/master/room/fasilitas/simpan', 'App\Master\Room\FasilitasController@simpanFasilitas')->name('simpan-fasilitas');
                    Route::post('/master/room/fasilitas/hapus', 'App\Master\Room\FasilitasController@hapusFasilitas')->name('hapus-fasilitas');
                });

                Route::name('tipe.')->group(function () {
                    Route::get('/master/room/tipe/daftar', 'App\Master\Room\RoomTipeController@daftarRoomTipe')->name('daftar-tipe-room');
                    Route::get('/master/room/tipe/form', 'App\Master\Room\RoomTipeController@formRoomTipe')->name('form-tipe-room');
                    Route::post('/master/room/tipe/cek/kode', 'App\Master\Room\RoomTipeController@cekKodeRoomTipe')->name('cek-kode-tipe-room');
                    Route::post('/master/room/tipe/simpan', 'App\Master\Room\RoomTipeController@simpanRoomTipe')->name('simpan-tipe-room');
                    Route::post('/master/room/tipe/hapus', 'App\Master\Room\RoomTipeController@hapusRoomTipe')->name('hapus-tipe-room');
                });

                Route::name('detail.')->group(function () {
                    Route::get('/master/room/detail/daftar', 'App\Master\Room\RoomDetailController@daftarRoomDetail')->name('daftar-room-detail');
                    Route::get('/master/room/detail/checklist', 'App\Master\Room\RoomDetailController@checkListRoomDetail')->name('check-list-room-detail');
                    Route::post('/master/room/detail/simpan', 'App\Master\Room\RoomDetailController@simpanRoomDetail')->name('simpan-room-detail');
                    Route::post('/master/room/detail/hapus', 'App\Master\Room\RoomDetailController@hapusRoomDetail')->name('hapus-room-detail');
                });

                Route::get('/master/room/room/daftar', 'App\Master\Room\RoomController@daftarRoom')->name('daftar-room');
                Route::get('/master/room/room/daftar/detail', 'App\Master\Room\RoomController@daftarDetailRoom')->name('daftar-detail-room');
                Route::get('/master/room/room/form/add', 'App\Master\Room\RoomController@formAddRoom')->name('form-add-room');
                Route::get('/master/room/room/form/edit/{kode_room}', 'App\Master\Room\RoomController@formEditRoom')->name('form-edit-room');
                Route::post('/master/room/room/cek/kode', 'App\Master\Room\RoomController@cekKodeRoom')->name('cek-kode-room');
                Route::post('/master/room/room/simpan', 'App\Master\Room\RoomController@simpanRoom')->name('simpan-room');
                Route::post('/master/room/room/hapus', 'App\Master\Room\RoomController@hapusRoom')->name('hapus-room');
            });

            Route::name('layanan.')->group(function () {
                Route::get('/master/layanan/daftar', 'App\Master\Layanan\LayananController@daftarLayanan')->name('daftar-layanan');
                Route::get('/master/layanan/form', 'App\Master\Layanan\LayananController@formLayanan')->name('form-layanan');
                Route::post('/master/layanan/cek/kode', 'App\Master\Layanan\LayananController@cekKodeLayanan')->name('cek-kode-layanan');
                Route::post('/master/layanan/simpan', 'App\Master\Layanan\LayananController@simpanLayanan')->name('simpan-layanan');
                Route::post('/master/layanan/hapus', 'App\Master\Layanan\LayananController@hapusLayanan')->name('hapus-layanan');
            });

            Route::name('platform.')->group(function () {
                Route::get('/master/platform/daftar', 'App\Master\Platform\PlatformController@daftarPlatform')->name('daftar-platform');
                Route::get('/master/platform/form', 'App\Master\Platform\PlatformController@formPlatform')->name('form-platform');
                Route::post('/master/platform/cek/kode', 'App\Master\Platform\PlatformController@cekKodePlatform')->name('cek-kode-platform');
                Route::post('/master/platform/simpan', 'App\Master\Platform\PlatformController@simpanPlatform')->name('simpan-platform');
                Route::post('/master/platform/hapus', 'App\Master\Platform\PlatformController@hapusPlatform')->name('hapus-platform');
            });
        });

        Route::name('transaksi.')->group(function () {
            Route::name('reservasi.')->group(function () {
                Route::name('checkin.')->group(function () {
                    Route::name('detail.')->group(function () {
                        Route::get('/transaksi/reservasi/checkin/detail/daftar', 'App\Transaksi\Reservasi\CheckIn\ReservasiDetailController@daftarReservasiCheckInDetail')->name('daftar-reservasi-detail');
                        Route::get('/transaksi/reservasi/checkin/detail/form', 'App\Transaksi\Reservasi\CheckIn\ReservasiDetailController@formReservasiCheckInDetail')->name('form-reservasi-detail');
                        Route::post('/transaksi/reservasi/checkin/detail/simpan', 'App\Transaksi\Reservasi\CheckIn\ReservasiDetailController@simpanReservasiCheckInDetail')->name('simpan-reservasi-detail');
                        Route::post('/transaksi/reservasi/checkin/detail/hapus', 'App\Transaksi\Reservasi\CheckIn\ReservasiDetailController@hapusReservasiCheckInDetail')->name('hapus-reservasi-detail');
                    });
                    Route::get('/transaksi/reservasi/checkin/daftar', 'App\Transaksi\Reservasi\CheckIn\ReservasiController@daftarReservasiCheckIn')->name('daftar-reservasi');
                    Route::get('/transaksi/reservasi/checkin/form/add', 'App\Transaksi\Reservasi\CheckIn\ReservasiController@formAddReservasiCheckIn')->name('form-add-reservasi');
                    Route::post('/transaksi/reservasi/checkin/cek/noreferensi', 'App\Transaksi\Reservasi\CheckIn\ReservasiController@cekNoReferensiReservasiCheckIn')->name('cek-no-referensi-reservasi');
                    Route::get('/transaksi/reservasi/checkin/form/edit/{kode_reservasi}', 'App\Transaksi\Reservasi\CheckIn\ReservasiController@formEditReservasiCheckIn')->where('kode_reservasi', '(.*)')->name('form-edit-reservasi');
                    Route::post('/transaksi/reservasi/checkin/simpan', 'App\Transaksi\Reservasi\CheckIn\ReservasiController@simpanReservasiCheckIn')->name('simpan-reservasi');
                    Route::post('/transaksi/reservasi/checkin/check-in', 'App\Transaksi\Reservasi\CheckIn\ReservasiController@checkInReservasiCheckIn')->name('check-in-reservasi');
                    Route::post('/transaksi/reservasi/checkin/hapus', 'App\Transaksi\Reservasi\CheckIn\ReservasiController@hapusReservasiCheckIn')->name('hapus-reservasi');
                });

                Route::name('inhouse.')->group(function () {
                    Route::get('/transaksi/reservasi/inhouse/daftar', 'App\Transaksi\Reservasi\InHouse\ReservasiInHouseController@daftarReservasiInHouse')->name('daftar-reservasi-inhouse');
                    Route::get('/transaksi/reservasi/inhouse/form/{kode_reservasi}', 'App\Transaksi\Reservasi\InHouse\ReservasiInHouseController@formReservasiInHouse')->where('kode_reservasi', '(.*)')->name('form-reservasi-inhouse');
                    Route::post('/transaksi/reservasi/inhouse/form/simpan/editroom/{kode_reservasi}', 'App\Transaksi\Reservasi\InHouse\ReservasiInHouseController@simpanEditRoom')->where('kode_reservasi', '(.*)')->name('simpan-edit-room-reservasi-inhouse');
                    Route::post('/transaksi/reservasi/inhouse/form/simpan/extendroom/{kode_reservasi}', 'App\Transaksi\Reservasi\InHouse\ReservasiInHouseController@simpanExtendRoom')->where('kode_reservasi', '(.*)')->name('simpan-extend-room-reservasi-inhouse');
                    Route::post('/transaksi/reservasi/inhouse/form/simpan', 'App\Transaksi\Reservasi\InHouse\ReservasiInHouseController@simpanReservasiInHouse')->name('simpan-reservasi-inhouse');
                });

                Route::name('penalty.')->group(function () {
                    Route::name('detail.')->group(function () {
                        Route::get('/transaksi/reservasi/penalty/detail/daftar', 'App\Transaksi\Reservasi\Penalty\ReservasiPenaltyDetailController@daftarReservasiPenaltyDetail')->name('daftar-reservasi-penalty-detail');
                        Route::get('/transaksi/reservasi/penalty/detail/form', 'App\Transaksi\Reservasi\Penalty\ReservasiPenaltyDetailController@formReservasiPenaltyDetail')->name('form-reservasi-penalty-detail');
                        Route::post('/transaksi/reservasi/penalty/detail/simpan', 'App\Transaksi\Reservasi\Penalty\ReservasiPenaltyDetailController@simpanReservasiPenaltyDetail')->name('simpan-reservasi-penalty-detail');
                        Route::post('/transaksi/reservasi/penalty/detail/hapus', 'App\Transaksi\Reservasi\Penalty\ReservasiPenaltyDetailController@hapusReservasiPenaltyDetail')->name('hapus-reservasi-penalty-detail');
                    });
                    Route::get('/transaksi/reservasi/penalty/daftar', 'App\Transaksi\Reservasi\Penalty\ReservasiPenaltyController@daftarReservasiPenalty')->name('daftar-reservasi-penalty');
                    Route::get('/transaksi/reservasi/penalty/form/{kode_reservasi}', 'App\Transaksi\Reservasi\Penalty\ReservasiPenaltyController@formReservasiPenalty')->where('kode_reservasi', '(.*)')->name('form-reservasi-penalty');
                    Route::post('/transaksi/reservasi/penalty/simpan', 'App\Transaksi\Reservasi\Penalty\ReservasiPenaltyController@simpanReservasiPenalty')->name('simpan-reservasi-penalty');
                    Route::post('/transaksi/reservasi/penalty/hapus', 'App\Transaksi\Reservasi\Penalty\ReservasiPenaltyController@hapusReservasiPenalty')->name('hapus-reservasi-penalty');
                });

                Route::name('checkout.')->group(function () {
                    Route::get('/transaksi/reservasi/checkout/daftar', 'App\Transaksi\Reservasi\CheckOut\ReservasiCheckOutController@daftarReservasiCheckOut')->name('daftar-reservasi-checkout');
                    Route::get('/transaksi/reservasi/checkout/form/{kode_reservasi}', 'App\Transaksi\Reservasi\CheckOut\ReservasiCheckOutController@formCheckOutReservasi')->where('kode_reservasi', '(.*)')->name('form-reservasi-checkout');
                    Route::post('/transaksi/reservasi/checkout/form/cek/pembayaran', 'App\Transaksi\Reservasi\CheckOut\ReservasiCheckOutController@cekPembayaranReservasiCheckOut')->name('cek-pembayaran-reservasi-checkout');
                    Route::post('/transaksi/reservasi/checkout/form/simpan', 'App\Transaksi\Reservasi\CheckOut\ReservasiCheckOutController@simpanReservasiCheckOut')->name('simpan-reservasi-checkout');
                });

                Route::name('history.')->group(function () {
                    Route::get('/transaksi/reservasi/history/daftar', 'App\Transaksi\Reservasi\History\ReservasiHistoryController@daftarReservasiHistory')->name('daftar-reservasi-history');
                    Route::get('/transaksi/reservasi/history/form/{kode_reservasi}', 'App\Transaksi\Reservasi\History\ReservasiHistoryController@formHistoryReservasi')->where('kode_reservasi', '(.*)')->name('form-reservasi-history');
                });
            });
        });

        Route::name('option.')->group(function () {
            Route::get('/option/customer', 'App\Option\OptionController@optionCustomer')->name('option-customer');
            Route::get('/option/jabatan', 'App\Option\OptionController@optionJabatan')->name('option-jabatan');
            Route::get('/option/layanan', 'App\Option\OptionController@optionLayanan')->name('option-layanan');
            Route::get('/option/item', 'App\Option\OptionController@optionItem')->name('option-item');
            Route::get('/option/platform', 'App\Option\OptionController@optionPlatform')->name('option-platform');
            Route::get('/option/room/reservasi', 'App\Option\OptionController@optionRoomReservasi')->name('option-room-reservasi');
            Route::get('/option/roomtipe', 'App\Option\OptionController@optionRoomTipe')->name('option-room-tipe');
        });

    });
});
