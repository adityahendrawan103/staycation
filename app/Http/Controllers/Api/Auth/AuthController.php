<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{
    public function login(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'email_username'    => 'required',
                'password'          => 'required'
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning("Email/username dan password tidak boleh kosong");
            }

            $sql = DB::table('users')
                    ->selectRaw("ifnull(users.user_id, '') as user_id, ifnull(users.role, '') as role, ifnull(users.nik, '') as nik,
                                ifnull(karyawan.nama, '') as nama_karyawan, ifnull(jabatan.nama_jabatan, '') as jabatan,
                                ifnull(users.email, '') as email, ifnull(users.password, '') as password, ifnull(karyawan.foto, '') as foto,
                                ifnull(users.companyid, '') as companyid")
                    ->leftJoin('karyawan', function($join) {
                        $join->on('karyawan.nik', '=', 'users.nik')
                            ->on('karyawan.companyid', '=', 'users.companyid');
                    })
                    ->leftJoin('jabatan', function($join) {
                        $join->on('jabatan.kode_jabatan', '=', 'karyawan.kode_jabatan')
                            ->on('jabatan.companyid', '=', 'users.companyid');
                    })
                    ->where('users.email', $request->get('email_username'))
                    ->orWhere('users.user_id', $request->get('email_username'))
                    ->first();

            if(empty($sql->email)) {
                return ApiResponse::responseWarning("Kombinasi email/username dan password tidak sesuai");
            } else {
                if(!Auth::attempt(['email' => $sql->email, 'password' => $request->password])) {
                    return ApiResponse::responseWarning("Kombinasi email/username dan password tidak sesuai");
                } else {
                    if((Hash::check(trim($request->get('password')), $sql->password, [])) == true) {
                        $data_user = new Collection();
                        $data_user->push((object) [
                            'user_id'   => strtoupper(trim($sql->user_id)),
                            'role'      => strtoupper(trim($sql->role)),
                            'nik'       => trim($sql->nik),
                            'nama'      => trim($sql->nama_karyawan),
                            'jabatan'   => trim($sql->jabatan),
                            'email'     => trim($sql->email),
                            'photo'     => trim($sql->foto),
                            'companyid' => strtoupper(trim($sql->companyid))
                        ]);
                        return ApiResponse::responseSuccess("success", $data_user->first());
                    } else {
                        return ApiResponse::responseWarning("Kombinasi email dan password tidak sesuai");
                    }
                }
            }
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('email'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), 'XXX');
        }
    }

    public function forgotPassword(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'email_username' => 'required|string'
            ]);

            if ($validate->fails()) {
                return ApiResponse::responseWarning('Isi alamat email anda dengan benar');
            }

            $sql = DB::table('users')
                    ->select('user_id','email')
                    ->where('email', trim($request->get('email_username')))
                    ->orWhere('user_id', trim($request->get('email_username')))
                    ->first();

            if (!empty($sql->user_id)) {
                $user_id = trim($sql->user_id);
                $email = trim($sql->email);
                $password = mt_rand(100000, 999999);

                $data = [
                    'subject'       => 'Forgot Password',
                    'email_from'    => 'programmer.enterbiner@gmail.com',
                    'email_to'      => trim($email),
                    'user_id'       => trim($user_id),
                    'new_password'  => $password,
                ];

                Mail::send('layouts.auth.email.resetpassword', $data,
                    function ($message) use ($data) {
                        $message->from($data['email_from']);
                        $message->to($data['email_to']);
                        $message->subject($data['subject']);
                    }
                );

                DB::transaction(function () use ($user_id, $password) {
                    DB::update('update users set password=? where user_id=?', [
                        bcrypt($password), trim($user_id)
                    ]);
                });

                return ApiResponse::responseSuccess('Password baru sudah dikirim ke alamat email anda, silahkan login dengan password baru anda', null);
            } else {
                return ApiResponse::responseWarning('Alamat email atau username tidak terdaftar');
            }
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('email'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), 'XXX');
        }
    }

    public function daftarUser(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'page'      => 'required',
                'per_page'  => 'required',
                'companyid' => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data company terlebih dahulu');
            }

            $sql = DB::table('users')
                    ->selectRaw("ifnull(users.user_id, '') as user_id, ifnull(users.role, '') as role, ifnull(users.nik, '') as nik,
                                ifnull(karyawan.nama, '') as nama_karyawan, ifnull(jabatan.nama_jabatan, '') as jabatan,
                                ifnull(users.email, '') as email, ifnull(karyawan.foto, '') as foto,
                                ifnull(users.companyid, '') as companyid")
                    ->leftJoin('karyawan', function($join) {
                        $join->on('karyawan.nik', '=', 'users.nik')
                            ->on('karyawan.companyid', '=', 'users.companyid');
                    })
                    ->leftJoin('jabatan', function($join) {
                        $join->on('jabatan.kode_jabatan', '=', 'karyawan.kode_jabatan')
                            ->on('jabatan.companyid', '=', 'users.companyid');
                    })
                    ->where('users.companyid', $request->get('companyid'))
                    ->paginate(empty($request->get('per_page')) ? 10 : $request->get('per_page'));

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('email'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), 'XXX');
        }
    }

    public function cekUserId(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'user_id'       => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data user id terlebih dahulu');
            }

            $sql = DB::table('users')
                    ->selectRaw("ifnull(users.user_id, '') as user_id, ifnull(users.companyid, '') as companyid")
                    ->where('users.user_id', strtoupper($request->get('user_id')))
                    ->where('users.companyid', strtoupper($request->get('companyid')))
                    ->first();

            if(!empty($sql->user_id)) {
                return ApiResponse::responseWarning('User Id yang anda entry sudah terdaftar');
            }
            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('email'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), 'XXX');
        }
    }

    public function cekEmail(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'email'         => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data email terlebih dahulu');
            }

            $sql = DB::table('users')
                    ->selectRaw("ifnull(users.email, '') as email, ifnull(users.companyid, '') as companyid")
                    ->where('users.email', strtoupper($request->get('email')))
                    ->where('users.companyid', strtoupper($request->get('companyid')))
                    ->first();

            if(!empty($sql->email)) {
                return ApiResponse::responseWarning('Email yang anda entry sudah terdaftar');
            }
            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('email'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), 'XXX');
        }
    }

    public function simpanUser(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'user_id'       => 'required',
                'nik'           => 'required',
                'role'          => 'required',
                'email'         => 'required',
                'status_add'    => 'required',
                'user_entry'    => 'required',
                'companyid'     => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Isi data user secara lengkap');
            }

            if($request->get('status_add') == 1) {
                $validate = Validator::make($request->all(), [
                    'password'          => 'required',
                    'password_confirm'  => 'required',
                ]);

                if($validate->fails()) {
                    return ApiResponse::responseWarning('Data kata sandi dan konfirmasi kata sandi harus terisi');
                }

                if($request->get('password') != $request->get('password_confirm')) {
                    return ApiResponse::responseWarning('Data kata sandi dan konfirmasi kata sandi tidak sesuai');
                }

                $sql = DB::table('users')
                    ->selectRaw("ifnull(users.nik, '') as nik,
                                ifnull(users.companyid, '') as companyid")
                    ->where('users.nik', strtoupper($request->get('nik')))
                    ->where('users.companyid', $request->get('companyid'))
                    ->first();

                if(!empty($sql->nik)) {
                    return ApiResponse::responseWarning('NIP yang anda pilih sudah memiliki user, silahkan lakukan proses update data');
                }

                $sql = DB::table('users')
                    ->selectRaw("ifnull(users.user_id, '') as user_id,
                                ifnull(users.companyid, '') as companyid")
                    ->where('users.user_id', strtoupper($request->get('user_id')))
                    ->where('users.companyid', strtoupper($request->get('companyid')))
                    ->first();

                if(!empty($sql->user_id)) {
                    return ApiResponse::responseWarning('User Id yang anda entry sudah terdaftar');
                }

                $sql = DB::table('users')
                    ->selectRaw("ifnull(users.email, '') as email,
                                ifnull(users.companyid, '') as companyid")
                    ->where('users.email', $request->get('email'))
                    ->where('users.companyid', strtoupper($request->get('companyid')))
                    ->first();

                if(!empty($sql->email)) {
                    return ApiResponse::responseWarning('Email yang anda entry sudah terdaftar');
                }
            } else {
                $sql = DB::table('users')
                    ->selectRaw("ifnull(users.user_id, '') as user_id, ifnull(users.email, '') as email,
                                ifnull(users.companyid, '') as companyid")
                    ->where('users.email', $request->get('email'))
                    ->where('users.companyid', $request->get('companyid'))
                    ->first();

                if(!empty($sql->email)) {
                    if(trim(strtoupper($sql->user_id)) != trim(strtoupper($request->get('user_id')))) {
                        return ApiResponse::responseWarning('Email yang anda entry sudah terdaftar');
                    }
                }
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_users_simpan (?,?,?,?,?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('nik'))),
                    trim(strtoupper($request->get('role'))), trim($request->get('email')),
                    bcrypt($request->get('password')), trim(strtoupper($request->get('companyid'))),
                    trim(strtoupper($request->get('user_entry')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Disimpan', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('email'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), 'XXX');
        }
    }

    public function ubahPasswordUser(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'user_id'           => 'required',
                'password'          => 'required',
                'password_confirm'  => 'required',
                'user_entry'        => 'required',
                'companyid'         => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Data kata sandi dan konfirmasi kata sandi harus terisi');
            }

            if($request->get('password') != $request->get('password_confirm')) {
                return ApiResponse::responseWarning('Data kata sandi dan konfirmasi kata sandi tidak sesuai');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_users_ubahpassword (?,?,?,?)', [
                    trim(strtoupper($request->get('user_id'))), bcrypt($request->get('password')),
                    trim(strtoupper($request->get('companyid'))), trim(strtoupper($request->get('user_entry')))
                ]);
            });

            return ApiResponse::responseSuccess('Password atau kata sandi berhasil diubah', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('email'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), 'XXX');
        }
    }

    public function hapusUser(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'user_id'           => 'required',
                'companyid'         => 'required',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning('Pilih data user terlebih dahulu');
            }

            DB::transaction(function () use ($request) {
                DB::insert('call sp_users_hapus (?,?)', [
                    trim(strtoupper($request->get('user_id'))), trim(strtoupper($request->get('companyid')))
                ]);
            });

            return ApiResponse::responseSuccess('Data Berhasil Dihapus', null);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('email'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), 'XXX');
        }
    }
}
