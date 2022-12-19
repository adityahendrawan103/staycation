<?php

namespace App\Http\Controllers\App\Master\Karyawan;

use App\Helpers\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class KaryawanController extends Controller
{
    public function daftarKaryawan(Request $request) {
        $responseApi = ApiService::KaryawanDaftar($request->get('page'), $request->get('per_page'), $request->get('search'),
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

            return view ('layouts.master.karyawan.karyawan.karyawan', [
                'data_karyawan'     => $data,
                'data_page'         => $data_page->first()
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function formAddKaryawan() {
        return view('layouts.master.karyawan.karyawan.karyawanform', [
            'title' => 'Tambah Data Karyawan'
        ]);
    }

    public function formEditKaryawan($nik, Request $request) {
        $responseApi = ApiService::KaryawanDetail(strtoupper(trim($nik)),
                strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            return view('layouts.master.karyawan.karyawan.karyawanform', [
                'title'         => 'Edit Data Karyawan',
                'nik'           => strtoupper(trim($data->nik)),
                'no_ktp'        => strtoupper(trim($data->no_ktp)),
                'nama'          => trim($data->nama),
                'jenis_kelamin' => trim($data->jenis_kelamin),
                'kode_jabatan'  => trim($data->kode_jabatan),
                'nama_jabatan'  => trim($data->nama_jabatan),
                'tempat_lahir'  => trim($data->tempat_lahir),
                'tanggal_lahir' => trim($data->tanggal_lahir),
                'alamat'        => trim($data->alamat),
                'rt'            => trim($data->rt),
                'rw'            => trim($data->rw),
                'kelurahan'     => trim($data->kelurahan),
                'kecamatan'     => trim($data->kecamatan),
                'kabupaten'     => trim($data->kabupaten),
                'provinsi'      => trim($data->provinsi),
                'agama'         => trim($data->agama),
                'telepon'       => trim($data->telepon),
                'foto'          => trim($data->foto),
                'status'        => strtoupper(trim($data->status)),
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function cekNIKKaryawan(Request $request) {
        $responseApi = ApiService::KaryawanCekNIK(strtoupper(trim($request->get('nik'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function cekNoKTPKaryawan(Request $request) {
        $responseApi = ApiService::KaryawanCekKTP(strtoupper(trim($request->get('nik'))),
                        strtoupper(trim($request->get('no_ktp'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }

    public function simpanKaryawan(Request $request) {
        $foto_sebelumnya = '';
        $responseApi = ApiService::KaryawanDetail(strtoupper(trim($request->get('nik'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;
            $foto_sebelumnya = $data->foto;
        }

        $foto = '';
        $image_file = $request->file('foto');

        if($image_file) {
            $extension = $image_file->getClientOriginalExtension();
            $rename_file = strtoupper(trim($request->get('nik'))).'.'.$extension;
            $path = trim(config('constants.app.app_asset_url')).'/images/karyawan/'.$rename_file;

            if(File::exists(trim(config('constants.app.app_asset_url')).'/images/karyawan/'.$rename_file)){
                File::delete(trim(config('constants.app.app_asset_url')).'/images/karyawan/'.$rename_file);
            }

            $image_file->move('assets/images/karyawan', $rename_file);
            $foto = $path;
        } else {
            $foto = $foto_sebelumnya;
        }

        $responseApi = ApiService::KaryawanSimpan(strtoupper(trim($request->get('nik'))), strtoupper(trim($request->get('no_ktp'))),
            trim($request->get('nama')), trim($request->get('jenis_kelamin')), strtoupper(trim($request->get('kode_jabatan'))),
            trim($request->get('tempat_lahir')), $request->get('tanggal_lahir'), trim($request->get('alamat')),
            trim($request->get('rt')), trim($request->get('rw')), trim($request->get('kelurahan')), trim($request->get('kecamatan')),
            trim($request->get('kabupaten')), trim($request->get('provinsi')), trim($request->get('agama')), trim($request->get('telepon')),
            trim($foto), strtoupper(trim($request->get('status'))), strtoupper(trim($request->session()->get('app_user_company_id'))),
            strtoupper(trim($request->session()->get('app_user_id'))));

        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            return redirect()->route('master.karyawan.karyawan.daftar-karyawan')->with('success', $messageApi);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function hapusKaryawan(Request $request) {
        $responseApi = ApiService::KaryawanHapus(strtoupper(trim($request->get('nik'))),
                        strtoupper(trim($request->session()->get('app_user_company_id'))));

        return json_decode($responseApi, true);
    }
}
