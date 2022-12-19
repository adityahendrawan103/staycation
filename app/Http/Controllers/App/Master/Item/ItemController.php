<?php

namespace App\Http\Controllers\App\Master\Item;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ItemController extends Controller
{
    public function daftarItem(Request $request) {
        $responseApi = ApiService::ItemDaftar($request->get('page'), $request->get('per_page'), $request->get('search'),
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

            return view ('layouts.master.item.item', [
                'data_item'     => $data,
                'data_page'     => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formItem(Request $request) {
        $responseApi = ApiService::ItemDetail($request->get('kode_item'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function cekKodeItem(Request $request) {
        $responseApi = ApiService::ItemCekKodeItem($request->get('kode_item'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanItem(Request $request) {
        $responseApi = ApiService::ItemSimpan(strtoupper(trim($request->get('kode_item'))),
                        trim($request->get('nama_item')), str_replace(',', '', trim($request->get('harga_denda'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))),
                        strtoupper(trim($request->session()->get('app_user_id'))));

        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('master.item.daftar-item')->with('success', $messageApi);
        } else {
            return redirect()->route('master.item.daftar-item')->with('failed', $messageApi);
        }
    }

    public function hapusItem(Request $request) {
        $responseApi = ApiService::ItemHapus(strtoupper(trim($request->get('kode_item'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
