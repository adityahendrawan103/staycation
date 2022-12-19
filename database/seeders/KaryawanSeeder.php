<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakerIndo = Faker::create('id_ID');
        $fakerEn = Faker::create('en_US');

    	for($i = 1; $i <= 50; $i++){
            $gender = $fakerEn->randomElement(['male', 'female']);
    		DB::table('karyawan')->insert([
    			'nik'           => $fakerEn->creditCardNumber,
    			'kode_jabatan'  => $fakerEn->randomElement(['ADM', 'RCP']),
    			'nama'          => $fakerEn->name($gender),
    			'tempat_lahir'  => $fakerEn->city,
    			'tanggal_lahir' => $fakerIndo->date($format = 'Y-m-d', $max = 'now'),
    			'jenis_kelamin' => ($gender == 'male') ? 'Laki-Laki' : 'Perempuan',
    			'alamat'        => $fakerEn->address,
    			'rt'            => $fakerIndo->buildingNumber,
    			'rw'            => $fakerIndo->buildingNumber,
    			'kelurahan'     => $fakerEn->streetSuffix,
    			'kecamatan'     => $fakerEn->streetName,
    			'kabupaten'     => $fakerEn->citySuffix,
    			'provinsi'      => $fakerEn->country,
    			'agama'         => $fakerEn->randomElement(['Islam', 'Kristen', 'Hindu', 'Buddha']),
    			'telepon'       => $fakerEn->phoneNumber,
    			'foto'          => 'https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    			'created_at'    => '2022-10-10 22:57:28',
    			'companyid'     => 'EB',
    			'usertime'      => '2022-10-10=22:57:28=ADITYA',
    		]);

    	}
    }
}
