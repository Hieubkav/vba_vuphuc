<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;

class CourseStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Tạo dữ liệu đăng ký khóa học...');

        $courses = Course::all();
        $students = Student::all();
        $enrollmentCount = 0;

        foreach ($courses as $course) {
            // Mỗi khóa học có 5-15 học viên đăng ký
            $numEnrollments = rand(5, 15);
            $selectedStudents = $students->random($numEnrollments);

            foreach ($selectedStudents as $student) {
                // Kiểm tra xem đã đăng ký chưa
                if (!$course->students()->where('student_id', $student->id)->exists()) {
                    $enrolledAt = Carbon::now()->subDays(rand(1, 90));
                    $progress = rand(0, 100);
                    $status = $this->getEnrollmentStatus($progress);
                    
                    $completedAt = null;
                    if ($status === 'completed') {
                        $completedAt = $enrolledAt->copy()->addDays(rand(30, 60));
                    }

                    $course->students()->attach($student->id, [
                        'enrolled_at' => $enrolledAt,
                        'completed_at' => $completedAt,
                        'status' => $status,
                        'progress_percentage' => $progress,
                        'total_study_hours' => rand(10, 100),
                        'final_score' => $status === 'completed' ? rand(70, 100) : null,
                        'notes' => $this->getRandomNote(),
                        'completion_data' => json_encode([
                            'assignments_completed' => rand(5, 20),
                            'quizzes_passed' => rand(3, 10),
                            'projects_submitted' => rand(1, 5),
                        ]),
                        'created_at' => $enrolledAt,
                        'updated_at' => $completedAt ?? $enrolledAt,
                    ]);
                    
                    $enrollmentCount++;
                }
            }
        }

        $this->command->info("✅ Đã tạo {$enrollmentCount} đăng ký khóa học");
    }

    private function getEnrollmentStatus(int $progress): string
    {
        if ($progress >= 100) {
            return 'completed';
        } elseif ($progress >= 50) {
            return 'in_progress';
        } elseif ($progress > 0) {
            return 'in_progress';
        } else {
            return 'enrolled';
        }
    }

    private function getRandomNote(): string
    {
        $notes = [
            'Học viên tích cực và có tiến bộ tốt',
            'Cần hỗ trợ thêm về phần VBA nâng cao',
            'Hoàn thành xuất sắc các bài tập',
            'Thường xuyên tham gia thảo luận',
            'Có kinh nghiệm thực tế tốt',
            'Cần cải thiện kỹ năng debug',
            'Rất nhiệt tình trong học tập',
            'Đã áp dụng được vào công việc thực tế',
            'Học viên có nền tảng IT tốt',
            'Cần thêm thời gian để hoàn thành',
        ];

        return $notes[array_rand($notes)];
    }
}
