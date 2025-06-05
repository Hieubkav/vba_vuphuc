<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Tạo 50 học viên mẫu
        $students = [];

        for ($i = 1; $i <= 50; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $firstName = $gender === 'male' ? $faker->firstNameMale : $faker->firstNameFemale;
            $lastName = $faker->lastName;
            $fullName = $lastName . ' ' . $firstName;

            $students[] = [
                'name' => $fullName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'birth_date' => $faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
                'gender' => $gender,
                'address' => $faker->address,
                'occupation' => $faker->randomElement([
                    'Kế toán viên', 'Nhân viên văn phòng', 'Giáo viên', 'Sinh viên',
                    'Quản lý', 'Nhân viên kinh doanh', 'Chuyên viên IT', 'Freelancer',
                    'Chủ doanh nghiệp', 'Nhân viên ngân hàng', 'Nhân viên marketing',
                    'Chuyên viên nhân sự', 'Kỹ sư', 'Bác sĩ', 'Luật sư'
                ]),
                'education_level' => $faker->randomElement([
                    'high_school', 'college', 'university', 'master', 'phd'
                ]),
                'learning_goals' => $faker->randomElement([
                    'Nâng cao kỹ năng Excel để làm việc hiệu quả hơn',
                    'Học VBA để tự động hóa công việc hàng ngày',
                    'Cải thiện kỹ năng phân tích dữ liệu',
                    'Chuẩn bị cho công việc mới trong lĩnh vực kế toán',
                    'Phát triển kỹ năng quản lý dự án',
                    'Tăng cường kiến thức tin học văn phòng',
                    'Học để thăng tiến trong công việc hiện tại',
                    'Chuẩn bị kiến thức cho việc khởi nghiệp',
                    'Cập nhật kỹ năng công nghệ mới',
                    'Chuyển đổi nghề nghiệp sang lĩnh vực mới'
                ]),
                'interests' => json_encode($faker->randomElements([
                    'Excel', 'VBA', 'Kế toán', 'Quản lý', 'Phân tích dữ liệu',
                    'Tin học văn phòng', 'Dashboard', 'Báo cáo', 'Tự động hóa',
                    'Lập trình', 'Kinh doanh', 'Marketing', 'Tài chính'
                ], $faker->numberBetween(2, 5))),
                'status' => $faker->randomElement(['active', 'inactive']),
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now()
            ];
        }

        // Chèn dữ liệu vào database
        foreach (array_chunk($students, 10) as $chunk) {
            Student::insert($chunk);
        }

        // Tạo quan hệ enrollment giữa students và courses
        $this->createEnrollments();

        $this->command->info('Đã tạo thành công 50 học viên mẫu và các enrollment!');
    }

    /**
     * Tạo các enrollment giữa students và courses
     */
    private function createEnrollments()
    {
        $faker = Faker::create();
        $students = Student::all();
        $courses = Course::all();

        if ($students->isEmpty() || $courses->isEmpty()) {
            return;
        }

        foreach ($students as $student) {
            // Mỗi học viên đăng ký 1-3 khóa học ngẫu nhiên
            $numberOfCourses = $faker->numberBetween(1, 3);
            $selectedCourses = $courses->random($numberOfCourses);

            foreach ($selectedCourses as $course) {
                // Kiểm tra xem đã đăng ký chưa để tránh trùng lặp
                if (!$student->courses()->where('course_id', $course->id)->exists()) {
                    $enrollmentDate = $faker->dateTimeBetween('-1 year', 'now');

                    $status = $faker->randomElement([
                        'enrolled',      // 40% đang học
                        'completed',     // 30% đã hoàn thành
                        'in_progress',   // 25% đang trong quá trình
                        'dropped'        // 5% đã bỏ học
                    ]);

                    // Tính progress dựa trên status
                    $progress = match($status) {
                        'enrolled' => $faker->numberBetween(0, 20),
                        'in_progress' => $faker->numberBetween(21, 80),
                        'completed' => 100,
                        'dropped' => $faker->numberBetween(5, 50),
                        default => 0
                    };

                    $student->courses()->attach($course->id, [
                        'enrolled_at' => $enrollmentDate,
                        'completed_at' => $status === 'completed' ?
                            $faker->dateTimeBetween($enrollmentDate, 'now') : null,
                        'status' => $status,
                        'progress_percentage' => $progress,
                        'total_study_hours' => $faker->numberBetween(0, $course->duration_hours ?? 40),
                        'final_score' => $status === 'completed' ?
                            $faker->randomFloat(2, 6.0, 10.0) : null,
                        'notes' => $faker->optional(0.2)->sentence(),
                        'created_at' => $enrollmentDate,
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }
}
