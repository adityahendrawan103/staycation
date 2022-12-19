<?php

namespace App\Http\Controllers\App\Auth;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index() {
        return view('layouts.auth.login');
    }

    public function login(Request $request) {
        $responseApi = ApiService::AuthLogin($request->get('email_username'), $request->get('password'));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if ($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $request->session()->flush();

            session()->put('authenticated', trim($data->companyid).trim($data->user_id).trim($data->email));
            session()->put('app_user_id', trim($data->user_id));
            session()->put('app_user_nama', trim($data->nama));
            session()->put('app_user_jabatan', trim($data->jabatan));
            session()->put('app_user_role', trim($data->role));
            session()->put('app_user_email', trim($data->email));
            session()->put('app_user_photo', trim($data->photo));
            session()->put('app_user_company_id', trim($data->companyid));

            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function forgotPassword() {
        return view('layouts.auth.forgotpassword');
    }

    public function forgotPasswordProses(Request $request) {
        $responseApi = ApiService::AuthForgotPassword($request->get('email_username'));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if ($statusApi == 1) {
            return redirect()->route('auth.index')->with('success', $messageApi);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }
}
