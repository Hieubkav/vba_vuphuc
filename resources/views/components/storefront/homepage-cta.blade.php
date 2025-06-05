<div class="absolute inset-0 opacity-10">
    <div class="absolute inset-0 bg-pattern"></div>
</div>
<div class="container mx-auto px-4 relative z-10">
    <div class="flex flex-col md:flex-row items-center justify-between gap-8 md:gap-16">
        <div class="text-center md:text-left max-w-2xl">
            <span class="text-xs uppercase tracking-widest font-semibold bg-white bg-opacity-20 px-3 py-1 rounded-full mb-4 inline-block">Học tập chuyên nghiệp</span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 leading-tight">Bắt đầu hành trình<br>với <span class="italic">VBA Vũ Phúc</span></h2>
            <p class="text-white text-opacity-90 text-lg">Khám phá các khóa học VBA chất lượng cao và chuyên sâu. Học tập hiệu quả, hỗ trợ tận tâm từ giảng viên.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('courses.index') }}" class="px-8 py-4 bg-white text-red-700 font-semibold rounded-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center min-w-[180px]">
                Xem khóa học
            </a>
            <a href="{{ route('students.register') }}" class="px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-red-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center min-w-[180px]">
                Đăng ký học
            </a>
        </div>
    </div>
</div>