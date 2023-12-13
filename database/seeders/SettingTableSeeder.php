<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    use RefreshDatabase;
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run(): void
    {
        $data=array(
            'description'=>"This is an e-commerce website for our Integrated Engineering Team Project",
            'short_des'=>"IETP E Commerce",
            'photo'=>"image.jpg",
            'logo'=>'logo.jpg',
            'address'=>"Addis Ababa Science and Technology University, Ethiopia",
            'email'=>"ietp@ietp.com",
            'phone'=>"+251 111-111-111",
        );
        DB::table('settings')->insert($data);
    }
}
