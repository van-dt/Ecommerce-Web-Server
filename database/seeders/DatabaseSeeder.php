<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('categories')->insert([
            ['name' => 'Xe Cộ'],
            ['name' => 'Đồ Điện Tử'],
            ['name' => 'Đồ Ăn, Thực Phẩm'],
            ['name' => 'Thể Thao & Du Lịch'],
            ['name' => 'Sức Khỏe'],
            ['name' => 'Nhà Sách Online'],
            ['name' => 'Thời Trang Nữ'],
            ['name' => 'Thiết Bị Điện Gia Dụng'],
            ['name' => 'Thời Trang Nam'],
            ['name' => 'Nhà Cửu & Đời Sống']
        ]);
    }
}
