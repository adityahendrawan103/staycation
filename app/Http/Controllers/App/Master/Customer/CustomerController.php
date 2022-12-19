<?php

namespace App\Http\Controllers\App\Master\Customer;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CustomerController extends Controller
{
    public function daftarCustomer(Request $request) {
        $responseApi = ApiService::CustomerDaftar($request->get('page'), $request->get('per_page'), $request->get('search'),
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

            return view ('layouts.master.customer.customer', [
                'data_customer'     => $data,
                'data_page'         => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formAddCustomer() {
        return view('layouts.master.customer.customerform', [
            'title' => 'Tambah Data Customer'
        ]);
    }

    public function formEditCustomer($no_identitas, Request $request) {
        $responseApi = ApiService::CustomerDetail(strtoupper(trim($no_identitas)),
                strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            return view('layouts.master.customer.customerform', [
                'title'         => 'Edit Data Customer',
                'no_identitas'  => strtoupper(trim($data->no_identitas)),
                'jenis_identitas' => strtoupper(trim($data->jenis_identitas)),
                'nama'          => trim($data->nama),
                'jenis_kelamin' => trim($data->jenis_kelamin),
                'tempat_lahir'  => trim($data->tempat_lahir),
                'tanggal_lahir' => $data->tanggal_lahir,
                'alamat'        => trim($data->alamat),
                'kota'          => trim($data->kota),
                'pekerjaan'     => trim($data->pekerjaan),
                'telepon'       => trim($data->telepon),
                'email'         => trim($data->email)
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }


    }

    public function cekIdentitasCustomer(Request $request) {
        $responseApi = ApiService::CustomerCekNoIdentitas($request->get('no_identitas'),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanCustomer(Request $request) {
        $responseApi = ApiService::CustomerSimpan(strtoupper(trim($request->get('no_identitas'))),
                strtoupper(trim($request->get('jenis_identitas'))), trim($request->get('nama')),
                trim($request->get('tempat_lahir')), trim($request->get('tanggal_lahir')),
                trim($request->get('jenis_kelamin')), trim($request->get('alamat')),
                trim($request->get('kota')), trim($request->get('pekerjaan')),
                trim($request->get('telepon')), trim($request->get('email')),
                strtoupper(trim($request->session()->get('app_user_company_id'))),
                            strtoupper(trim($request->session()->get('app_user_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('master.customer.daftar-customer')->with('success', $messageApi);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function hapusCustomer(Request $request) {
        $responseApi = ApiService::CustomerHapus(strtoupper(trim($request->get('no_identitas'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
