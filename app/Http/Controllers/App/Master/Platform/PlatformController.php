<?php

namespace App\Http\Controllers\App\Master\Platform;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PlatformController extends Controller
{
    public function daftarPlatform(Request $request) {
        $responseApi = ApiService::PlatformDaftar($request->get('page'), $request->get('per_page'), $request->get('search'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            $data_page = new Collection();
            $data_page->push((object) [
                'search'        => $request->get('search'),
                'current_page'  => $data->from,
                'from'          => $data->from,
                'to'            => $data->to,
                'total'         => $data->total,
                'page'          => $data->current_page,
                'per_page'      => $data->per_page
            ]);

            return view ('layouts.master.platform.platform', [
                'data_platform'     => $data,
                'data_page'         => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formPlatform(Request $request) {
        $responseApi = ApiService::PlatformDetail($request->get('kode_platform'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function cekKodePlatform(Request $request) {
        $responseApi = ApiService::PlatformCekKodePlatform($request->get('kode_platform'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanplatform(Request $request) {
        $responseApi = ApiService::PlatformSimpan(strtoupper(trim($request->get('kode_platform'))),
                        trim($request->get('nama_platform')), strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('master.platform.daftar-platform')->with('success', $messageApi);
        } else {
            return redirect()->route('master.platform.daftar-platform')->with('failed', $messageApi);
        }
    }

    public function hapusPlatform(Request $request) {
        $responseApi = ApiService::PlatformHapus(strtoupper(trim($request->get('kode_platform'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
