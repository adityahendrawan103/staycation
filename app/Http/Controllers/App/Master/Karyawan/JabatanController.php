<?php

namespace App\Http\Controllers\App\Master\Karyawan;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class JabatanController extends Controller
{
    public function daftarJabatan(Request $request) {
        $responseApi = ApiService::JabatanDaftar($request->get('page'), $request->get('per_page'), $request->get('search'),
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

            return view ('layouts.master.karyawan.jabatan.jabatan', [
                'data_jabatan'  => $data,
                'data_page'     => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formJabatan(Request $request) {
        $responseApi = ApiService::JabatanDetail($request->get('kode_jabatan'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function cekKodeJabatan(Request $request) {
        $responseApi = ApiService::JabatanCekKodeJabatan($request->get('kode_jabatan'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanJabatan(Request $request) {
        $responseApi = ApiService::JabatanSimpan(strtoupper(trim($request->get('kode_jabatan'))),
                        trim($request->get('nama_jabatan')), strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('master.karyawan.jabatan.daftar-jabatan')->with('success', $messageApi);
        } else {
            return redirect()->route('master.karyawan.jabatan.daftar-jabatan')->with('failed', $messageApi);
        }
    }

    public function hapusJabatan(Request $request) {
        $responseApi = ApiService::JabatanHapus(strtoupper(trim($request->get('kode_jabatan'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
