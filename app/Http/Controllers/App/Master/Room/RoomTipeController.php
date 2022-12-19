<?php

namespace App\Http\Controllers\App\Master\Room;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RoomTipeController extends Controller
{
    public function daftarRoomTipe(Request $request) {
        $responseApi = ApiService::RoomTipeDaftar($request->get('page'), $request->get('per_page'), $request->get('search'),
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

            return view ('layouts.master.room.roomtipe.roomtipe', [
                'data_tipe'     => $data,
                'data_page'     => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formRoomTipe(Request $request) {
        $responseApi = ApiService::RoomTipeDetail($request->get('kode_tipe'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function cekKodeRoomTipe(Request $request) {
        $responseApi = ApiService::RoomTipeCekKodeRoomTipe($request->get('kode_tipe'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanRoomTipe(Request $request) {
        $responseApi = ApiService::RoomTipeSimpan(strtoupper(trim($request->get('kode_tipe'))),
                        trim($request->get('nama_tipe')), strtoupper(trim($request->get('grade'))),
                        str_replace(',', '', trim($request->get('harga'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));

        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('master.room.tipe.daftar-tipe-room')->with('success', $messageApi);
        } else {
            return redirect()->route('master.room.tipe.daftar-tipe-room')->with('failed', $messageApi);
        }
    }

    public function hapusRoomTipe(Request $request) {
        $responseApi = ApiService::RoomTipeHapus(strtoupper(trim($request->get('kode_tipe'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
