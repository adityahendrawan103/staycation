<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class JabatanSeeder extends Seeder
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
    		DB::table('jabatan')->insert([
    			'kode_jabatan'  => $i,
    			'nama_jabatan'  => $fakerEn->jobTitle,
    			'created_at'    => '2022-10-10 22:57:28',
    			'companyid'     => 'EB',
    			'usertime'      => '2022-10-10=22:57:28=ADITYA',
    		]);

    	}
    }
}
