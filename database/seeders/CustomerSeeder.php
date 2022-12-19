<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
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

    	for($i = 1; $i <= 200; $i++){
            $gender = $fakerEn->randomElement(['male', 'female']);
    		DB::table('customer')->insert([
    			'no_identitas'  => $fakerEn->creditCardNumber,
    			'jenis_identitas' => $fakerEn->randomElement(['KTP', 'SIM']),
    			'nama'          => $fakerEn->name($gender),
    			'tempat_lahir'  => $fakerEn->city,
    			'tanggal_lahir' => $fakerIndo->date($format = 'Y-m-d', $max = 'now'),
    			'jenis_kelamin' => ($gender == 'male') ? 'Laki-Laki' : 'Perempuan',
    			'alamat'        => $fakerEn->address,
    			'kecamatan'     => $fakerEn->streetName,
    			'kabupaten'     => $fakerEn->citySuffix,
    			'provinsi'      => $fakerEn->country,
    			'pekerjaan'     => $fakerIndo->jobTitle,
    			'telepon'       => $fakerEn->phoneNumber,
    			'email'         => $fakerEn->freeEmail,
    			'created_at'    => '2022-10-10 22:57:28',
    			'companyid'     => 'EB',
    			'usertime'      => '2022-10-10=22:57:28=ADITYA',
    		]);

    	}
    }
}
