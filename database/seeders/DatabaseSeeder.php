<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Bắt đầu tạo dữ liệu mẫu đầy đủ cho dự án VBA Vũ Phúc...');

        $this->call([
            // Bước 1: Dữ liệu cơ bản
            UserSeeder::class,
            SettingSeeder::class,
            CatPostSeeder::class,
            CatCourseSeeder::class,
            InstructorSeeder::class,
            PartnerSeeder::class,
            AssociationSeeder::class,
            SliderSeeder::class,

            // Bước 2: Dữ liệu chính
            PostSeeder::class,
            CourseSeeder::class,
            StudentSeeder::class,
            AlbumSeeder::class,

            // Bước 3: Dữ liệu phụ thuộc
            PostImageSeeder::class,
            CourseImageSeeder::class,
            CourseMaterialSeeder::class,
            AlbumImageSeeder::class,
            CourseStudentSeeder::class,
            MenuItemSeeder::class,
        ]);

        $this->command->info('✅ Hoàn thành tạo dữ liệu mẫu!');
    }
}
