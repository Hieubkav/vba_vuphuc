<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👤 Tạo dữ liệu User...');

        $users = [
            [
                'name' => 'Admin VBA Vũ Phúc',
                'email' => 'admin@vbavuphuc.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'order' => 1,
            ],
            [
                'name' => 'Vũ Phúc',
                'email' => 'vuphuc@vbavuphuc.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'order' => 2,
            ],
            [
                'name' => 'Quản trị viên',
                'email' => 'manager@vbavuphuc.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'order' => 3,
            ],
            [
                'name' => 'Biên tập viên',
                'email' => 'editor@vbavuphuc.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'order' => 4,
            ],
            [
                'name' => 'Hỗ trợ khách hàng',
                'email' => 'support@vbavuphuc.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'order' => 5,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info("✅ Đã tạo " . count($users) . " users");
    }
}
