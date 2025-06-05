<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sửa lỗi dữ liệu JSON cho các trường requirements và what_you_learn
        $courses = DB::table('courses')->get();

        foreach ($courses as $course) {
            $updates = [];

            // Xử lý trường requirements
            if (!empty($course->requirements)) {
                if (is_string($course->requirements)) {
                    $decoded = json_decode($course->requirements, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Nếu không phải JSON hợp lệ, đặt thành array rỗng
                        $updates['requirements'] = json_encode([]);
                    }
                }
            } else {
                // Nếu null hoặc rỗng, đặt thành array rỗng
                $updates['requirements'] = json_encode([]);
            }

            // Xử lý trường what_you_learn
            if (!empty($course->what_you_learn)) {
                if (is_string($course->what_you_learn)) {
                    $decoded = json_decode($course->what_you_learn, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Nếu không phải JSON hợp lệ, đặt thành array rỗng
                        $updates['what_you_learn'] = json_encode([]);
                    }
                }
            } else {
                // Nếu null hoặc rỗng, đặt thành array rỗng
                $updates['what_you_learn'] = json_encode([]);
            }

            // Cập nhật nếu có thay đổi
            if (!empty($updates)) {
                DB::table('courses')
                    ->where('id', $course->id)
                    ->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần rollback vì chỉ làm sạch dữ liệu
    }
};
