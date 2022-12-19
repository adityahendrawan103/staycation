<?php

namespace App\Http\Controllers\App\Master\Room;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FasilitasController extends Controller
{
    public function daftarFasilitas(Request $request) {
        $responseApi = ApiService::FasilitasDaftar($request->get('page'), $request->get('per_page'), $request->get('search'),
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

            return view ('layouts.master.room.fasilitas.fasilitas', [
                'data_fasilitas'    => $data,
                'data_page'         => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formFasilitas(Request $request) {
        $responseApi = ApiService::FasilitasDetail($request->get('kode_fasilitas'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function cekKodeFasilitas(Request $request) {
        $responseApi = ApiService::FasilitasCekKodeFasilitas($request->get('kode_fasilitas'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanFasilitas(Request $request) {
        $responseApi = ApiService::FasilitasSimpan(strtoupper(trim($request->get('kode_fasilitas'))),
                        trim($request->get('nama_fasilitas')), str_replace(',', '', trim($request->get('harga'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));

        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('master.room.fasilitas.daftar-fasilitas')->with('success', $messageApi);
        } else {
            return redirect()->route('master.room.fasilitas.daftar-fasilitas')->with('failed', $messageApi);
        }
    }

    public function hapusFasilitas(Request $request) {
        $responseApi = ApiService::FasilitasHapus(strtoupper(trim($request->get('kode_fasilitas'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
