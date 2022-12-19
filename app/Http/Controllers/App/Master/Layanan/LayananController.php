<?php

namespace App\Http\Controllers\App\Master\Layanan;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LayananController extends Controller
{
    public function daftarLayanan(Request $request) {
        $responseApi = ApiService::LayananDaftar($request->get('page'), $request->get('per_page'), $request->get('search'),
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

            return view ('layouts.master.layanan.layanan', [
                'data_layanan'     => $data,
                'data_page'     => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formLayanan(Request $request) {
        $responseApi = ApiService::LayananDetail($request->get('kode_layanan'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function cekKodeLayanan(Request $request) {
        $responseApi = ApiService::LayananCekKodeLayanan($request->get('kode_layanan'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanLayanan(Request $request) {
        $responseApi = ApiService::LayananSimpan(strtoupper(trim($request->get('kode_layanan'))),
                        trim($request->get('nama_layanan')), trim($request->get('satuan')),
                        str_replace(',', '', trim($request->get('harga'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));

        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('master.layanan.daftar-layanan')->with('success', $messageApi);
        } else {
            return redirect()->route('master.layanan.daftar-layanan')->with('failed', $messageApi);
        }
    }

    public function hapusLayanan(Request $request) {
        $responseApi = ApiService::LayananHapus(strtoupper(trim($request->get('kode_layanan'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
