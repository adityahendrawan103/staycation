<?php

namespace App\Http\Controllers\App\Master\Room;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RoomController extends Controller
{
    public function daftarRoom(Request $request) {
        $responseApi = ApiService::RoomDaftar($request->get('page'), $request->get('per_page'), $request->get('search'),
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

            return view ('layouts.master.room.room.room', [
                'data_room'     => $data,
                'data_page'     => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formAddRoom(Request $request) {
        $responseApi = ApiService::RoomHapusTmp(strtoupper(trim($request->session()->get('app_user_id'))),
                            strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return view('layouts.master.room.room.roomform', [
                'title' => 'Tambah Data Room'
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formEditRoom($kode_room, Request $request) {
        $responseApi = ApiService::RoomDetail(strtoupper(trim($kode_room)),
                strtoupper(trim($request->session()->get('app_user_company_id'))),
                strtoupper(trim($request->session()->get('app_user_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            return view('layouts.master.room.room.roomform', [
                'title'         => 'Edit Data Room',
                'kode_room'     => strtoupper(trim($data->kode_room)),
                'kode_tipe'     => strtoupper(trim($data->kode_tipe)),
                'nama_tipe'     => trim($data->nama_tipe),
                'lantai'        => trim($data->lantai),
                'kapasitas'     => trim($data->kapasitas),
                'longtime'      => number_format($data->longtime),
                'shorttime'     => number_format($data->shorttime),
                'status'        => strtoupper(trim($data->status)),
                'keterangan'    => trim($data->keterangan),
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function cekKodeRoom(Request $request) {
        $responseApi = ApiService::RoomCekKodeRoom(strtoupper(trim($request->get('kode_room'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanRoom(Request $request) {
        $responseApi = ApiService::RoomSimpan(strtoupper(trim($request->get('kode_room'))),
                strtoupper(trim($request->get('kode_tipe'))), trim($request->get('lantai')),
                str_replace(',', '', trim($request->get('kapasitas'))),
                str_replace(',', '', trim($request->get('longtime'))),
                str_replace(',', '', trim($request->get('shorttime'))),
                trim($request->get('keterangan')), trim($request->get('status')),
                strtoupper(trim($request->session()->get('app_user_company_id'))),
                strtoupper(trim($request->session()->get('app_user_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('master.room.daftar-room')->with('success', $messageApi);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function hapusRoom(Request $request) {
        $responseApi = ApiService::RoomHapus(strtoupper(trim($request->get('kode_room'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
